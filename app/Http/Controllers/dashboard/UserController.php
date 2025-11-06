<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\UserStoreRequest;
use App\Http\Requests\dashboard\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * عرض قائمة المستخدمين مع الفلترة والبحث
     */
    public function index(Request $request)
    {
        $this->authorize('manage-users');

        // الحصول على معاملات البحث والفلترة
        $selectedRole = $request->get('role', 'all');
        $search = $request->get('search');
        $status = $request->get('status', 'all');

        // بناء الاستعلام
        $query = User::query();

        // فلترة حسب الدور (القديم)
        if ($selectedRole !== 'all' && !in_array($selectedRole, ['admin', 'moderator', 'user', 'trader'])) {
            // محاولة البحث في الأدوار الجديدة
            $query->whereHas('roles', function ($q) use ($selectedRole) {
                $q->where('name', $selectedRole);
            });
        } elseif ($selectedRole !== 'all') {
            $query->where('role', $selectedRole);
        }

        // فلترة حسب الحالة
        if ($status !== 'all' && in_array($status, ['pending', 'active', 'inactive', 'banned'])) {
            $query->where('status', $status);
        }

        // البحث
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // جلب المستخدمين مع العلاقات
        $users = $query->with(['roles'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // الحصول على الأدوار القديمة للتوافق
        $oldRoles = [
            'admin' => 'مدير',
            'moderator' => 'مشرف',
            'user' => 'مستخدم',
            'trader' => 'متداول',
        ];

        // الحصول على الأدوار الجديدة من قاعدة البيانات
        $dbRoles = $this->getDbRoles();

        // الإحصائيات العامة
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'pending' => User::where('status', 'pending')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'banned' => User::where('status', 'banned')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        // عداد لكل دور (للتابات) - القديم
        $roleCounts = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // إحصائيات الأدوار الجديدة
        $roleCountsNew = $this->getRoleCountsNew();

        return view('dashboard.users.index', compact(
            'users',
            'oldRoles',
            'dbRoles',
            'selectedRole',
            'status',
            'search',
            'stats',
            'roleCounts',
            'roleCountsNew'
        ));
    }

    /**
     * عرض نموذج إنشاء مستخدم جديد
     */
    public function create()
    {
        $this->authorize('manage-users');
        
        $dbRoles = $this->getDbRoles();
        
        return view('dashboard.users.create', compact('dbRoles'));
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $roleIds = $request->input('role_ids', []);

        // رفع الصورة الشخصية
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // تشفير كلمة المرور
        $data['password'] = bcrypt($data['password']);

        // الحالة الافتراضية
        $data['status'] = $data['status'] ?? 'pending';
        
        // معالجة is_verified
        $data['is_verified'] = $request->has('is_verified') ? (bool)$request->input('is_verified') : false;

        // إنشاء المستخدم
        $user = User::create($data);

        // تعيين الأدوار الجديدة
        if (!empty($roleIds)) {
            $user->roles()->sync($roleIds);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * عرض تفاصيل مستخدم
     */
    public function show(User $user)
    {
        $this->authorize('manage-users');
        
        $user->load(['roles', 'auditLogs' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('dashboard.users.show', compact('user'));
    }

    /**
     * عرض نموذج تعديل مستخدم
     */
    public function edit(User $user)
    {
        $this->authorize('manage-users');
        
        $dbRoles = $this->getDbRoles();
        $user->load('roles');

        return view('dashboard.users.edit', compact('user', 'dbRoles'));
    }

    /**
     * تحديث بيانات مستخدم
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $roleIds = $request->input('role_ids', []);

        // رفع الصورة الشخصية الجديدة
        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // معالجة is_verified
        $data['is_verified'] = $request->has('is_verified') ? (bool)$request->input('is_verified') : false;

        // تحديث بيانات المستخدم
        $user->update($data);

        // تحديث الأدوار
        if ($request->has('role_ids')) {
            $user->roles()->sync($roleIds);
        } else {
            // إذا لم يتم إرسال role_ids، احذف جميع الأدوار
            $user->roles()->sync([]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    /**
     * حذف مستخدم
     */
    public function destroy(User $user)
    {
        $this->authorize('manage-users');

        // حذف الصورة الشخصية
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // حذف المستخدم
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }

    /**
     * اعتماد وتفعيل مستخدم
     */
    public function approve(User $user)
    {
        $this->authorize('manage-users');

        $user->update([
            'status' => 'active',
            'is_verified' => true
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم اعتماد وتفعيل المستخدم بنجاح.');
    }

    /**
     * إلغاء تفعيل مستخدم
     */
    public function deactivate(User $user)
    {
        $this->authorize('manage-users');

        $user->update(['status' => 'inactive']);

        return redirect()
            ->route('users.index')
            ->with('success', 'تم إلغاء تفعيل المستخدم بنجاح.');
    }

    /**
     * الحصول على الأدوار من قاعدة البيانات
     */
    private function getDbRoles()
    {
        try {
            if (class_exists(Role::class) && Schema::hasTable('roles')) {
                return Role::where('is_active', true)
                    ->orderBy('order')
                    ->orderBy('name')
                    ->get();
            }
        } catch (\Exception $e) {
            // في حالة عدم وجود جدول roles بعد
        }

        return collect();
    }

    /**
     * الحصول على إحصائيات الأدوار الجديدة
     */
    private function getRoleCountsNew()
    {
        try {
            if (Schema::hasTable('user_roles') && Schema::hasTable('roles')) {
                return DB::table('user_roles')
                    ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                    ->select('roles.name', DB::raw('COUNT(*) as count'))
                    ->groupBy('roles.name')
                    ->pluck('count', 'name')
                    ->toArray();
            }
        } catch (\Exception $e) {
            // في حالة عدم وجود الجداول بعد
        }

        return [];
    }
}
