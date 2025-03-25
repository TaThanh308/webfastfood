@extends('layouts.user')
<!-- CSS for Fire & Marquee Effect -->
<style>
    /* Tạo hiệu ứng cháy bằng glow */
    .marquee-container {
        width: 100%;
        overflow: hidden;
        white-space: nowrap;
        background: linear-gradient(to right, #ff6a00, #ee0979);
        padding: 10px 0;
        box-shadow: 0px 4px 10px rgba(255, 105, 180, 0.8);
    }

    /* Hiệu ứng chạy chữ */
    .marquee-text {
        display: inline-block;
        font-size: 28px;
        font-weight: bold;
        color: white;
        text-shadow: 0 0 10px #ff0000, 0 0 20px #ff7300, 0 0 30px #ff0000;
        animation: marquee 10s linear infinite;
    }

    /* Keyframes cho hiệu ứng chạy chữ */
    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>

@section('content')
<div class="container py-5">
 <!-- Banner Title with Fire Effect -->
<div class="marquee-container">
    <h2 class="marquee-text">
        🔥 {{ $banners->first()->title ?? 'Khuyến mãi & Sự kiện HOT nhất 🔥' }} 🔥
    </h2>
</div>

<!-- Banner Section -->
<div class="container p-0">
    @if(isset($banners) && $banners->count() > 0)
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
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
</div>


    
<!-- Tin tức mới nhất -->
<div class="container mt-4">
    <h3 class="text-center">Tin tức mới nhất</h3>
    <div class="row">
        @foreach ($news as $newsItem)
            <div class="col-md-4">
                <div class="card mb-3">
                    <img src="{{ asset('storage/' . $newsItem->image) }}" class="card-img-top" alt="{{ $newsItem->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $newsItem->title }}</h5>
                        <p class="card-text">{{ Str::limit($newsItem->content, 100) }}...</p>
                        <!-- Nút mở modal -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newsModal-{{ $newsItem->id }}">
                            Đọc thêm
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal hiển thị nội dung đầy đủ -->
            <div class="modal fade" id="newsModal-{{ $newsItem->id }}" tabindex="-1" aria-labelledby="newsModalLabel-{{ $newsItem->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newsModalLabel-{{ $newsItem->id }}">{{ $newsItem->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset('storage/' . $newsItem->image) }}" class="img-fluid mb-3" alt="{{ $newsItem->title }}">
                            <p>{{ $newsItem->content }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


    <!-- Đánh giá mới nhất -->
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
</div>
@endsection
