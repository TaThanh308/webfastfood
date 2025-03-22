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

        // Kiểm tra trạng thái đơn hàng
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá khi đơn hàng đã hoàn thành!');
        }

        if ($order->orderItems->isEmpty()) {
            return redirect()->back()->with('error', 'Không có sản phẩm nào để đánh giá!');
        }

        foreach ($order->orderItems as $item) {
            // Kiểm tra xem khách hàng đã đánh giá sản phẩm này trong đơn hàng này chưa
            $existingReview = Review::where('user_id', auth()->id())
                ->where('product_id', $item->product_id)
                ->where('order_id', $order->id)
                ->first();

            if ($existingReview) {
                return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này trước đó!');
            }

            // Nếu chưa có đánh giá, tạo mới
            Review::create([
                'user_id' => auth()->id(),
                'product_id' => $item->product_id,
                'order_id' => $order->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        }

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    
    public function index()
    {
        $reviews = Review::with('user', 'product')->latest()->take(10)->get(); // Lấy 10 đánh giá mới nhất
        return view('home', compact('reviews'));
    }
    }
