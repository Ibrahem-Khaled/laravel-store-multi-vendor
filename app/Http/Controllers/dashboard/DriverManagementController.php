<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use App\Models\User;
use App\Services\Driver\OrderDistributionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DriverManagementController extends Controller
{
    protected $orderDistributionService;

    public function __construct(OrderDistributionService $orderDistributionService)
    {
        $this->orderDistributionService = $orderDistributionService;
    }

    /**
     * Dashboard overview
     */
    public function dashboard()
    {
        $stats = [
            'total_drivers' => Driver::count(),
            'active_drivers' => Driver::where('is_active', true)->count(),
            'available_drivers' => Driver::available()->count(),
            'supervisor_drivers' => Driver::supervisors()->count(),
            'total_orders' => DriverOrder::count(),
            'pending_orders' => DriverOrder::where('status', 'assigned')->count(),
            'in_progress_orders' => DriverOrder::whereIn('status', ['accepted', 'picked_up'])->count(),
            'completed_orders' => DriverOrder::where('status', 'delivered')->count(),
            'cancelled_orders' => DriverOrder::where('status', 'cancelled')->count(),
            'today_orders' => DriverOrder::whereDate('assigned_at', today())->count(),
            'monthly_orders' => DriverOrder::whereMonth('assigned_at', now()->month)->count(),
        ];

        // Recent orders
        $recentOrders = DriverOrder::with(['order.user', 'driver.user', 'order.userAddress'])
            ->latest()
            ->limit(10)
            ->get();

        // Drivers needing attention
        $driversNeedingAttention = Driver::where('is_active', true)
            ->where(function($query) {
                $query->where('current_orders_count', '>', 5)
                      ->orWhere('rating', '<', 3.0)
                      ->orWhere('is_available', false);
            })
            ->with('user')
            ->get();

        // Monthly statistics for chart
        $monthlyStats = $this->getMonthlyStatistics();

        // Top performing drivers
        $topDrivers = Driver::with('user')
            ->orderBy('rating', 'desc')
            ->orderBy('total_deliveries', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.driver-management.dashboard', compact(
            'stats',
            'recentOrders',
            'driversNeedingAttention',
            'monthlyStats',
            'topDrivers'
        ));
    }

    /**
     * Drivers list with filters
     */
    public function drivers(Request $request)
    {
        $query = Driver::with(['user', 'activeOrders']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('license_number', 'like', "%{$search}%")
              ->orWhere('vehicle_plate_number', 'like', "%{$search}%");
        }

        if ($request->filled('city')) {
            $query->inCity($request->city);
        }

        if ($request->filled('neighborhood')) {
            $query->inNeighborhood($request->neighborhood);
        }

        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('is_available')) {
            $query->where('is_available', $request->is_available);
        }

        if ($request->filled('is_supervisor')) {
            $query->where('is_supervisor', $request->is_supervisor);
        }

        if ($request->filled('rating_min')) {
            $query->where('rating', '>=', $request->rating_min);
        }

        if ($request->filled('rating_max')) {
            $query->where('rating', '<=', $request->rating_max);
        }

        $drivers = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter options
        $cities = Driver::distinct()->pluck('city')->filter();
        $neighborhoods = Driver::distinct()->pluck('neighborhood')->filter();
        $vehicleTypes = Driver::distinct()->pluck('vehicle_type')->filter();

        return view('dashboard.driver-management.drivers', compact(
            'drivers',
            'cities',
            'neighborhoods',
            'vehicleTypes'
        ));
    }

    /**
     * Driver details
     */
    public function driverDetails($id)
    {
        $driver = Driver::with(['user', 'driverOrders.order.user', 'driverOrders.order.userAddress'])
            ->findOrFail($id);

        $stats = $this->orderDistributionService->getDriverStats($driver);

        // Recent orders
        $recentOrders = $driver->driverOrders()
            ->with(['order.user', 'order.userAddress'])
            ->latest()
            ->limit(10)
            ->get();

        // Monthly performance
        $monthlyPerformance = $this->getDriverMonthlyPerformance($driver);

        // Rating history
        $ratingHistory = $this->getDriverRatingHistory($driver);

        return view('dashboard.driver-management.driver-details', compact(
            'driver',
            'stats',
            'recentOrders',
            'monthlyPerformance',
            'ratingHistory'
        ));
    }

    /**
     * Orders management
     */
    public function orders(Request $request)
    {
        $query = DriverOrder::with(['order.user', 'driver.user', 'order.userAddress', 'order.items.product', 'assignedBy']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('order', function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('assignment_type')) {
            $query->where('assignment_type', $request->assignment_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('assigned_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('assigned_at', '<=', $request->date_to);
        }

        if ($request->filled('city')) {
            $query->whereHas('order.userAddress', function($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        $orders = $query->orderBy('assigned_at', 'desc')->paginate(20);

        // Get filter options
        $drivers = Driver::with('user')->get();
        $cities = DriverOrder::join('orders', 'driver_orders.order_id', '=', 'orders.id')
            ->join('user_addresses', 'orders.user_address_id', '=', 'user_addresses.id')
            ->distinct()->pluck('user_addresses.city')->filter();
        $statuses = ['assigned', 'accepted', 'picked_up', 'delivered', 'cancelled'];

        return view('dashboard.driver-management.orders', compact(
            'orders',
            'drivers',
            'cities',
            'statuses'
        ));
    }

    /**
     * Order details
     */
    public function orderDetails($id)
    {
        $driverOrder = DriverOrder::with([
            'order.user',
            'order.userAddress',
            'order.items.product',
            'driver.user',
            'assignedBy'
        ])->findOrFail($id);

        $confirmationStatus = $driverOrder->getConfirmationStatus();

        return view('dashboard.driver-management.order-details', compact(
            'driverOrder',
            'confirmationStatus'
        ));
    }

    /**
     * Create new driver
     */
    public function createDriver()
    {
        $users = User::whereDoesntHave('driver')->get();
        return view('dashboard.driver-management.create-driver', compact('users'));
    }

    /**
     * Store new driver
     */
    public function storeDriver(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:drivers,user_id',
            'license_number' => 'required|string|max:255|unique:drivers,license_number',
            'vehicle_type' => 'required|string|in:car,motorcycle,bicycle',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_plate_number' => 'required|string|max:255|unique:drivers,vehicle_plate_number',
            'phone_number' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_supervisor' => 'boolean',
            'working_hours' => 'nullable|array',
            'service_areas' => 'nullable|array',
        ]);

        Driver::create($request->all());

        return redirect()->route('admin.drivers')
            ->with('success', 'Driver created successfully');
    }

    /**
     * Edit driver
     */
    public function editDriver($id)
    {
        $driver = Driver::with('user')->findOrFail($id);
        return view('dashboard.driver-management.edit-driver', compact('driver'));
    }

    /**
     * Update driver
     */
    public function updateDriver(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'license_number' => 'required|string|max:255|unique:drivers,license_number,' . $id,
            'vehicle_type' => 'required|string|in:car,motorcycle,bicycle',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_plate_number' => 'required|string|max:255|unique:drivers,vehicle_plate_number,' . $id,
            'phone_number' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'is_supervisor' => 'boolean',
            'working_hours' => 'nullable|array',
            'service_areas' => 'nullable|array',
        ]);

        $driver->update($request->all());

        return redirect()->route('admin.driver.details', $id)
            ->with('success', 'Driver updated successfully');
    }

    /**
     * Assign order manually
     */
    public function assignOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $order = Order::find($request->order_id);
        $driver = Driver::find($request->driver_id);

        // Check if order is already assigned
        $existingAssignment = DriverOrder::where('order_id', $order->id)
            ->whereIn('status', ['assigned', 'accepted', 'picked_up'])
            ->first();

        if ($existingAssignment) {
            return back()->with('error', 'Order is already assigned to another driver');
        }

        try {
            $this->orderDistributionService->manuallyAssignOrder(
                $order,
                $driver,
                Auth::user()?->id ?? 1
            );

            return back()->with('success', 'Order assigned successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reassign order
     */
    public function reassignOrder(Request $request, $driverOrderId)
    {
        $request->validate([
            'new_driver_id' => 'required|exists:drivers,id',
        ]);

        $driverOrder = DriverOrder::findOrFail($driverOrderId);
        $newDriver = Driver::find($request->new_driver_id);

        try {
            $this->orderDistributionService->reassignOrder(
                $driverOrder,
                $newDriver,
                Auth::user()?->id ?? 1
            );

            return back()->with('success', 'Order reassigned successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Confirm delivery
     */
    public function confirmDelivery(Request $request, $driverOrderId)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $driverOrder = DriverOrder::findOrFail($driverOrderId);

        if ($driverOrder->status !== 'delivered') {
            return back()->with('error', 'Order is not marked as delivered by driver');
        }

        // Update confirmation data
        $confirmationData = $driverOrder->confirmation_data ?? [];
        $confirmationData['admin_confirmed'] = true;
        $confirmationData['admin_confirmed_by'] = Auth::user()?->id ?? 1;
        $confirmationData['admin_confirmed_at'] = now();
        $confirmationData['admin_notes'] = $request->notes;

        $driverOrder->update([
            'confirmation_data' => $confirmationData
        ]);

        // Update order status to completed if fully confirmed
        $confirmationStatus = $driverOrder->getConfirmationStatus();
        if ($confirmationStatus['is_fully_confirmed']) {
            $driverOrder->order->update(['status' => 'completed']);
        }

        return back()->with('success', 'Delivery confirmed successfully');
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Request $request, $driverOrderId)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $driverOrder = DriverOrder::findOrFail($driverOrderId);

        if (!$driverOrder->canBeCancelled()) {
            return back()->with('error', 'Order cannot be cancelled at this stage');
        }

        $driverOrder->cancel($request->reason);

        return back()->with('success', 'Order cancelled successfully');
    }

    /**
     * Get monthly statistics
     */
    private function getMonthlyStatistics()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $month->format('Y-m'),
                'month_name' => $month->format('M Y'),
                'orders_count' => DriverOrder::whereMonth('assigned_at', $month->month)
                    ->whereYear('assigned_at', $month->year)
                    ->count(),
                'completed_count' => DriverOrder::whereMonth('assigned_at', $month->month)
                    ->whereYear('assigned_at', $month->year)
                    ->where('status', 'delivered')
                    ->count(),
                'cancelled_count' => DriverOrder::whereMonth('assigned_at', $month->month)
                    ->whereYear('assigned_at', $month->year)
                    ->where('status', 'cancelled')
                    ->count(),
            ];
        }
        return $months;
    }

    /**
     * Get driver monthly performance
     */
    private function getDriverMonthlyPerformance(Driver $driver)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = [
                'month' => $month->format('Y-m'),
                'month_name' => $month->format('M Y'),
                'orders_count' => $driver->driverOrders()
                    ->whereMonth('assigned_at', $month->month)
                    ->whereYear('assigned_at', $month->year)
                    ->count(),
                'completed_count' => $driver->driverOrders()
                    ->whereMonth('assigned_at', $month->month)
                    ->whereYear('assigned_at', $month->year)
                    ->where('status', 'delivered')
                    ->count(),
                'rating' => $driver->driverOrders()
                    ->whereMonth('assigned_at', $month->month)
                    ->whereYear('assigned_at', $month->year)
                    ->where('status', 'delivered')
                    ->avg('order.driver_rating') ?? 0,
            ];
        }
        return $months;
    }

    /**
     * Get driver rating history
     */
    private function getDriverRatingHistory(Driver $driver)
    {
        return $driver->driverOrders()
            ->where('status', 'delivered')
            ->whereHas('order', function($query) {
                $query->whereNotNull('driver_rating');
            })
            ->with('order')
            ->orderBy('delivered_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($order) {
                return [
                    'date' => $order->delivered_at,
                    'rating' => $order->order->driver_rating,
                    'order_id' => $order->order_id,
                ];
            });
    }

    /**
     * Destroy driver
     */
    public function destroyDriver($id)
    {
        $driver = Driver::findOrFail($id);

        // Check if driver has active orders
        if ($driver->currentOrders()->count() > 0) {
            return back()->with('error', 'Cannot delete driver with active orders');
        }

        $driver->delete();

        return redirect()->route('admin.drivers')
            ->with('success', 'Driver deleted successfully');
    }
}
