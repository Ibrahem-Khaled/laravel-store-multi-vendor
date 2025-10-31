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
    use AuthorizesRequests; // <-- أضف هذا السطر

    public function index(Request $request)
    {
        $this->authorize('manage-users');

        $selectedRole = $request->get('role', 'all');
        $search = $request->get('search');

        $query = User::query();
        if ($selectedRole !== 'all') {
            $query->where('role', $selectedRole);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->with('roles')->latest()->paginate(10)->withQueryString();

        // الحصول على الأدوار القديمة للتوافق
        $oldRoles = ['admin', 'moderator', 'user', 'trader'];
        
        // الحصول على الأدوار الجديدة من قاعدة البيانات
        $dbRoles = collect();
        try {
            if (class_exists(Role::class) && Schema::hasTable('roles')) {
                $dbRoles = Role::active()->orderBy('order')->get();
            }
        } catch (\Exception $e) {
            // في حالة عدم وجود جدول roles بعد
            $dbRoles = collect();
        }

        // الإحصائيات العامة
        $usersCount        = User::count();
        $activeUsersCount  = User::where('status', 'active')->count();
        $adminsCount       = User::where('role', 'admin')->count();

        // عداد لكل دور (للتابات) - القديم والجديد
        $roleCounts = User::select('role', DB::raw('COUNT(*) as c'))
            ->groupBy('role')->pluck('c', 'role');
        
        // إحصائيات الأدوار الجديدة
        $roleCountsNew = collect();
        try {
            if (Schema::hasTable('user_roles') && Schema::hasTable('roles')) {
                $roleCountsNew = DB::table('user_roles')
                    ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                    ->select('roles.name', DB::raw('COUNT(*) as count'))
                    ->groupBy('roles.name')
                    ->pluck('count', 'name');
            }
        } catch (\Exception $e) {
            // في حالة عدم وجود الجداول بعد
            $roleCountsNew = collect();
        }

        // إجمالي عدد الأدوار
        $totalRolesCount = count($oldRoles) + $dbRoles->count();

        return view('dashboard.users.index', compact(
            'users',
            'oldRoles',
            'dbRoles',
            'selectedRole',
            'usersCount',
            'activeUsersCount',
            'adminsCount',
            'roleCounts',
            'roleCountsNew',
            'totalRolesCount'
        ));
    }


    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $roleIds = $request->input('role_ids', []);
        
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $data['password'] = bcrypt($data['password']);
        // status المبدئي للمستخدم الجديد يكون pending حتى تتم الموافقة
        $data['status'] = $data['status'] ?? 'pending';

        $user = User::create($data);
        
        // تعيين الأدوار الجديدة
        if (!empty($roleIds)) {
            $user->roles()->sync($roleIds);
        }
        
        return back()->with('success', 'تم إنشاء المستخدم بنجاح (بانتظار الاعتماد).');
    }


    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $roleIds = $request->input('role_ids', []);
        
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        
        $user->update($data);
        
        // تحديث الأدوار
        if ($request->has('role_ids')) {
            $user->roles()->sync($roleIds);
        }

        return back()->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }


    public function destroy(User $user)
    {
        if ($user->avatar) Storage::disk('public')->delete($user->avatar);
        $user->delete();
        return back()->with('success', 'تم حذف المستخدم.');
    }


    public function approve(User $user)
    {
        $user->update(['status' => 'active', 'is_verified' => true]);
        return back()->with('success', 'تم اعتماد وتفعيل المستخدم.');
    }


    public function deactivate(User $user)
    {
        $user->update(['status' => 'inactive']);
        return back()->with('success', 'تم إلغاء تفعيل المستخدم.');
    }
}
