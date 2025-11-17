<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DriverOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * قائمة طلبات العميل
     */
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();

        $query = Order::where('user_id', $user->id)
            ->with(['items.product', 'userAddress', 'driverOrder.driver.user', 'returns']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                    'subtotal' => $order->subtotal,
                    'shipping_total' => $order->shipping_total,
                    'discount_total' => $order->discount_total,
                    'grand_total' => $order->grand_total,
                    'items_count' => $order->items->count(),
                    'items' => $order->items->map(function($item) {
                        return [
                            'id' => $item->id,
                            'product' => [
                                'id' => $item->product->id,
                                'name' => $item->product->name,
                                'image' => $item->product->cover ?? null,
                            ],
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total' => $item->quantity * $item->unit_price,
                        ];
                    }),
                    'address' => $order->userAddress ? [
                        'city' => $order->userAddress->city,
                        'neighborhood' => $order->userAddress->neighborhood,
                        'address' => $order->userAddress->address,
                    ] : null,
                    'driver' => $order->driverOrder && $order->driverOrder->driver ? [
                        'id' => $order->driverOrder->driver->id,
                        'name' => $order->driverOrder->driver->user->name,
                        'phone' => $order->driverOrder->driver->phone_number,
                        'vehicle_type' => $order->driverOrder->driver->vehicle_type,
                        'status' => $order->driverOrder->status,
                    ] : null,
                    'has_returns' => $order->returns->count() > 0,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ];
            }),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    /**
     * تفاصيل الطلب
     */
    public function show($id)
    {
        $user = Auth::guard('api')->user();

        $order = Order::where('user_id', $user->id)
            ->with(['items.product', 'userAddress', 'driverOrder.driver.user', 'returns'])
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $order->id,
                'status' => $order->status,
                'payment_method' => $order->payment_method,
                'subtotal' => $order->subtotal,
                'shipping_total' => $order->shipping_total,
                'discount_total' => $order->discount_total,
                'grand_total' => $order->grand_total,
                'items' => $order->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'image' => $item->product->cover ?? null,
                        ],
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total' => $item->quantity * $item->unit_price,
                    ];
                }),
                'address' => $order->userAddress ? [
                    'city' => $order->userAddress->city,
                    'neighborhood' => $order->userAddress->neighborhood,
                    'address' => $order->userAddress->address,
                ] : null,
                'driver' => $order->driverOrder && $order->driverOrder->driver ? [
                    'id' => $order->driverOrder->driver->id,
                    'name' => $order->driverOrder->driver->user->name,
                    'phone' => $order->driverOrder->driver->phone_number,
                    'vehicle_type' => $order->driverOrder->driver->vehicle_type,
                    'vehicle_plate' => $order->driverOrder->driver->vehicle_plate_number,
                    'status' => $order->driverOrder->status,
                    'assigned_at' => $order->driverOrder->assigned_at,
                    'picked_up_at' => $order->driverOrder->picked_up_at,
                    'delivered_at' => $order->driverOrder->delivered_at,
                ] : null,
                'returns' => $order->returns->map(function($return) {
                    return [
                        'id' => $return->id,
                        'type' => $return->type,
                        'status' => $return->status,
                        'reason' => $return->reason,
                    ];
                }),
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]
        ]);
    }

    /**
     * تتبع الطلب
     */
    public function track($id)
    {
        $user = Auth::guard('api')->user();

        $order = Order::where('user_id', $user->id)
            ->with(['driverOrder.driver.user', 'items.product'])
            ->findOrFail($id);

        $driverOrder = $order->driverOrder;

        $tracking = [
            'order_id' => $order->id,
            'order_status' => $order->status,
            'current_status' => $driverOrder ? $driverOrder->status : 'not_assigned',
            'timeline' => [],
        ];

        if ($driverOrder) {
            $timeline = [];
            
            if ($driverOrder->assigned_at) {
                $timeline[] = [
                    'status' => 'assigned',
                    'title' => 'تم تعيين السائق',
                    'description' => 'تم تعيين سائق للطلب',
                    'date' => $driverOrder->assigned_at,
                ];
            }

            if ($driverOrder->accepted_at) {
                $timeline[] = [
                    'status' => 'accepted',
                    'title' => 'قبل السائق الطلب',
                    'description' => 'قبل السائق الطلب وهو في الطريق',
                    'date' => $driverOrder->accepted_at,
                ];
            }

            if ($driverOrder->picked_up_at) {
                $timeline[] = [
                    'status' => 'picked_up',
                    'title' => 'تم استلام الطلب',
                    'description' => 'استلم السائق الطلب وهو في الطريق إليك',
                    'date' => $driverOrder->picked_up_at,
                ];
            }

            if ($driverOrder->delivered_at) {
                $timeline[] = [
                    'status' => 'delivered',
                    'title' => 'تم التسليم',
                    'description' => 'تم تسليم الطلب بنجاح',
                    'date' => $driverOrder->delivered_at,
                ];
            }

            $tracking['timeline'] = $timeline;
            $tracking['driver'] = [
                'id' => $driverOrder->driver->id,
                'name' => $driverOrder->driver->user->name,
                'phone' => $driverOrder->driver->phone_number,
                'vehicle_type' => $driverOrder->driver->vehicle_type,
            ];
        } else {
            $tracking['timeline'][] = [
                'status' => 'pending',
                'title' => 'في انتظار التعيين',
                'description' => 'الطلب في انتظار تعيين سائق',
                'date' => $order->created_at,
            ];
        }

        return response()->json([
            'status' => true,
            'data' => $tracking
        ]);
    }

    /**
     * تأكيد استلام الطلب
     */
    public function confirmReceipt(Request $request, $id)
    {
        $user = Auth::guard('api')->user();

        $order = Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->findOrFail($id);

        // التحقق من أن الطلب تم تسليمه
        $driverOrder = $order->driverOrder;
        if (!$driverOrder || $driverOrder->status !== 'delivered') {
            return response()->json([
                'status' => false,
                'message' => 'الطلب لم يتم تسليمه بعد'
            ], 400);
        }

        // تحديث حالة الطلب
        $order->update(['status' => 'completed']);

        return response()->json([
            'status' => true,
            'message' => 'تم تأكيد استلام الطلب بنجاح',
            'data' => [
                'order_id' => $order->id,
                'status' => $order->status,
            ]
        ]);
    }
}
