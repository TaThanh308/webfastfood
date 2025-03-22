@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-lg-6">
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded shadow-lg" alt="{{ $product->name }}">
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-lg-6">
            <h1 class="fw-bold text-uppercase">{{ $product->name }}</h1>
            <p class="text-muted">{{ $product->description }}</p>
            
            @php
                $discounted_price = $product->discount_price ?? $product->price;
                $has_discount = $product->discount_price && $product->discount_price < $product->price;
                $discount_percentage = $has_discount ? round((($product->price - $product->discount_price) / $product->price) * 100) : 0;
            @endphp

            <p class="fw-bold fs-4">
                @if ($has_discount)
                    <span class="text-decoration-line-through text-muted me-2">
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </span>
                    <span class="text-danger">{{ number_format($discounted_price, 0, ',', '.') }} VNĐ</span>
                @else
                    <span class="text-primary">{{ number_format($product->price, 0, ',', '.') }} VNĐ</span>
                @endif
            </p>
            
            @if ($has_discount)
                <span class="badge bg-danger fs-5 p-2">🔥 Giảm {{ $discount_percentage }}%</span>
            @endif

            <p>Kích thước: <strong>{{ $product->size ?? 'Không có' }}</strong></p>
            <p>Còn lại: 
                <strong class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $product->stock > 0 ? $product->stock . ' sản phẩm' : 'Hết hàng' }}
                </strong>
            </p>
            <p>Danh mục: <span class="badge bg-primary">{{ $product->category->name ?? 'Không xác định' }}</span></p>

            @auth
            <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="d-flex align-items-center">
                    <label for="quantity" class="form-label me-2 fw-bold">Số lượng:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control w-25 me-3" value="1" min="1" required>
                    <button type="submit" class="btn btn-success fw-bold">🛒 Thêm vào giỏ</button>
                </div>
            </form>
            @else
                <p class="mt-3"><a href="{{ route('login') }}" class="btn btn-warning btn-lg w-100 fw-bold">🔑 Đăng nhập để mua hàng</a></p>
            @endauth
        </div>
    </div>

    <!-- Thống kê đánh giá -->
    <div class="container mt-5">
        <h3 class="text-center fw-bold text-uppercase">Thống kê đánh giá</h3>
        @php
            $totalReviews = $reviews->count();
            $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
            foreach ($reviews as $review) {
                $ratingCounts[$review->rating]++;
            }
        @endphp

        @if ($totalReviews > 0)
            <p class="text-center fw-bold">Tổng số lượt đánh giá: {{ $totalReviews }}</p>
            @foreach ($ratingCounts as $stars => $count)
                @php
                    $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100, 2) : 0;
                @endphp
                <div class="d-flex align-items-center mb-2">
                    <span class="fw-bold me-2">{{ $stars }} ⭐</span>
                    <div class="progress w-50 me-2">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="fw-bold">{{ $percentage }}% ({{ $count }} lượt)</span>
                </div>
            @endforeach
        @else
            <p class="text-muted text-center">Chưa có đánh giá nào.</p>
        @endif
    </div>

    <!-- Danh sách đánh giá -->
    <div class="container mt-5">
        <h3 class="text-center fw-bold text-uppercase">Đánh giá sản phẩm</h3>
        @if($reviews->isEmpty())
            <p class="text-muted text-center">Chưa có đánh giá nào.</p>
        @else
            @foreach ($reviews as $review)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $review->user->name }}</h5>
                        <p class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                            @endfor
                        </p>
                        <p class="card-text">{{ $review->comment }}</p>
                        <small class="text-muted">Đánh giá vào {{ $review->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <!-- Sản phẩm liên quan -->
   <!-- Sản phẩm liên quan -->
    <div class="container mt-5">
        <h3 class="text-center fw-bold text-uppercase mb-4">Sản phẩm liên quan</h3>
        <div class="row">
            @foreach ($relatedProducts as $relatedProduct)
                @php
                    $related_discounted_price = $relatedProduct->discount_price ?? $relatedProduct->price;
                    $related_has_discount = $relatedProduct->discount_price && $relatedProduct->discount_price < $relatedProduct->price;
                    $related_discount_percentage = $related_has_discount ? round((($relatedProduct->price - $relatedProduct->discount_price) / $relatedProduct->price) * 100) : 0;
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
                    <div class="card product-card border-0 shadow-lg d-flex flex-column h-100">
                        <a href="{{ route('customer.products.show', $relatedProduct->id) }}" class="text-decoration-none">
                            <div class="product-image position-relative">
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                                
                                @if ($related_has_discount)
                                    <div class="discount-badge position-absolute top-0 start-0 bg-danger text-white p-2 rounded-end" style="font-size: 14px;">
                                        -{{ $related_discount_percentage }}%
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="card-body text-center d-flex flex-column justify-content-between flex-grow-1">
                            <h5 class="card-title fw-bold">
                                <a href="{{ route('customer.products.show', $relatedProduct->id) }}" class="text-dark text-decoration-none">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h5>
                            <p class="fw-bold text-danger fs-5">
                                @if ($related_has_discount)
                                    <del class="text-muted">{{ number_format($relatedProduct->price, 0, ',', '.') }} VNĐ</del><br>
                                    <span class="text-danger">{{ number_format($relatedProduct->discount_price, 0, ',', '.') }} VNĐ</span>
                                @else
                                    <span class="text-primary">{{ number_format($relatedProduct->price, 0, ',', '.') }} VNĐ</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

            @endforeach

            @if ($relatedProducts->isEmpty())
                <p class="text-muted text-center">Không có sản phẩm liên quan.</p>
            @endif
        </div>
    </div>

</div>
@endsection
