<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\ShippingProof;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingProofController extends Controller
{
    /**
     * عرض قائمة طلبات الشحن
     */
    public function index(Request $request)
    {
        $status = $request->input('status'); // pending, approved, rejected
        $userId = $request->input('user_id');

        // إحصائيات
        $pendingCount = ShippingProof::where('status', 'pending')->count();
        $approvedCount = ShippingProof::where('status', 'approved')->count();
        $rejectedCount = ShippingProof::where('status', 'rejected')->count();
        $totalCount = ShippingProof::count();
        $totalCoinsAdded = ShippingProof::where('status', 'approved')->sum('coins_added') ?? 0;

        $query = ShippingProof::with(['user:id,name,username,email', 'admin:id,name,username'])
            ->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $proofs = $query->paginate(15);

        return view('dashboard.shipping-proofs.index', compact(
            'proofs',
            'status',
            'userId',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'totalCount',
            'totalCoinsAdded'
        ));
    }

    /**
     * عرض تفاصيل طلب شحن معين
     */
    public function show($id)
    {
        $proof = ShippingProof::with(['user', 'admin'])
            ->findOrFail($id);

        return view('dashboard.shipping-proofs.show', compact('proof'));
    }

    /**
     * الموافقة على طلب شحن
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'coins_amount' => 'required|integer|min:1',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $proof = ShippingProof::findOrFail($id);

        if ($proof->status !== 'pending') {
            return back()->with('error', 'لا يمكن الموافقة على طلب تم معالجته مسبقاً');
        }

        DB::beginTransaction();

        try {
            // تحديث حالة الطلب
            $proof->update([
                'status' => 'approved',
                'admin_id' => auth()->id(),
                'admin_notes' => $request->input('admin_notes'),
                'coins_added' => $request->input('coins_amount'),
                'approved_at' => now(),
            ]);

            // إضافة العملات إلى حساب المستخدم
            $user = User::findOrFail($proof->user_id);
            $user->increment('coins', $request->input('coins_amount'));

            DB::commit();

            return back()->with('success', 'تم الموافقة على الطلب وإضافة العملات بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء الموافقة على الطلب: ' . $e->getMessage());
        }
    }

    /**
     * رفض طلب شحن
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $proof = ShippingProof::findOrFail($id);

        if ($proof->status !== 'pending') {
            return back()->with('error', 'لا يمكن رفض طلب تم معالجته مسبقاً');
        }

        $proof->update([
            'status' => 'rejected',
            'admin_id' => auth()->id(),
            'admin_notes' => $request->input('admin_notes'),
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'تم رفض الطلب بنجاح');
    }
}
