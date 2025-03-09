@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Thêm Giảm Giá</h1>

    <form action="{{ route('discounts.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Sản phẩm:</label>
            <select name="product_id" class="form-control">
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>% Giảm giá:</label>
            <input type="number" name="discount_percentage" class="form-control" min="1" max="100" required>
        </div>
        <div class="mb-3">
            <label>Ngày bắt đầu:</label>
            <input type="date" name="start_date" class="form-control" min="{{ date('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label>Ngày kết thúc:</label>
            <input type="date" name="valid_until" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection
