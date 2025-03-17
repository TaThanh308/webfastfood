<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


class CheckoutController extends Controller
{
    public function checkout()
    {
        $cartItems = DB::table('cart')
            ->join('products', 'cart.product_id', '=', 'products.id')
            ->select('cart.*', 'products.name', 'products.price', 'products.image')
            ->where('cart.user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $totalPrice = $cartItems->sum(fn($item) => $item->price * $item->quantity);

        return view('customer.checkout.checkout', compact('cartItems', 'totalPrice'));
    }

    public function codPayment(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required',
            'phone_number' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $request->total_price,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'phone_number' => $request->phone_number,
            ]);

            // Thêm vào bảng order_items
            $cartItems = Cart::where('user_id', Auth::id())->get();
            foreach ($cartItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Tạo thanh toán
            Payment::create([
                'order_id' => $order->id,
                'amount' => $request->total_price,
                'payment_method' => 'COD',
            ]);

            // Xóa giỏ hàng
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();
            return redirect()->route('customer.orders')->with('success', 'Đặt hàng thành công! Vui lòng nhận hàng và thanh toán khi giao hàng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }

    
    public function vnpayPayment(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    
        // Cấu hình thông tin VNPay
        $vnp_TmnCode = 'TH6K1YAU'; 
        $vnp_HashSecret = 'FYX6B1YUYE6KG2J4E575SFBGTXAELOUD';
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = url('/vnpay/return');
    
        // Tạo mã giao dịch duy nhất
        $vnp_TxnRef = time(); 
        $vnp_Amount = intval($request->total_price) * 100; // Chuyển đổi giá trị về số nguyên
        $vnp_OrderInfo = "Thanh toán đơn hàng #$vnp_TxnRef qua VNPAY";
        $vnp_IpAddr = request()->ip(); 
    
        // Tạo mảng dữ liệu cần gửi
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];
    
        // Sắp xếp dữ liệu theo key
        ksort($inputData);
    
        // Tạo query string chính xác (dùng & thay vì urldecode)
        $queryString = [];
        foreach ($inputData as $key => $value) {
            $queryString[] = urlencode($key) . "=" . urlencode($value);
        }
        $queryString = implode('&', $queryString);
    
        // Tạo chữ ký bảo mật
        $vnpSecureHash = hash_hmac('sha512', $queryString, $vnp_HashSecret);
    
        // Tạo URL thanh toán cuối cùng
        $paymentUrl = $vnp_Url . "?" . $queryString . "&vnp_SecureHash=" . $vnpSecureHash;
    
        // Ghi log để kiểm tra lỗi
        Log::info('VNPay Payment Request:', $inputData);
        Log::info('Generated Hash:', ['hash' => $vnpSecureHash]);
        Log::info('VNPay Payment URL:', ['url' => $paymentUrl]);
    
        return redirect($paymentUrl);
    }
    
    public function vnpayReturn(Request $request)
    {
        Log::info('VNPay Response:', $request->all());
    
        if ($request->vnp_ResponseCode == "00") { // Thanh toán thành công
            DB::beginTransaction();
            try {
                $userId = Auth::id();
                if (!$userId) {
                    throw new \Exception("User chưa đăng nhập.");
                }
    
                // Kiểm tra giỏ hàng
                $cartItems = Cart::where('user_id', $userId)->get();
                if ($cartItems->isEmpty()) {
                    throw new \Exception("Giỏ hàng trống, không thể tạo đơn hàng.");
                }
    
                // Kiểm tra session
                $shippingAddress = session('shipping_address');
                $phoneNumber = session('phone_number');
                if (!$shippingAddress || !$phoneNumber) {
                    throw new \Exception("Thông tin giao hàng không hợp lệ.");
                }
    
                // Tạo đơn hàng
                $order = Order::create([
                    'user_id' => $userId,
                    'total_price' => $request->vnp_Amount / 100,
                    'status' => 'paid',
                    'shipping_address' => $shippingAddress,
                    'phone_number' => $phoneNumber,
                ]);
    
                // Lưu chi tiết đơn hàng
                foreach ($cartItems as $item) {
                    $order->orderItems()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);
                }
    
                // Lưu thông tin thanh toán
                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => $request->vnp_TxnRef,
                    'amount' => $request->vnp_Amount / 100,
                    'payment_method' => 'VNPAY',
                ]);
    
                // Xóa giỏ hàng sau thanh toán
                Cart::where('user_id', $userId)->delete();
    
                DB::commit();
                return redirect()->route('customer.orders')->with('success', 'Thanh toán thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('VNPay Order Error:', ['error' => $e->getMessage()]);
                return redirect()->route('customer.checkout.checkout')->with('error', 'Lỗi xử lý đơn hàng: ' . $e->getMessage());
            }
        }
    
        return redirect()->route('customer.cart.index')->with('error', 'Thanh toán không thành công.');
    }
    
    
}
