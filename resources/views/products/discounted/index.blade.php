@extends('layouts.user')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4 text-uppercase fw-bold">Sản Phẩm Giảm Giá</h1>

    <div class="row">
        @foreach ($products as $product)
            @php
                $discount = $product->discounts->first();
                $discounted_price = $product->price * (1 - $discount->discount_percentage / 100);
            @endphp

            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card product-card border-0 shadow-lg">
                    <div class="product-image position-relative">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        
                        <!-- Hiển thị % giảm giá đẹp hơn -->
                        <div class="discount-badge">
                            -{{ round($discount->discount_percentage) }}%
                        </div>

                        <div class="overlay">
                            <a href="{{ route('products.discounted.show', $product->id) }}" class="btn btn-light btn-view">Xem Chi Tiết</a>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                        <p class="fw-bold text-danger fs-5">
                            <del class="text-muted">{{ number_format($product->price, 0, ',', '.') }} VNĐ</del>
                            <br>
                            {{ number_format($discounted_price, 0, ',', '.') }} VNĐ
                        </p>
                        <a href="{{ route('products.discounted.show', $product->id) }}" class="btn btn-primary w-100 fw-bold">Xem chi tiết</a>
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

    /* Badge giảm giá đẹp hơn */
    .discount-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: linear-gradient(135deg, #ff0000, #ff5733);
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 8px 12px;
        border-radius: 50px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transform: rotate(-10deg);
    }
</style>

@endsection
