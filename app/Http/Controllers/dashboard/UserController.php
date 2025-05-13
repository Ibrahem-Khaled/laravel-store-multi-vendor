<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function index(Request $request)
    {
        // إحصائيات المستخدمين
        $usersCount = User::count();
        $activeUsersCount = User::where('status', 'active')->count();
        $adminsCount = User::where('role', 'admin')->count();
        $roles = ['admin', 'moderator', 'user', 'trader'];

        // فلترة حسب الدور
        $selectedRole = $request->role ?? 'all';
        $users = User::query();

        if ($selectedRole !== 'all') {
            $users->where('role', $selectedRole);
        }

        // البحث
        if ($request->has('search')) {
            $search = $request->search;
            $users->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%");
            });
        }

        $users = $users->latest()->paginate(10);

        return view('dashboard.users.index', compact(
            'users',
            'usersCount',
            'activeUsersCount',
            'adminsCount',
            'roles',
            'selectedRole'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,moderator,user,trader',
            'status' => 'required|in:active,inactive,banned',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
        ]);

        $userData = $request->except('password', 'avatar');
        $userData['password'] = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($userData);

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,moderator,user,trader',
            'status' => 'required|in:active,inactive,banned',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
        ]);

        $userData = $request->except('password', 'avatar');

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        // حذف الصورة إذا كانت موجودة
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function updateCoins(Request $request, User $user)
    {
        $request->validate([
            'coins' => 'required|integer|min:0',
            'operation' => 'required|in:add,subtract,set',
            'description' => 'nullable|string|max:255'
        ]);

        $currentCoins = $user->coins;
        $newCoins = $request->coins;
        $description = $request->description ?? 'تحديث رصيد العملات';

        DB::beginTransaction();
        try {
            switch ($request->operation) {
                case 'add':
                    $user->coins += $newCoins;
                    $message = "تم إضافة $newCoins عملة بنجاح. الرصيد الجديد: {$user->coins}";
                    $amount = $newCoins;
                    break;
                case 'subtract':
                    if ($currentCoins < $newCoins) {
                        return back()->with('error', 'لا يمكن خصم هذا المبلغ. الرصيد الحالي غير كافي.');
                    }
                    $user->coins -= $newCoins;
                    $message = "تم خصم $newCoins عملة بنجاح. الرصيد الجديد: {$user->coins}";
                    $amount = -$newCoins;
                    break;
                case 'set':
                    $amount = $newCoins - $currentCoins;
                    $user->coins = $newCoins;
                    $message = "تم تعيين الرصيد إلى $newCoins عملة بنجاح";
                    break;
            }

            $user->save();

            // تسجيل العملية في سجل التحويلات
            // $user->coinTransactions()->create([
            //     'amount' => $amount,
            //     'description' => $description,
            //     'admin_id' => auth()->id()
            // ]);

            DB::commit();

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء تحديث الرصيد: ' . $e->getMessage());
        }
    }
}
