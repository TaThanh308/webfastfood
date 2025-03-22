<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;

class DiscountProductController extends Controller
{
     public function index()
    {
        // Lấy ra tất cả sản phẩm đang giảm giá
        $banners = Banner::where('status', 'active')->get();
        // Lấy ra tất cả sản phẩm không giảm giá
        $products = Product::whereHas('discounts', function ($query) {
            $query->where('start_date', '<=', now())
                  ->where('valid_until', '>=', now());
        })->with(['discounts' => function ($query) {
            $query->where('start_date', '<=', now())
                  ->where('valid_until', '>=', now());
        }])->get();

        return view('customer.discounted.index', compact('products', 'banners'));
    }

    // // Trang chi tiết sản phẩm giảm giá
    // public function show($id)
    // {
    //     $product = Product::with(['discounts' => function ($query) {
    //         $query->where('start_date', '<=', now())
    //               ->where('valid_until', '>=', now());
    //     }])->findOrFail($id);

    //     // Kiểm tra xem sản phẩm có giảm giá không
    //     if (!$product->discounts->count()) {
    //         return redirect()->route('customer.discounts.index')->with('error', 'Sản phẩm này không có giảm giá!');
    //     }

    //     return view('customer.discounted.show', compact('product'));
    // }
}
