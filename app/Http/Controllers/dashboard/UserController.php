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
        $filters = [
            'role' => $request->get('role', 'all'),
            'status' => $request->get('status', 'all'),
            'search' => $request->get('search'),
            'sort' => $request->get('sort', 'id'),
            'direction' => $request->get('direction', 'desc'),
        ];

        // بناء الاستعلام
        $query = User::query();

        // فلترة حسب الدور
        if ($filters['role'] !== 'all') {
            if (in_array($filters['role'], ['admin', 'moderator', 'user', 'trader'])) {
                $query->where('role', $filters['role']);
            } else {
                $query->whereHas('roles', function ($q) use ($filters) {
                    $q->where('name', $filters['role']);
                });
            }
        }

        // فلترة حسب الحالة
        if ($filters['status'] !== 'all' && in_array($filters['status'], ['pending', 'active', 'inactive', 'banned'])) {
            $query->where('status', $filters['status']);
        }

        // البحث
        if ($filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('uuid', 'like', "%{$search}%");
            });
        }

        // الترتيب
        $allowedSorts = ['id', 'name', 'email', 'created_at', 'status'];
        $sort = in_array($filters['sort'], $allowedSorts) ? $filters['sort'] : 'id';
        $direction = in_array($filters['direction'], ['asc', 'desc']) ? $filters['direction'] : 'desc';
        $query->orderBy($sort, $direction);

        // جلب المستخدمين مع العلاقات
        $users = $query->with(['roles'])
            ->paginate(20)
            ->withQueryString();

        // الإحصائيات
        $stats = $this->getStats();

        // الأدوار
        $oldRoles = [
            'admin' => 'مدير',
            'moderator' => 'مشرف',
            'user' => 'مستخدم',
            'trader' => 'تاجر',
        ];

        $dbRoles = $this->getDbRoles();
        $roleCounts = $this->getRoleCounts();

        return view('dashboard.users.index', compact(
            'users',
            'filters',
            'stats',
            'oldRoles',
            'dbRoles',
            'roleCounts'
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
        $data['is_verified'] = $request->input('is_verified', '0') == '1';

        // إنشاء المستخدم
        $user = User::create($data);

        // تعيين الأدوار
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
        
        $user->load([
            'roles',
            'auditLogs' => function ($query) {
                $query->latest()->limit(20);
            }
        ]);

        // إحصائيات المستخدم
        $userStats = [
            'total_orders' => 0, // يمكن إضافتها لاحقاً
            'total_products' => $user->products()->count() ?? 0,
            'total_brands' => $user->brands()->count() ?? 0,
        ];

        return view('dashboard.users.show', compact('user', 'userStats'));
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
        $data['is_verified'] = $request->input('is_verified', '0') == '1';

        // تحديث بيانات المستخدم
        $user->update($data);

        // تحديث الأدوار
        if ($request->has('role_ids')) {
            $user->roles()->sync($roleIds);
        } else {
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
     * تبديل حالة التوثيق
     */
    public function toggleVerification(User $user)
    {
        $this->authorize('manage-users');

        $user->update([
            'is_verified' => !$user->is_verified
        ]);

        $message = $user->is_verified 
            ? 'تم توثيق الحساب بنجاح.' 
            : 'تم إلغاء توثيق الحساب بنجاح.';

        return redirect()
            ->route('users.index')
            ->with('success', $message);
    }

    /**
     * الحصول على الإحصائيات
     */
    private function getStats()
    {
        return [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'pending' => User::where('status', 'pending')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'banned' => User::where('status', 'banned')->count(),
            'verified' => User::where('is_verified', true)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'traders' => User::where('role', 'trader')->count(),
        ];
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
     * الحصول على إحصائيات الأدوار
     */
    private function getRoleCounts()
    {
        $counts = [
            'old' => User::select('role', DB::raw('COUNT(*) as count'))
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray(),
            'new' => []
        ];

        try {
            if (Schema::hasTable('user_roles') && Schema::hasTable('roles')) {
                $counts['new'] = DB::table('user_roles')
                    ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                    ->select('roles.name', DB::raw('COUNT(*) as count'))
                    ->groupBy('roles.name')
                    ->pluck('count', 'name')
                    ->toArray();
            }
        } catch (\Exception $e) {
            // في حالة عدم وجود الجداول بعد
        }

        return $counts;
    }
}
