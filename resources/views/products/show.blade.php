@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="product-image-container">
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-lg product-image" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-lg-6">
            <h1 class="fw-bold text-uppercase">{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->description }}</p>
            <p class="fw-bold text-danger fs-4">Giá: {{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
            <p>Kích thước: <strong>{{ $product->size ?? 'Không có' }}</strong></p>
            <p>Còn lại: 
                <strong class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $product->stock > 0 ? $product->stock . ' sản phẩm' : 'Hết hàng' }}
                </strong>
            </p>
            <p>Danh mục: <span class="badge bg-primary">{{ $product->category->name ?? 'Không xác định' }}</span></p>

            @auth
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">🛒 Thêm vào giỏ hàng</button>
                </form>
            @else
                <p class="mt-3"><a href="{{ route('login') }}" class="btn btn-warning btn-lg w-100 fw-bold">🔑 Đăng nhập để mua hàng</a></p>
            @endauth
        </div>
    </div>
</div>

<style>
    /* Ảnh sản phẩm thu nhỏ gọn hơn */
    .product-image-container {
        width: 100%;
        max-width: 400px; /* Đặt giới hạn chiều rộng */
        margin: 0 auto; /* Canh giữa */
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease-in-out;
    }

    .product-image {
        width: 100%;
        height: 300px; /* Chiều cao cố định */
        object-fit: cover; /* Cắt ảnh để vừa khung */
        transition: transform 0.3s ease-in-out;
    }

    .product-image-container:hover .product-image {
        transform: scale(1.05);
    }

    .btn-primary, .btn-warning {
        border-radius: 30px;
        padding: 12px;
        font-size: 1.2rem;
    }

    .text-danger {
        font-size: 1.2rem;
    }
</style>
@endsection
