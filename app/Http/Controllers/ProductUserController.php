<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\ProductDiscount;

class ProductUserController extends Controller
{
 // Hiển thị danh sách sản phẩm
 public function index()
 {
    $today = Carbon::today();

    // Lấy danh sách sản phẩm KHÔNG có giảm giá hợp lệ
    $products = Product::whereDoesntHave('discounts', function ($query) use ($today) {
        $query->where('start_date', '<=', $today)
              ->where('valid_until', '>=', $today);
    })->get();

    return view('customer.products.index', compact('products'));
 }

 // Hiển thị chi tiết sản phẩm
 public function show($id)
 {
     $product = Product::findOrFail($id);
     return view('customer.products.show', compact('product'));
 }}
