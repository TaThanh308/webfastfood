@extends('layouts.user')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4 text-uppercase fw-bold">Danh Sách Sản Phẩm</h1>

    <div class="row">
        @foreach ($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <a href="{{ route('customer.products.show', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    </a>
                    <div class="card-body text-center">
                        <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                            <h5 class="fw-bold">{{ $product->name }}</h5>
                        </a>
                        <p class="fw-bold text-danger fs-5">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
