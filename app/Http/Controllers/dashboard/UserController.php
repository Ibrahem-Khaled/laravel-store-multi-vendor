<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\UserStoreRequest;
use App\Http\Requests\dashboard\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- أضف هذا
use Illuminate\Support\Facades\DB;

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

        $users = $query->latest()->paginate(10)->withQueryString();

        $roles = ['admin', 'moderator', 'user', 'trader'];

        // الإحصائيات العامة
        $usersCount        = User::count();
        $activeUsersCount  = User::where('status', 'active')->count();
        $adminsCount       = User::where('role', 'admin')->count();

        // عداد لكل دور (للتابات)
        $roleCounts = User::select('role', DB::raw('COUNT(*) as c'))
            ->groupBy('role')->pluck('c', 'role'); // ['admin'=>10, 'user'=>50, ...]

        return view('dashboard.users.index', compact(
            'users',
            'roles',
            'selectedRole',
            'usersCount',
            'activeUsersCount',
            'adminsCount',
            'roleCounts'
        ));
    }


    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $data['password'] = bcrypt($data['password']);
        // status المبدئي للمستخدم الجديد يكون pending حتى تتم الموافقة
        $data['status'] = $data['status'] ?? 'pending';


        $user = User::create($data);
        return back()->with('success', 'تم إنشاء المستخدم بنجاح (بانتظار الاعتماد).');
    }


    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $user->update($data);
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
