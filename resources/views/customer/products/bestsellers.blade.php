@extends('layouts.user')

<style>
    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s;
        border-radius: 10px;
        overflow: hidden;
        height: 100%;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        font-size: 1rem;
    }

    .card-text {
        font-size: 0.9rem;
    }

    .price-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }

    .original-price {
        text-decoration: line-through;
        color: gray;
        font-size: 0.9rem;
    }

    .discount-price {
        color: red;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .btn {
        transition: background 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-success:hover {
        background: #28a745;
        opacity: 0.8;
    }
    h3.text-center {
    margin-top: 20px; /* Tạo khoảng cách giữa phần tiêu đề và phần header */
    padding-top: 10px; /* Đảm bảo không bị dính vào lề trên */
    font-size: 1.5rem; /* Đảm bảo chữ không bị quá nhỏ */
}

</style>

@section('content')
<div class="container mt-4">
    <h3 class="text-center text-danger fw-bold">🔥 Sản phẩm bán chạy nhất 🔥</h3>
    <div class="row">
        @foreach ($bestSellingProducts as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card shadow-lg border-0">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top rounded-top" alt="{{ $product->name }}">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold text-primary">{{ $product->name }}</h5>
                        
                        <!-- Xử lý giá sản phẩm có giảm giá -->
                        <div class="price-container">
                            @php
                                $discountedPrice = $product->price;
                                if (isset($product->discount_percentage) && $product->discount_percentage > 0) {
                                    $discountedPrice = $product->price * (1 - $product->discount_percentage / 100);
                                }
                            @endphp
                            
                            @if ($discountedPrice < $product->price)
                                <span class="original-price">{{ number_format($product->price, 0, ',', '.') }} VND</span>
                                <span class="discount-price">{{ number_format($discountedPrice, 0, ',', '.') }} VND</span>
                            @else
                                <span class="discount-price">{{ number_format($product->price, 0, ',', '.') }} VND</span>
                            @endif
                        </div>
                        
                        <p class="text-muted">🛒 Đã bán: <strong>{{ $product->total_sold }}</strong> lần</p>
                        <a href="{{ route('customer.products.show', $product->id) }}" class="btn btn-success">
                            <i class="fas fa-shopping-cart"></i> Mua ngay
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-3">
                {{ $bestSellingProducts->links('pagination::bootstrap-4') }}
            </div>
</div>
@endsection