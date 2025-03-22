@extends('layouts.user')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4 text-uppercase fw-bold">Danh Sách Sản Phẩm</h1>

    <div class="row">
    @foreach ($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card product-card border-0 shadow-lg">
                <div class="product-image">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="overlay">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-light btn-view">Xem Chi Tiết</a>
                    </div>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                    <p class="fw-bold text-danger fs-5">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label for="quantity">Số lượng:</label>
                        <input type="number" name="quantity" value="1" min="1" required>
                        <button type="submit">Thêm vào giỏ hàng</button>
                        </form>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary w-100 fw-bold">Xem chi tiết</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>

<style>
    /* CSS Đẳng cấp - Đỉnh cao thế giới */
    .product-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border-radius: 15px;
        overflow: hidden;
    }

    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .product-image img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease-in-out;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }

    .product-card:hover .overlay {
        opacity: 1;
    }

    .btn-view {
        font-size: 1.1rem;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 30px;
        transition: background 0.3s ease-in-out;
    }

    .btn-view:hover {
        background: #ffcc00;
        color: #000;
    }

    .card-body {
        background: #fff;
        padding: 20px;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
    }

    .card-title {
        color: #333;
    }

    .btn-primary {
        border-radius: 30px;
        padding: 10px;
        font-size: 1rem;
    }

    .btn-primary:hover {
        background: #ff5733;
        border-color: #ff5733;
    }
</style>
@endsection
