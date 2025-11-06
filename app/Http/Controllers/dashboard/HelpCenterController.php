<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HelpCenterController extends Controller
{
    use AuthorizesRequests;

    /**
     * عرض صفحة مركز المساعدة الرئيسية
     */
    public function index()
    {
        $this->authorize('manage-tickets');

        // إحصائيات التذاكر
        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::pending()->count(),
            'open' => Ticket::open()->count(),
            'resolved' => Ticket::status('resolved')->count(),
            'closed' => Ticket::status('closed')->count(),
            'urgent' => Ticket::priority('urgent')->whereIn('status', ['pending', 'open', 'in_progress'])->count(),
        ];

        // التذاكر الأخيرة
        $recentTickets = Ticket::with(['user', 'category', 'responder'])
            ->latest()
            ->limit(10)
            ->get();

        // التذاكر حسب الفئة
        $ticketsByCategory = TicketCategory::withCount('tickets')
            ->get()
            ->map(function ($category) {
                return [
                    'category' => $category->name,
                    'count' => $category->tickets_count,
                    'pending' => $category->tickets()->pending()->count(),
                ];
            });

        return view('dashboard.help-center.index', compact('stats', 'recentTickets', 'ticketsByCategory'));
    }
}
