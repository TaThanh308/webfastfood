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

        // Kiểm tra nếu số lượng sản phẩm trong kho đủ
        if ($product->stock < $request->quantity) {
            return redirect()->route('customer.cart.index')->with('error', 'Sản phẩm không đủ trong kho');
        }

        // Giảm số lượng sản phẩm trong kho
        $product->stock -= $request->quantity;
        $product->save();

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
                'price' => $product->price
            ]);
        }

        return redirect()->route('customer.cart.index')->with('success', 'Đã thêm vào giỏ hàng');
    }


    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::findOrFail($id);
        $product = Product::findOrFail($cartItem->product_id);

        // Kiểm tra nếu số lượng sản phẩm trong kho đủ
        if ($product->stock + $cartItem->quantity < $request->quantity) {
            return redirect()->route('customer.cart.index')->with('error', 'Sản phẩm không đủ trong kho');
        }

        // Cập nhật lại số lượng giỏ hàng
        $product->stock += $cartItem->quantity - $request->quantity; // Thêm số lượng cũ vào lại kho nếu giảm
        $product->save();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('customer.cart.index')->with('success', 'Cập nhật giỏ hàng thành công');
    }

    public function removeFromCart($id)
    {
        $cartItem = Cart::findOrFail($id);
        $product = Product::findOrFail($cartItem->product_id);

        // Cộng lại số lượng vào kho khi xóa sản phẩm khỏi giỏ hàng
        $product->stock += $cartItem->quantity;
        $product->save();

        // Xóa sản phẩm khỏi giỏ hàng
        $cartItem->delete();

        return redirect()->route('customer.cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }


}
