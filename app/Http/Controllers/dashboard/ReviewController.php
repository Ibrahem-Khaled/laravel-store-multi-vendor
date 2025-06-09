<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'user'])
            ->when(request('product_id'), function ($query) {
                $query->where('product_id', request('product_id'));
            })
            ->when(request('user_id'), function ($query) {
                $query->where('user_id', request('user_id'));
            })
            ->when(request('status'), function ($query) {
                $query->where('is_approved', request('status') === 'approved');
            })
            ->when(request('search'), function ($query) {
                $query->where('comment', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(10);

        $products = Product::all();
        $users = User::all();

        $totalReviews = Review::count();
        $approvedReviews = Review::where('is_approved', true)->count();
        $pendingReviews = Review::where('is_approved', false)->count();
        $averageRating = Review::avg('rate') ?? 0;

        return view('dashboard.reviews.index', compact(
            'reviews',
            'products',
            'users',
            'totalReviews',
            'approvedReviews',
            'pendingReviews',
            'averageRating'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rate' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create($request->all());

        return redirect()->route('reviews.index')
            ->with('success', 'تم إضافة التقييم بنجاح');
    }

    public function update(Request $request, Review $review)
    {
        $request->validate([
            'rate' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($request->only(['rate', 'comment']));

        return redirect()->route('reviews.index')
            ->with('success', 'تم تحديث التقييم بنجاح');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('reviews.index')
            ->with('success', 'تم حذف التقييم بنجاح');
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'تم الموافقة على التقييم بنجاح');
    }

    public function disapprove(Review $review)
    {
        $review->update(['is_approved' => false]);

        return back()->with('success', 'تم رفض التقييم بنجاح');
    }
}
