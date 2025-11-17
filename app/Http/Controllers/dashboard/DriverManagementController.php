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
        $stats = $this->getDashboardStats();
        $recentOrders = $this->getRecentOrders(10);
        $driversNeedingAttention = $this->getDriversNeedingAttention();
        $monthlyStats = $this->getMonthlyStatistics();
        $topDrivers = $this->getTopDrivers(5);

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
        $filters = $this->extractFilters($request);
        $query = $this->buildDriversQuery($filters);
        
        $drivers = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Get filter options
        $filterOptions = $this->getFilterOptions();

        return view('dashboard.driver-management.drivers', compact(
            'drivers',
            'filters',
            'filterOptions'
        ));
    }

    /**
     * Driver details
     */
    public function driverDetails($id)
    {
        $driver = Driver::with([
            'user', 
            'driverOrders.order.user', 
            'driverOrders.order.userAddress'
        ])->findOrFail($id);

        $stats = $this->orderDistributionService->getDriverStats($driver);
        $recentOrders = $driver->driverOrders()
            ->with(['order.user', 'order.userAddress'])
            ->latest()
            ->limit(10)
            ->get();
        $monthlyPerformance = $this->getDriverMonthlyPerformance($driver);
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
        $filters = $this->extractOrderFilters($request);
        $query = $this->buildOrdersQuery($filters);
        
        $orders = $query->orderBy('assigned_at', 'desc')->paginate(20)->withQueryString();

        // Get filter options
        $filterOptions = $this->getOrderFilterOptions();
        
        // Extract for backward compatibility with view
        $drivers = $filterOptions['drivers'];
        $cities = $filterOptions['cities'];
        $statuses = $filterOptions['statuses'];

        return view('dashboard.driver-management.orders', compact(
            'orders',
            'filters',
            'filterOptions',
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
        $validated = $request->validate([
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
            'is_supervisor' => 'nullable|boolean',
            'working_hours' => 'nullable|array',
            'service_areas' => 'nullable|string', // JSON string from hidden input
        ]);

        // Parse service_areas if it's a JSON string
        if (isset($validated['service_areas']) && is_string($validated['service_areas'])) {
            $parsed = json_decode($validated['service_areas'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                $validated['service_areas'] = $parsed;
            } else {
                $validated['service_areas'] = [];
            }
        }

        Driver::create($validated);

        return redirect()->route('admin.driver.drivers')
            ->with('success', 'تم إنشاء السواق بنجاح');
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

        $validated = $request->validate([
            'license_number' => 'required|string|max:255|unique:drivers,license_number,' . $id,
            'vehicle_type' => 'required|string|in:car,motorcycle,bicycle',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_plate_number' => 'required|string|max:255|unique:drivers,vehicle_plate_number,' . $id,
            'phone_number' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'nullable|boolean',
            'is_available' => 'nullable|boolean',
            'is_supervisor' => 'nullable|boolean',
            'working_hours' => 'nullable|array',
            'service_areas' => 'nullable|string', // JSON string from hidden input
        ]);

        // Parse service_areas if it's a JSON string
        if (isset($validated['service_areas']) && is_string($validated['service_areas'])) {
            $parsed = json_decode($validated['service_areas'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                $validated['service_areas'] = $parsed;
            } else {
                $validated['service_areas'] = [];
            }
        }

        $driver->update($validated);

        return redirect()->route('admin.driver.details', $id)
            ->with('success', 'تم تحديث بيانات السواق بنجاح');
    }

    /**
     * Assign order manually
     */
    public function assignOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $order = Order::findOrFail($validated['order_id']);
        $driver = Driver::findOrFail($validated['driver_id']);

        // Check if order is already assigned
        $existingAssignment = DriverOrder::where('order_id', $order->id)
            ->whereIn('status', ['assigned', 'accepted', 'picked_up'])
            ->first();

        if ($existingAssignment) {
            return back()->with('error', 'الطلب مُسند بالفعل لسواق آخر');
        }

        try {
            $this->orderDistributionService->manuallyAssignOrder(
                $order,
                $driver,
                Auth::id()
            );

            return back()->with('success', 'تم إسناد الطلب بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reassign order
     */
    public function reassignOrder(Request $request, $driverOrderId)
    {
        $validated = $request->validate([
            'new_driver_id' => 'required|exists:drivers,id',
        ]);

        $driverOrder = DriverOrder::findOrFail($driverOrderId);
        $newDriver = Driver::findOrFail($validated['new_driver_id']);

        try {
            $this->orderDistributionService->reassignOrder(
                $driverOrder,
                $newDriver,
                Auth::id()
            );

            return back()->with('success', 'تم إعادة إسناد الطلب بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Confirm delivery
     */
    public function confirmDelivery(Request $request, $driverOrderId)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $driverOrder = DriverOrder::findOrFail($driverOrderId);

        if ($driverOrder->status !== 'delivered') {
            return back()->with('error', 'الطلب لم يتم تسليمه بعد');
        }

        $confirmationData = $driverOrder->confirmation_data ?? [];
        $confirmationData['admin_confirmed'] = true;
        $confirmationData['admin_confirmed_by'] = Auth::id();
        $confirmationData['admin_confirmed_at'] = now();
        $confirmationData['admin_notes'] = $validated['notes'] ?? null;

        $driverOrder->update(['confirmation_data' => $confirmationData]);

        $confirmationStatus = $driverOrder->getConfirmationStatus();
        if ($confirmationStatus['is_fully_confirmed']) {
            $driverOrder->order->update(['status' => 'completed']);
        }

        return back()->with('success', 'تم تأكيد التسليم بنجاح');
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Request $request, $driverOrderId)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $driverOrder = DriverOrder::findOrFail($driverOrderId);

        if (!$driverOrder->canBeCancelled()) {
            return back()->with('error', 'لا يمكن إلغاء الطلب في هذه المرحلة');
        }

        $driverOrder->cancel($validated['reason']);

        return back()->with('success', 'تم إلغاء الطلب بنجاح');
    }

    /**
     * Destroy driver
     */
    public function destroyDriver($id)
    {
        $driver = Driver::findOrFail($id);

        if ($driver->activeOrders()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف سواق لديه طلبات نشطة');
        }

        $driver->delete();

        return redirect()->route('admin.driver.drivers')
            ->with('success', 'تم حذف السواق بنجاح');
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
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
    }

    /**
     * Get recent orders
     */
    private function getRecentOrders($limit = 10)
    {
        return DriverOrder::with(['order.user', 'driver.user', 'order.userAddress'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get drivers needing attention
     */
    private function getDriversNeedingAttention()
    {
        return Driver::where('is_active', true)
            ->where(function($query) {
                $query->where('current_orders_count', '>', 5)
                      ->orWhere('rating', '<', 3.0)
                      ->orWhere('is_available', false);
            })
            ->with('user')
            ->get();
    }

    /**
     * Get top performing drivers
     */
    private function getTopDrivers($limit = 5)
    {
        return Driver::with('user')
            ->orderBy('rating', 'desc')
            ->orderBy('total_deliveries', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Extract filters from request
     */
    private function extractFilters(Request $request)
    {
        return [
            'search' => $request->get('search'),
            'city' => $request->get('city'),
            'neighborhood' => $request->get('neighborhood'),
            'vehicle_type' => $request->get('vehicle_type'),
            'is_active' => $request->get('is_active'),
            'is_available' => $request->get('is_available'),
            'is_supervisor' => $request->get('is_supervisor'),
            'rating_min' => $request->get('rating_min'),
            'rating_max' => $request->get('rating_max'),
        ];
    }

    /**
     * Build drivers query with filters
     */
    private function buildDriversQuery(array $filters)
    {
        $query = Driver::with(['user', 'activeOrders']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('license_number', 'like', "%{$search}%")
              ->orWhere('vehicle_plate_number', 'like', "%{$search}%");
        }

        if (!empty($filters['city'])) {
            $query->inCity($filters['city']);
        }

        if (!empty($filters['neighborhood'])) {
            $query->inNeighborhood($filters['neighborhood']);
        }

        if (!empty($filters['vehicle_type'])) {
            $query->where('vehicle_type', $filters['vehicle_type']);
        }

        if ($filters['is_active'] !== null && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        if ($filters['is_available'] !== null && $filters['is_available'] !== '') {
            $query->where('is_available', $filters['is_available']);
        }

        if ($filters['is_supervisor'] !== null && $filters['is_supervisor'] !== '') {
            $query->where('is_supervisor', $filters['is_supervisor']);
        }

        if (!empty($filters['rating_min'])) {
            $query->where('rating', '>=', $filters['rating_min']);
        }

        if (!empty($filters['rating_max'])) {
            $query->where('rating', '<=', $filters['rating_max']);
        }

        return $query;
    }

    /**
     * Get filter options
     */
    private function getFilterOptions()
    {
        return [
            'cities' => Driver::distinct()->pluck('city')->filter()->sort()->values(),
            'neighborhoods' => Driver::distinct()->pluck('neighborhood')->filter()->sort()->values(),
            'vehicleTypes' => Driver::distinct()->pluck('vehicle_type')->filter()->sort()->values(),
        ];
    }

    /**
     * Extract order filters from request
     */
    private function extractOrderFilters(Request $request)
    {
        return [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'driver_id' => $request->get('driver_id'),
            'assignment_type' => $request->get('assignment_type'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'city' => $request->get('city'),
        ];
    }

    /**
     * Build orders query with filters
     */
    private function buildOrdersQuery(array $filters)
    {
        $query = DriverOrder::with([
            'order.user', 
            'driver.user', 
            'order.userAddress', 
            'order.items.product', 
            'assignedBy'
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('order', function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['driver_id'])) {
            $query->where('driver_id', $filters['driver_id']);
        }

        if (!empty($filters['assignment_type'])) {
            $query->where('assignment_type', $filters['assignment_type']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('assigned_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('assigned_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['city'])) {
            $query->whereHas('order.userAddress', function($q) use ($filters) {
                $q->where('city', $filters['city']);
            });
        }

        return $query;
    }

    /**
     * Get order filter options
     */
    private function getOrderFilterOptions()
    {
        return [
            'drivers' => Driver::with('user')->get(),
            'cities' => DriverOrder::join('orders', 'driver_orders.order_id', '=', 'orders.id')
                ->join('user_addresses', 'orders.user_address_id', '=', 'user_addresses.id')
                ->distinct()->pluck('user_addresses.city')->filter()->sort()->values(),
            'statuses' => ['assigned', 'accepted', 'picked_up', 'delivered', 'cancelled'],
        ];
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
                    ->join('orders', 'driver_orders.order_id', '=', 'orders.id')
                    ->avg('orders.driver_rating') ?? 0,
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
}
