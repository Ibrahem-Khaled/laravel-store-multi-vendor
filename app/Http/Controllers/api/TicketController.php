<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * الحصول على فئات التذاكر
     */
    public function categories()
    {
        try {
            $categories = TicketCategory::active()
                ->ordered()
                ->get(['id', 'name', 'name_en', 'icon', 'description']);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب فئات التذاكر بنجاح',
                'data' => $categories,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب فئات التذاكر',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * إنشاء تذكرة جديدة
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|exists:ticket_categories,id',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|min:10',
                'priority' => 'nullable|in:low,medium,high,urgent',
                'attachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();
            $data['user_id'] = auth()->id();
            $data['priority'] = $data['priority'] ?? 'medium';
            $data['status'] = 'pending';

            // رفع المرفق إذا كان موجوداً
            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('tickets', 'public');
            }

            $ticket = Ticket::create($data);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء التذكرة بنجاح',
                'data' => [
                    'ticket_number' => $ticket->ticket_number,
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'status' => $ticket->status,
                    'priority' => $ticket->priority,
                    'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء التذكرة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على تذاكر المستخدم
     */
    public function index(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $category = $request->get('category_id', 'all');

            $query = Ticket::where('user_id', auth()->id())
                ->with(['category:id,name,icon', 'responder:id,name']);

            if ($status !== 'all' && in_array($status, ['pending', 'open', 'in_progress', 'resolved', 'closed'])) {
                $query->where('status', $status);
            }

            if ($category !== 'all') {
                $query->where('category_id', $category);
            }

            $tickets = $query->latest()->paginate(20);

            $formattedTickets = $tickets->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'subject' => $ticket->subject,
                    'message' => $ticket->message,
                    'status' => $ticket->status,
                    'status_label' => $ticket->status_label,
                    'priority' => $ticket->priority,
                    'priority_label' => $ticket->priority_label,
                    'category' => [
                        'id' => $ticket->category->id,
                        'name' => $ticket->category->name,
                        'icon' => $ticket->category->icon,
                    ],
                    'has_response' => $ticket->hasResponse(),
                    'has_attachment' => !is_null($ticket->attachment),
                    'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $ticket->updated_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب التذاكر بنجاح',
                'data' => $formattedTickets,
                'meta' => [
                    'current_page' => $tickets->currentPage(),
                    'last_page' => $tickets->lastPage(),
                    'per_page' => $tickets->perPage(),
                    'total' => $tickets->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب التذاكر',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على تذكرة محددة
     */
    public function show($id)
    {
        try {
            $ticket = Ticket::where('user_id', auth()->id())
                ->with(['category', 'responder'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب التذكرة بنجاح',
                'data' => [
                    'id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'subject' => $ticket->subject,
                    'message' => $ticket->message,
                    'status' => $ticket->status,
                    'status_label' => $ticket->status_label,
                    'priority' => $ticket->priority,
                    'priority_label' => $ticket->priority_label,
                    'category' => [
                        'id' => $ticket->category->id,
                        'name' => $ticket->category->name,
                        'icon' => $ticket->category->icon,
                    ],
                    'response' => $ticket->response,
                    'has_response' => $ticket->hasResponse(),
                    'responded_by' => $ticket->responder ? [
                        'id' => $ticket->responder->id,
                        'name' => $ticket->responder->name,
                    ] : null,
                    'responded_at' => $ticket->responded_at ? $ticket->responded_at->format('Y-m-d H:i:s') : null,
                    'attachment' => $ticket->attachment ? asset('storage/' . $ticket->attachment) : null,
                    'rating' => $ticket->rating,
                    'feedback' => $ticket->feedback,
                    'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $ticket->updated_at->format('Y-m-d H:i:s'),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'التذكرة غير موجودة أو غير متاحة',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * تقييم التذكرة
     */
    public function rate(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'feedback' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'البيانات غير صحيحة',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $ticket = Ticket::where('user_id', auth()->id())
                ->findOrFail($id);

            if (!$ticket->hasResponse()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن تقييم التذكرة قبل تلقي الرد',
                ], 400);
            }

            $ticket->update([
                'rating' => $request->rating,
                'feedback' => $request->feedback,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تقييم التذكرة بنجاح',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تقييم التذكرة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
