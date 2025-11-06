<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TicketController extends Controller
{
    use AuthorizesRequests;

    /**
     * عرض قائمة التذاكر
     */
    public function index(Request $request)
    {
        $this->authorize('manage-tickets');

        $status = $request->get('status', 'all');
        $priority = $request->get('priority', 'all');
        $category = $request->get('category', 'all');
        $search = $request->get('search');

        $query = Ticket::with(['user', 'category', 'responder']);

        // فلترة حسب الحالة
        if ($status !== 'all' && in_array($status, ['pending', 'open', 'in_progress', 'resolved', 'closed'])) {
            $query->where('status', $status);
        }

        // فلترة حسب الأولوية
        if ($priority !== 'all' && in_array($priority, ['low', 'medium', 'high', 'urgent'])) {
            $query->where('priority', $priority);
        }

        // فلترة حسب الفئة
        if ($category !== 'all') {
            $query->where('category_id', $category);
        }

        // البحث
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->latest()->paginate(20)->withQueryString();

        $categories = TicketCategory::active()->ordered()->get();

        // إحصائيات
        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::pending()->count(),
            'open' => Ticket::open()->count(),
            'resolved' => Ticket::status('resolved')->count(),
            'closed' => Ticket::status('closed')->count(),
        ];

        return view('dashboard.tickets.index', compact(
            'tickets',
            'categories',
            'status',
            'priority',
            'category',
            'search',
            'stats'
        ));
    }

    /**
     * عرض تفاصيل تذكرة
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('manage-tickets');

        $ticket->load(['user', 'category', 'responder']);

        return view('dashboard.tickets.show', compact('ticket'));
    }

    /**
     * الرد على تذكرة
     */
    public function respond(Request $request, Ticket $ticket)
    {
        $this->authorize('manage-tickets');

        $request->validate([
            'response' => 'required|string|min:10',
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->markAsResponded(auth()->id(), $request->response);

        if ($request->status === 'resolved') {
            $ticket->resolve();
        } elseif ($request->status === 'closed') {
            $ticket->close();
        } else {
            $ticket->update(['status' => $request->status]);
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'تم إرسال الرد بنجاح.');
    }

    /**
     * تحديث حالة التذكرة
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->authorize('manage-tickets');

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        if ($request->status === 'resolved') {
            $ticket->resolve();
        } elseif ($request->status === 'closed') {
            $ticket->close();
        } else {
            $ticket->update(['status' => $request->status]);
        }

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'تم تحديث حالة التذكرة بنجاح.');
    }

    /**
     * تحديث أولوية التذكرة
     */
    public function updatePriority(Request $request, Ticket $ticket)
    {
        $this->authorize('manage-tickets');

        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket->update(['priority' => $request->priority]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'تم تحديث أولوية التذكرة بنجاح.');
    }

    /**
     * حذف تذكرة
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('manage-tickets');

        if ($ticket->attachment) {
            Storage::disk('public')->delete($ticket->attachment);
        }

        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'تم حذف التذكرة بنجاح.');
    }

    /**
     * تحميل المرفق
     */
    public function downloadAttachment(Ticket $ticket)
    {
        $this->authorize('manage-tickets');

        if (!$ticket->attachment || !Storage::disk('public')->exists($ticket->attachment)) {
            abort(404);
        }

        return Storage::disk('public')->download($ticket->attachment);
    }
}
