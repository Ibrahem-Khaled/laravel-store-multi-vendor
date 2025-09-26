<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DriverOrderResource;
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use App\Services\Driver\OrderDistributionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    protected $orderDistributionService;

    public function __construct(OrderDistributionService $orderDistributionService)
    {
        $this->orderDistributionService = $orderDistributionService;
    }

    /**
     * Get driver dashboard
     */
    public function dashboard()
    {
        $driver = Auth::guard('api')->user()->driver;

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver profile not found'
            ], 404);
        }

        $stats = $this->orderDistributionService->getDriverStats($driver);

        $recentOrders = DriverOrder::where('driver_id', $driver->id)
            ->with(['order.user', 'order.userAddress'])
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'driver' => [
                    'id' => $driver->id,
                    'name' => $driver->user->name,
                    'phone' => $driver->phone_number,
                    'vehicle_type' => $driver->vehicle_type,
                    'vehicle_model' => $driver->vehicle_model,
                    'vehicle_plate' => $driver->vehicle_plate_number,
                    'city' => $driver->city,
                    'neighborhood' => $driver->neighborhood,
                    'is_available' => $driver->is_available,
                    'rating' => $driver->rating,
                ],
                'statistics' => $stats,
                'recent_orders' => DriverOrderResource::collection($recentOrders),
            ]
        ]);
    }

    /**
     * Get current orders for driver
     */
    public function currentOrders(Request $request)
    {
        $driver = Auth::guard('api')->user()->driver;

        $query = DriverOrder::where('driver_id', $driver->id)
            ->whereIn('status', ['assigned', 'accepted', 'picked_up'])
            ->with(['order.user', 'order.userAddress', 'order.items.product']);

        $orders = $query->orderBy('assigned_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => DriverOrderResource::collection($orders)
        ]);
    }

    /**
     * Get order history for driver
     */
    public function orderHistory(Request $request)
    {
        $driver = Auth::guard('api')->user()->driver;

        $query = DriverOrder::where('driver_id', $driver->id)
            ->with(['order.user', 'order.userAddress', 'order.items.product']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('assigned_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => DriverOrderResource::collection($orders)
        ]);
    }

    /**
     * Accept order
     */
    public function acceptOrder(Request $request, $orderId)
    {
        $driver = Auth::guard('api')->user()->driver;

        $driverOrder = DriverOrder::where('driver_id', $driver->id)
            ->where('order_id', $orderId)
            ->where('status', 'assigned')
            ->first();

        if (!$driverOrder) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found or already processed'
            ], 404);
        }

        $driverOrder->accept();

        return response()->json([
            'status' => true,
            'message' => 'Order accepted successfully',
            'data' => new DriverOrderResource($driverOrder)
        ]);
    }

    /**
     * Mark order as picked up
     */
    public function markAsPickedUp(Request $request, $orderId)
    {
        $driver = Auth::guard('api')->user()->driver;

        $driverOrder = DriverOrder::where('driver_id', $driver->id)
            ->where('order_id', $orderId)
            ->where('status', 'accepted')
            ->first();

        if (!$driverOrder) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found or not accepted'
            ], 404);
        }

        $driverOrder->markAsPickedUp();

        return response()->json([
            'status' => true,
            'message' => 'Order marked as picked up',
            'data' => new DriverOrderResource($driverOrder)
        ]);
    }

    /**
     * Mark order as delivered
     */
    public function markAsDelivered(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'delivery_notes' => 'nullable|string',
            'confirmation_image' => 'nullable|string', // Base64 encoded image
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::guard('api')->user()->driver;

        $driverOrder = DriverOrder::where('driver_id', $driver->id)
            ->where('order_id', $orderId)
            ->where('status', 'picked_up')
            ->first();

        if (!$driverOrder) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found or not picked up'
            ], 404);
        }

        $confirmationData = [
            'driver_confirmed' => true,
            'delivery_notes' => $request->delivery_notes,
            'confirmation_image' => $request->confirmation_image,
            'delivered_at' => now(),
        ];

        $driverOrder->markAsDelivered($confirmationData);

        return response()->json([
            'status' => true,
            'message' => 'Order marked as delivered. Waiting for customer confirmation.',
            'data' => new DriverOrderResource($driverOrder)
        ]);
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::guard('api')->user()->driver;

        $driverOrder = DriverOrder::where('driver_id', $driver->id)
            ->where('order_id', $orderId)
            ->whereIn('status', ['assigned', 'accepted'])
            ->first();

        if (!$driverOrder) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found or cannot be cancelled'
            ], 404);
        }

        $driverOrder->cancel($request->reason);

        return response()->json([
            'status' => true,
            'message' => 'Order cancelled successfully',
            'data' => new DriverOrderResource($driverOrder)
        ]);
    }

    /**
     * Update driver availability
     */
    public function updateAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_available' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::guard('api')->user()->driver;

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver profile not found'
            ], 404);
        }

        $driver->update([
            'is_available' => $request->is_available
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Availability updated successfully',
            'data' => [
                'is_available' => $driver->is_available
            ]
        ]);
    }

    /**
     * Update driver location
     */
    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::guard('api')->user()->driver;

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver profile not found'
            ], 404);
        }

        $driver->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Location updated successfully',
            'data' => [
                'latitude' => $driver->latitude,
                'longitude' => $driver->longitude
            ]
        ]);
    }

    /**
     * Get driver profile
     */
    public function profile()
    {
        $driver = Auth::guard('api')->user()->driver;

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver profile not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $driver->id,
                'user_id' => $driver->user_id,
                'license_number' => $driver->license_number,
                'vehicle_type' => $driver->vehicle_type,
                'vehicle_model' => $driver->vehicle_model,
                'vehicle_plate_number' => $driver->vehicle_plate_number,
                'phone_number' => $driver->phone_number,
                'city' => $driver->city,
                'neighborhood' => $driver->neighborhood,
                'latitude' => $driver->latitude,
                'longitude' => $driver->longitude,
                'is_available' => $driver->is_available,
                'is_active' => $driver->is_active,
                'is_supervisor' => $driver->is_supervisor,
                'current_orders_count' => $driver->current_orders_count,
                'rating' => $driver->rating,
                'total_deliveries' => $driver->total_deliveries,
                'working_hours' => $driver->working_hours,
                'service_areas' => $driver->service_areas,
                'created_at' => $driver->created_at,
                'updated_at' => $driver->updated_at,
            ]
        ]);
    }

    /**
     * Update driver profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_number' => 'nullable|string|max:255',
            'vehicle_type' => 'nullable|string|in:car,motorcycle,bicycle',
            'vehicle_model' => 'nullable|string|max:255',
            'vehicle_plate_number' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'working_hours' => 'nullable|array',
            'service_areas' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = Auth::guard('api')->user()->driver;

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver profile not found'
            ], 404);
        }

        $driver->update($request->only([
            'license_number',
            'vehicle_type',
            'vehicle_model',
            'vehicle_plate_number',
            'phone_number',
            'city',
            'neighborhood',
            'working_hours',
            'service_areas',
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $driver
        ]);
    }
}
