@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Chỉnh Sửa Giảm Giá</h1>

    <form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Sản phẩm:</label>
            <select name="product_id" class="form-control">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $product->id == $discount->product_id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>% Giảm giá:</label>
            <input type="number" name="discount_percentage" class="form-control" value="{{ $discount->discount_percentage }}" min="1" max="100" required>
        </div>

        <div class="mb-3">
            <label>Ngày bắt đầu:</label>
            <input type="date" name="start_date" class="form-control" value="{{ $discount->start_date }}" min="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label>Ngày kết thúc:</label>
            <input type="date" name="valid_until" class="form-control" value="{{ $discount->valid_until }}" required>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
</div>
@endsection
