<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Banner;
use App\Models\ProductDiscount;
use App\Models\News;

class ProductUserController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $today = Carbon::today();
        // Lấy danh sách sản phẩm có giảm giá hợp lệ
        $banners = Banner::where('status', 'active')->get();

        // Lấy danh sách sản phẩm KHÔNG có giảm giá hợp lệ
        $products = Product::whereDoesntHave('discounts', function ($query) use ($today) {
            $query->where('start_date', '<=', $today)
                ->where('valid_until', '>=', $today);
        })->get();

        $news = News::where('status', 'published')->latest()->take(3)->get(); // Lấy 5 tin tức mới nhất

        return view('customer.products.index', compact('products', 'banners', 'news'));
    }
    // Hiển thị chi tiết sản phẩm
    public function show($id)
    {
        $product = Product::with('reviews.user')->findOrFail($id);
    
        // Kiểm tra có giảm giá hay không
        $discount = $product->discounts->first();
        $product->discount_price = $discount 
            ? round($product->price * (1 - $discount->discount_percentage / 100), 0)
            : null;
    
        // Lấy sản phẩm liên quan
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $id)
                                ->limit(4)
                                ->get()
                                ->map(function ($relatedProduct) {
                                    $relatedDiscount = $relatedProduct->discounts->first();
                                    $relatedProduct->discount_price = $relatedDiscount
                                        ? round($relatedProduct->price * (1 - $relatedDiscount->discount_percentage / 100), 0)
                                        : null;
                                    return $relatedProduct;
                                });
    
        // Lấy danh sách đánh giá sản phẩm
        $reviews = $product->reviews()->with('user')->latest()->get();
    
        return view('customer.products.show', compact('product', 'relatedProducts', 'reviews'));
    }
    public function bestSellers()
    {
        $bestSellingProducts = Product::select(
                'products.id', 
                'products.name', 
                'products.image', 
                'products.price', 
                'product_discounts.discount_percentage', // Lấy phần trăm giảm giá
                'products.category_id'
            )
            ->leftJoin('product_discounts', function ($join) {
                $join->on('products.id', '=', 'product_discounts.product_id')
                    ->whereDate('product_discounts.start_date', '<=', now()) // Giảm giá đã bắt đầu
                    ->whereDate('product_discounts.valid_until', '>=', now()); // Giảm giá còn hiệu lực
            })
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('COUNT(order_items.id) as total_sold')
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price', 'product_discounts.discount_percentage', 'products.category_id') 
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();
    
        return view('customer.products.bestsellers', compact('bestSellingProducts'));
    }
    
    
}
