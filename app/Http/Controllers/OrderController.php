<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng của khách hàng đăng nhập.
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Xem chi tiết đơn hàng.
     */
    public function show($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('customer.orders.show', compact('order'));
    }

    /**
     * Hủy đơn hàng (chuyển trạng thái thành 'canceled').
     */
    public function cancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Đơn hàng không thể hủy.');
        }

        $order->status = 'canceled';
        $order->save();

        return redirect()->route('orders.my')->with('success', 'Đơn hàng đã được hủy.');
    }
}
