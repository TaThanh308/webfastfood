@extends('layouts.user')

@section('content')
<div class="container mt-4">
    @if(isset($banners) && $banners->count() > 0)
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <a href="{{ $banner->link ?? '#' }}" target="_blank">
                            <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100 banner-img" alt="{{ $banner->title }}">
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
</div>
<div class="container mt-4">
    <h3 class="text-center">Đánh giá mới nhất</h3>
    <div class="row">
        @foreach ($reviews as $review)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $review->user->name ?? 'Người dùng ẩn danh' }}</h5>
                        <p class="card-text">
                            <strong>Sản phẩm:</strong> {{ $review->product->name ?? 'Không xác định' }}
                        </p>
                        <p>
                            <strong>Đánh giá:</strong> 
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                        </p>
                        <p class="text-muted">{{ $review->comment ?? 'Không có nhận xét' }}</p>
                        <small class="text-muted">Ngày đánh giá: {{ $review->created_at->format('d/m/Y') }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<style>
    .banner-img {
        height: 400px; /* Tăng chiều cao banner */
        object-fit: cover; /* Giữ tỉ lệ ảnh */
        border-radius: 15px; /* Bo góc nhẹ */
    }
    .carousel {
        max-width: 1000px; /* Tăng chiều rộng để phù hợp */
        margin: 0 auto; /* Căn giữa carousel */
    }
</style>
@endsection
