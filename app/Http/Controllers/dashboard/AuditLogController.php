<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    /**
     * عرض قائمة سجلات التدقيق
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        // التصفية حسب المستخدم
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // التصفية حسب العملية
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // التصفية حسب النموذج
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        // التصفية حسب التاريخ من
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // التصفية حسب التاريخ إلى
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $auditLogs = $query->paginate(20)->withQueryString();

        // إحصائيات
        $stats = [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', today())->count(),
            'this_week' => AuditLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => AuditLog::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // إحصائيات حسب العملية
        $actionsStats = AuditLog::select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->pluck('count', 'action');

        // إحصائيات حسب النموذج
        $modelsStats = AuditLog::select('auditable_type', DB::raw('count(*) as count'))
            ->groupBy('auditable_type')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->mapWithKeys(function ($item) {
                return [class_basename($item->auditable_type) => $item->count];
            });

        // قائمة المستخدمين للفلترة
        $users = User::whereHas('auditLogs')->orderBy('name')->get(['id', 'name', 'email']);

        // قائمة النماذج المتاحة
        $auditableTypes = AuditLog::distinct('auditable_type')
            ->pluck('auditable_type')
            ->mapWithKeys(function ($type) {
                return [$type => class_basename($type)];
            });

        $actions = ['created' => 'إنشاء', 'updated' => 'تعديل', 'deleted' => 'حذف', 'restored' => 'استعادة'];

        return view('dashboard.audit-logs.index', compact(
            'auditLogs',
            'stats',
            'actionsStats',
            'modelsStats',
            'users',
            'auditableTypes',
            'actions'
        ));
    }

    /**
     * عرض تفاصيل سجل تدقيق معين
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load(['user', 'auditable']);

        return view('dashboard.audit-logs.show', compact('auditLog'));
    }
}

