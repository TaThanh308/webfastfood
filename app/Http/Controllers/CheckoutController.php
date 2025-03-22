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
    public function formcheckout()
    {
        $cartItems = DB::table('cart')
            ->join('products', 'cart.product_id', '=', 'products.id')
            ->leftJoin('product_discounts', function ($join) {
                $join->on('products.id', '=', 'product_discounts.product_id')
                     ->where('product_discounts.start_date', '<=', now())
                     ->where('product_discounts.valid_until', '>=', now());
            })
            ->select(
                'cart.*',
                'products.name',
                'products.price',
                'products.image',
                'product_discounts.discount_percentage',
                DB::raw('
                    CASE 
                        WHEN product_discounts.discount_percentage IS NOT NULL 
                        THEN products.price * (1 - product_discounts.discount_percentage / 100) 
                        ELSE products.price 
                    END as final_price
                ')
            )
            ->where('cart.user_id', Auth::id())
            ->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Giỏ hàng trống!');
        }
    
        // Tính tổng tiền với giá đã giảm
        $totalPrice = $cartItems->sum(fn($item) => $item->final_price * $item->quantity);
    
        return view('customer.checkout.checkout', compact('cartItems', 'totalPrice'));
    }
    
    public function codPayment(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'total_price' => 'required|numeric|min:1',
        ]);
    
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $validated['total_price'],
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'phone_number' => $validated['phone_number'],
            ]);
    
            $cartItems = Cart::where('user_id', Auth::id())->get();
            foreach ($cartItems as $item) {
                // Kiểm tra giảm giá
                $discount = $item->product->discounts()
                    ->where('start_date', '<=', now())
                    ->where('valid_until', '>=', now())
                    ->first();
            
                // Tính giá sau giảm
                $price = $item->product->price;
                if ($discount) {
                    $price = $item->product->price * (1 - ($discount->discount_percentage / 100));
                }
            
                // Thêm sản phẩm vào bảng order_items
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                ]);
            }
            
    
            Payment::create([
                'order_id' => $order->id,
                'amount' => $validated['total_price'],
                'payment_method' => 'COD',
            ]);
    
            Cart::where('user_id', Auth::id())->delete();
            DB::commit();
            return redirect()->route('orders.show', $order->id)->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('COD Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại.');
        }
    }
    
    

    
    public function vnpayPayment(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    
        // Cấu hình thông tin VNPay
        $vnp_TmnCode = env('VNP_TMNCODE');
        $vnp_HashSecret = env('VNP_HASHSECRET');
        $vnp_Url = env('VNP_URL');
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
                $user = auth()->user();
                if (!$user) {
                    throw new \Exception("Người dùng chưa đăng nhập.");
                }

                // Lấy giỏ hàng của người dùng
                $cartItems = Cart::where('user_id', $user->id)->get();
                if ($cartItems->isEmpty()) {
                    throw new \Exception("Giỏ hàng trống, không thể tạo đơn hàng.");
                }

                // Tạo đơn hàng
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_price' => $request->vnp_Amount / 100, // Chuyển về đơn vị VNĐ
                    'status' => 'confirmed',
                    'shipping_address' => session('checkout_data')['shipping_address'] ?? 'Không có địa chỉ',
                    'phone_number' => session('checkout_data')['phone_number'] ?? 'Không có số điện thoại',
                ]);

                // Thêm sản phẩm vào bảng order_items
                foreach ($cartItems as $item) {
                    $product = $item->product;
                
                    // Kiểm tra xem sản phẩm có giảm giá không
                    $discount = $product->discounts()
                        ->where('start_date', '<=', now())
                        ->where('valid_until', '>=', now())
                        ->first();
                
                    // Nếu có giảm giá, áp dụng giảm giá
                    $discountedPrice = $product->price;
                    if ($discount) {
                        $discountedPrice = $product->price * (1 - ($discount->discount_percentage / 100));
                    }
                
                    // Thêm sản phẩm vào bảng order_items
                    $order->orderItems()->create([
                        'product_id' => $product->id,
                        'quantity' => $item->quantity,
                        'price' => $discountedPrice, // Lưu giá sau khi giảm
                    ]);
                }
                
                // Tạo bản ghi thanh toán
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'VNPAY',
                    'amount' => $order->total_price,
                    'transaction_id' => $request->vnp_TransactionNo,
                ]);

                // Xóa giỏ hàng sau khi thanh toán thành công
                Cart::where('user_id', $user->id)->delete();

                // Xóa session thanh toán
                session()->forget('checkout_data');

                DB::commit();
                return redirect()->route('orders.show', $order->id)->with('success', 'Thanh toán VNPAY thành công!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('VNPay Order Error:', ['error' => $e->getMessage()]);
                return redirect()->route('customer.cart.index')->with('error', 'Lỗi xử lý đơn hàng: ' . $e->getMessage());
            }
        }

        return redirect()->route('customer.cart.index')->with('error', 'Thanh toán thất bại!');
    }

    
}
