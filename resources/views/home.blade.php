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
