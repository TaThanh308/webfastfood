@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Ảnh sản phẩm -->
        <div class="col-md-6">
            <div class="position-relative">
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-lg" alt="{{ $product->name }}">

                @php
                    $discount = $product->discounts->first();
                    $discount_percentage = $discount ? round($discount->discount_percentage) : 0;
                    $discounted_price = $discount ? ($product->price * (1 - $discount->discount_percentage / 100)) : $product->price;
                @endphp

                <!-- Chỉ hiển thị badge nếu có giảm giá -->
                @if ($discount)
                    <div class="discount-badge">-{{ $discount_percentage }}%</div>
                @endif
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
            <h1 class="fw-bold">{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->description }}</p>

            <p class="fw-bold fs-4">
                @if ($discount)
                    <del class="text-muted">{{ number_format($product->price, 0, ',', '.') }} VNĐ</del>
                    <span class="text-danger ms-2">{{ number_format($discounted_price, 0, ',', '.') }} VNĐ</span>
                @else
                    <span class="text-primary">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                @endif
            </p>

            @if ($discount)
                <span class="badge bg-danger fs-5 p-2">🔥 Giảm {{ $discount_percentage }}%</span>
                <p class="text-muted mt-3">
                    <i class="fas fa-calendar-alt"></i> Áp dụng từ: <strong>{{ \Carbon\Carbon::parse($discount->start_date)->format('d/m/Y') }}</strong> 
                    đến <strong>{{ \Carbon\Carbon::parse($discount->valid_until)->format('d/m/Y') }}</strong>
                </p>
            @endif

            @auth
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg mt-3"><i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng</button>
                </form>
            @else
                <p class="mt-3">
                    <a href="{{ route('login') }}" class="btn btn-warning btn-lg"><i class="fas fa-sign-in-alt"></i> Đăng nhập để mua hàng</a>
                </p>
            @endauth
        </div>
    </div>
</div>

<style>
    /* Badge hiển thị % giảm giá */
    .discount-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: linear-gradient(135deg, #ff0000, #ff5733);
        color: white;
        font-size: 18px;
        font-weight: bold;
        padding: 8px 12px;
        border-radius: 50px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transform: rotate(-10deg);
    }

    /* Nút thêm vào giỏ hàng */
    .btn-primary {
        border-radius: 30px;
        padding: 12px 20px;
        font-size: 1.1rem;
    }

    .btn-primary:hover {
        background: #ff5733;
        border-color: #ff5733;
    }

    /* Giao diện card */
    .img-fluid {
        border-radius: 15px;
        transition: transform 0.3s ease-in-out;
    }

    .img-fluid:hover {
        transform: scale(1.05);
    }

    /* Chỉnh sửa badge giảm giá */
    .badge.bg-danger {
        font-size: 1rem;
        font-weight: bold;
    }
</style>

@endsection
