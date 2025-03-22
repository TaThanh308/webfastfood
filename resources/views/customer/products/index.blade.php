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

    <!-- Product Section -->
    <h1 class="text-center mb-4 text-uppercase fw-bold">Danh Sách Sản Phẩm</h1>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <!-- Sử dụng Bootstrap shadow-lg cho hiệu ứng bóng mờ -->
                <div class="card border-0 shadow-lg h-100 d-flex flex-column">
                    <a href="{{ route('customer.products.show', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    </a>
                    <div class="card-body text-center flex-grow-1 d-flex flex-column justify-content-between">
                        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                            <h5 class="fw-bold">{{ $product->name }}</h5>
                        </a>
                        <p class="fw-bold text-danger fs-5">
                            {{ number_format($product->price, 0, ',', '.') }} VNĐ
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
