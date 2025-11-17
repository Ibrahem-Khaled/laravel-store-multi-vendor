<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReturnController extends Controller
{
    /**
     * قائمة مرتجعات العميل
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        $query = OrderReturn::where('user_id', $user->id)
            ->with(['order', 'orderItem.product', 'replacementOrder']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => $returns->map(function($return) {
                return [
                    'id' => $return->id,
                    'order_id' => $return->order_id,
                    'order_item_id' => $return->order_item_id,
                    'type' => $return->type,
                    'status' => $return->status,
                    'reason' => $return->reason,
                    'customer_notes' => $return->customer_notes,
                    'admin_notes' => $return->admin_notes,
                    'refund_amount' => $return->refund_amount,
                    'refund_method' => $return->refund_method,
                    'images' => $return->images,
                    'order' => [
                        'id' => $return->order->id,
                        'grand_total' => $return->order->grand_total,
                        'status' => $return->order->status,
                    ],
                    'order_item' => $return->orderItem ? [
                        'id' => $return->orderItem->id,
                        'product' => [
                            'id' => $return->orderItem->product->id,
                            'name' => $return->orderItem->product->name,
                        ],
                        'quantity' => $return->orderItem->quantity,
                        'unit_price' => $return->orderItem->unit_price,
                    ] : null,
                    'replacement_order' => $return->replacementOrder ? [
                        'id' => $return->replacementOrder->id,
                        'status' => $return->replacementOrder->status,
                    ] : null,
                    'created_at' => $return->created_at,
                    'updated_at' => $return->updated_at,
                    'processed_at' => $return->processed_at,
                    'approved_at' => $return->approved_at,
                    'rejected_at' => $return->rejected_at,
                    'completed_at' => $return->completed_at,
                ];
            }),
            'pagination' => [
                'current_page' => $returns->currentPage(),
                'last_page' => $returns->lastPage(),
                'per_page' => $returns->perPage(),
                'total' => $returns->total(),
            ]
        ]);
    }

    /**
     * تفاصيل مرتجع
     */
    public function show($id)
    {
        $user = Auth::guard('api')->user();

        $return = OrderReturn::where('user_id', $user->id)
            ->with(['order.items.product', 'orderItem.product', 'replacementOrder'])
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $return->id,
                'order_id' => $return->order_id,
                'order_item_id' => $return->order_item_id,
                'type' => $return->type,
                'status' => $return->status,
                'reason' => $return->reason,
                'customer_notes' => $return->customer_notes,
                'admin_notes' => $return->admin_notes,
                'refund_amount' => $return->refund_amount,
                'refund_method' => $return->refund_method,
                'images' => $return->images,
                'order' => [
                    'id' => $return->order->id,
                    'grand_total' => $return->order->grand_total,
                    'status' => $return->order->status,
                    'items' => $return->order->items->map(function($item) {
                        return [
                            'id' => $item->id,
                            'product' => [
                                'id' => $item->product->id,
                                'name' => $item->product->name,
                            ],
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                        ];
                    }),
                ],
                'order_item' => $return->orderItem ? [
                    'id' => $return->orderItem->id,
                    'product' => [
                        'id' => $return->orderItem->product->id,
                        'name' => $return->orderItem->product->name,
                    ],
                    'quantity' => $return->orderItem->quantity,
                    'unit_price' => $return->orderItem->unit_price,
                ] : null,
                'replacement_order' => $return->replacementOrder ? [
                    'id' => $return->replacementOrder->id,
                    'status' => $return->replacementOrder->status,
                ] : null,
                'created_at' => $return->created_at,
                'updated_at' => $return->updated_at,
                'processed_at' => $return->processed_at,
                'approved_at' => $return->approved_at,
                'rejected_at' => $return->rejected_at,
                'completed_at' => $return->completed_at,
            ]
        ]);
    }

    /**
     * إنشاء طلب إرجاع
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'nullable|exists:order_items,id',
            'type' => 'required|in:return,refund,replacement',
            'reason' => 'required|string|max:1000',
            'customer_notes' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'order_id.required' => 'رقم الطلب مطلوب',
            'order_id.exists' => 'الطلب غير موجود',
            'order_item_id.exists' => 'عنصر الطلب غير موجود',
            'type.required' => 'نوع الطلب مطلوب',
            'type.in' => 'نوع الطلب غير صحيح',
            'reason.required' => 'سبب الإرجاع مطلوب',
            'images.max' => 'يمكن رفع 5 صور كحد أقصى',
        ]);

        // التحقق من أن الطلب ملك للعميل
        $order = Order::where('user_id', $user->id)->findOrFail($request->order_id);

        // التحقق من أن الطلب مكتمل أو تم شحنه
        if (!in_array($order->status, ['completed', 'shipped'])) {
            return response()->json([
                'status' => false,
                'message' => 'يمكن إرجاع الطلبات المكتملة أو المشحونة فقط'
            ], 400);
        }

        // التحقق من أن عنصر الطلب موجود في الطلب
        if ($request->order_item_id) {
            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('id', $request->order_item_id)
                ->firstOrFail();
        }

        // التحقق من عدم وجود مرتجع معلق لنفس الطلب/العنصر
        $existingReturn = OrderReturn::where('order_id', $order->id)
            ->where('order_item_id', $request->order_item_id)
            ->whereIn('status', ['pending', 'approved', 'processing'])
            ->first();

        if ($existingReturn) {
            return response()->json([
                'status' => false,
                'message' => 'يوجد طلب إرجاع معلق بالفعل لهذا العنصر'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // رفع الصور
            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('returns', 'public');
                    $images[] = $path;
                }
            }

            $return = OrderReturn::create([
                'order_id' => $order->id,
                'order_item_id' => $request->order_item_id,
                'user_id' => $user->id,
                'type' => $request->type,
                'status' => 'pending',
                'reason' => $request->reason,
                'customer_notes' => $request->customer_notes,
                'images' => $images,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'تم إنشاء طلب الإرجاع بنجاح',
                'data' => [
                    'id' => $return->id,
                    'order_id' => $return->order_id,
                    'status' => $return->status,
                    'type' => $return->type,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء إنشاء طلب الإرجاع: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إلغاء طلب الإرجاع
     */
    public function cancel($id)
    {
        $user = Auth::guard('api')->user();

        $return = OrderReturn::where('user_id', $user->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $return->cancel();

        return response()->json([
            'status' => true,
            'message' => 'تم إلغاء طلب الإرجاع بنجاح'
        ]);
    }
}
