@extends('layouts.user')

@section('content')
<div class="container py-5">
     <!-- Banner Section sát lên trên và tự động chuyển trong 3 giây -->
     <div class="container p-0">
        @if(isset($banners) && $banners->count() > 0)
            <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000"> <!-- Thêm data-bs-interval="3000" -->
                <div class="carousel-inner">
                    @foreach($banners as $key => $banner)
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <a href="{{ $banner->link ?? '#' }}" target="_blank">
                                <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100" alt="{{ $banner->title }}">
                            </a>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        @else
            <p class="text-center">Chưa có banner nào được hiển thị.</p>
        @endif
    </div></br>
    <h1 class="text-center mb-4 text-uppercase fw-bold">Sản Phẩm Giảm Giá</h1>

    <div class="row">
        @foreach ($products as $product)
            @php
                $discount = $product->discounts->first();
                $discounted_price = $product->price * (1 - $discount->discount_percentage / 100);
            @endphp

            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <a href="{{ route('customer.products.show', $product->id) }}" class="text-decoration-none">
                    <div class="card product-card border-0 shadow-lg">
                        <div class="product-image position-relative">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">

                            <!-- Dùng lớp Bootstrap để hiển thị giảm giá -->
                            <div class="position-absolute top-0 start-0 bg-danger text-white py-1 px-3 rounded-end">
                                -{{ round($discount->discount_percentage) }}%
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-dark">{{ $product->name }}</h5>
                            <p class="fw-bold text-danger fs-5">
                                <del class="text-muted">{{ number_format($product->price, 0, ',', '.') }} VNĐ</del>
                                <br>
                                {{ number_format($discounted_price, 0, ',', '.') }} VNĐ
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
