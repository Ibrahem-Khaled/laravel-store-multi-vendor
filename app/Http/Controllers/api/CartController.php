<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->guard('api')->user();

        // نستخدم firstOrCreate لإنشاء سلة تلقائيًا إذا لم تكن موجودة لتجنب الأخطاء
        $cart = $user->carts()->firstOrCreate(
            ['status' => 'pending'], // الشروط
            ['payment_method' => 'cash_on_delivery'] // القيم عند الإنشاء
        )->load('items.product.store'); // تحميل العلاقات اللازمة

        return response()->json($cart);
    }


    public function store(Request $request)
    {
        // 1. التحقق من صحة المدخلات
        $request->validate([
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')],
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->guard('api')->user();
        $product = Product::findOrFail($request->product_id);

        // 2. التحقق من الكمية المتاحة في المخزون
        if ($product->quantity < $request->quantity) {
            return response()->json(['message' => 'الكمية المطلوبة غير متوفرة في المخزون.'], 422);
        }

        // 3. الحصول على السلة الحالية أو إنشاء واحدة جديدة
        $cart = $user->carts()->firstOrCreate(
            ['status' => 'pending'],
            ['payment_method' => 'cash_on_delivery']
        );

        // 4. إضافة المنتج أو تحديث كميته باستخدام updateOrCreate
        // هذا الخيار أفضل وأكثر أمانًا من التحقق اليدوي
        $cart->items()->updateOrCreate(
            ['product_id' => $product->id], // الشروط: ابحث عن المنتج في السلة
            ['quantity' => $request->quantity] // القيم: قم بتحديث الكمية
        );

        return response()->json($cart->load('items.product'), 201);
    }

    public function destroyItem($productId)
    {
        $user = auth()->guard('api')->user();
        $cart = $user->carts()->where('status', 'pending')->first();

        if ($cart) {
            // ابحث عن المنتج داخل عناصر السلة وقم بحذفه
            $item = $cart->items()->where('product_id', $productId)->first();
            if ($item) {
                $item->delete();
                return response()->json(['message' => 'تم حذف المنتج من السلة بنجاح.'], 200);
            }
        }

        return response()->json(['message' => 'المنتج غير موجود في السلة.'], 404);
    }

    public function destroyCart()
    {
        $user = auth()->guard('api')->user();
        $cart = $user->carts()->where('status', 'pending')->first();

        if ($cart) {
            $cart->delete();
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'لا توجد سلة لحذفها.'], 404);
    }
}
