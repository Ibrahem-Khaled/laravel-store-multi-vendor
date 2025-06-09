<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->when(request('user_id'), function ($query) {
                $query->where('user_id', request('user_id'));
            })
            ->when(request('status'), function ($query) {
                $query->where('is_read', request('status') === 'read');
            })
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . request('search') . '%')
                        ->orWhere('body', 'like', '%' . request('search') . '%');
                });
            })
            ->latest()
            ->paginate(10);

        $users = User::all();

        $totalNotifications = Notification::count();
        $readNotifications = Notification::where('is_read', true)->count();
        $unreadNotifications = Notification::where('is_read', false)->count();
        $latestNotifications = Notification::where('created_at', '>=', now()->subDays(7))->count();

        return view('dashboard.notifications.index', compact(
            'notifications',
            'users',
            'totalNotifications',
            'readNotifications',
            'unreadNotifications',
            'latestNotifications'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Notification::create($request->all());

        return redirect()->route('notifications.index')
            ->with('success', 'تم إرسال الإشعار بنجاح');
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $notification->update($request->all());

        return redirect()->route('notifications.index')
            ->with('success', 'تم تحديث الإشعار بنجاح');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'تم حذف الإشعار بنجاح');
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return back()->with('success', 'تم تعليم الإشعار كمقروء');
    }

    public function markAsUnread(Notification $notification)
    {
        $notification->update(['is_read' => false]);

        return back()->with('success', 'تم تعليم الإشعار كغير مقروء');
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة');
    }
}
