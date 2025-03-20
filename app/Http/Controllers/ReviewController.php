<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);
    
        $order = Order::with('orderItems')->findOrFail($orderId);
    
        if ($order->orderItems->isEmpty()) {
            return redirect()->back()->with('error', 'Không có sản phẩm nào để đánh giá!');
        }
    
        foreach ($order->orderItems as $item) {
            Review::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $item->product_id,
                    'order_id' => $order->id,
                ],
                [
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]
            );
        }
    
        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
    
    public function index()
{
    $reviews = Review::with('user', 'product')->latest()->take(10)->get(); // Lấy 10 đánh giá mới nhất
    return view('home', compact('reviews'));
}
}
