<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductDiscount;

class ProductDiscountController extends Controller
{
    public function index()
    {
        $discounts = ProductDiscount::with('product')->get();
        return view('admin.discounts.index', compact('discounts'));
    }

    // Form thêm mới giảm giá
    public function create()
    {
        $products = Product::all();
        return view('admin.discounts.create', compact('products'));
    }

    // Lưu giảm giá mới
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'valid_until' => 'required|date|after_or_equal:start_date',
        ]);
        

        ProductDiscount::create($request->all());

        return redirect()->route('discounts.index')->with('success', 'Thêm giảm giá thành công!');
    }

    // Form chỉnh sửa giảm giá
    public function edit(ProductDiscount $discount)
    {
        $products = Product::all();
        return view('admin.discounts.edit', compact('discount', 'products'));
    }

    // Cập nhật giảm giá
    public function update(Request $request, ProductDiscount $discount)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'valid_until' => 'required|date|after_or_equal:start_date',
        ]);
        

        $discount->update($request->all());

        return redirect()->route('discounts.index')->with('success', 'Cập nhật giảm giá thành công!');
    }

    // Xóa giảm giá
    public function destroy(ProductDiscount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Xóa giảm giá thành công!');
    }
}
