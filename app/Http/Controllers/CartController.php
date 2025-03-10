<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem giỏ hàng');
    }

    $cart = DB::table('cart')
        ->join('products', 'cart.product_id', '=', 'products.id')
        ->leftJoin('product_discounts', function ($join) {
            $join->on('cart.product_id', '=', 'product_discounts.product_id')
                 ->whereDate('product_discounts.start_date', '<=', now())
                 ->whereDate('product_discounts.valid_until', '>=', now());
        })
        ->select(
            'cart.id', 
            'products.name', 
            'products.image', 
            'products.price', 
            'product_discounts.discount_percentage',
            'cart.quantity',
            DB::raw('IFNULL(products.price * (1 - product_discounts.discount_percentage / 100), products.price) AS final_price')
        )
        ->where('cart.user_id', Auth::id())
        ->get();

    return view('customer.cart.index', compact('cart'));
}

    
public function addToCart(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm vào giỏ hàng');
    }

    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $product = Product::findOrFail($request->product_id);

    // Lấy giảm giá hiện tại (nếu có)
    $discount = DB::table('product_discounts')
        ->where('product_id', $product->id)
        ->whereDate('start_date', '<=', now())
        ->whereDate('valid_until', '>=', now())
        ->value('discount_percentage');

    $final_price = $discount ? $product->price * (1 - $discount / 100) : $product->price;

    $cartItem = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

    if ($cartItem) {
        $cartItem->quantity += $request->quantity;
        $cartItem->save();
    } else {
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $final_price
        ]);
    }

    return redirect()->route('customer.cart.index')->with('success', 'Đã thêm vào giỏ hàng');
}

    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        DB::table('cart')
            ->where('id', $id)
            ->update(['quantity' => $request->quantity]);

        return redirect()->route('customer.cart.index')->with('success', 'Cập nhật giỏ hàng thành công');
    }
    public function removeFromCart($id)
    {
        DB::table('cart')->where('id', $id)->delete();

        return redirect()->route('customer.cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }


}
