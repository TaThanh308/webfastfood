@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Giỏ Hàng Của Bạn</h2>
            <hr>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($cart->isEmpty())
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h3 class="mb-3">Giỏ hàng của bạn đang trống</h3>
            <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Sản phẩm</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Tổng tiền</th>
                                <th class="text-center pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $item->image) }}" class="img-thumbnail" style="width: 70px; height: 70px; object-fit: cover;">
                                        <div class="ms-3">
                                            <h6 class="mb-1">{{ $item->name }}</h6>
                                            @if(isset($item->attributes) && count($item->attributes) > 0)
                                                <small class="text-muted">
                                                    @foreach($item->attributes as $key => $value)
                                                        {{ $key }}: {{ $value }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    {{ number_format($item->final_price, 0, ',', '.') }}đ
                                    @if(isset($item->original_price) && $item->original_price > $item->final_price)
                                        <small class="text-decoration-line-through text-muted d-block">{{ number_format($item->original_price, 0, ',', '.') }}đ</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm quantity-control" style="width: 120px;">
                                            <button class="btn btn-outline-secondary quantity-btn" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">-</button>
                                            <input type="number" class="form-control text-center" name="quantity" value="{{ $item->quantity }}" min="1" max="99">
                                            <button class="btn btn-outline-secondary quantity-btn" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Cập nhật</button>
                                    </form>
                                </td>
                                <td class="text-center align-middle fw-bold">
                                    {{ number_format($item->final_price * $item->quantity, 0, ',', '.') }}đ
                                </td>
                                <td class="text-center align-middle pe-4">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Tóm tắt đơn hàng</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($cart->sum(function($item) { return $item->final_price * $item->quantity; }), 0, ',', '.') }}đ</span>
                        </div>
                        @if(isset($discount) && $discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá:</span>
                            <span>- {{ number_format($discount, 0, ',', '.') }}đ</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Tổng cộng:</span>
                            <span class="fw-bold fs-5">{{ number_format($cart->sum(function($item) { return $item->final_price * $item->quantity; }) - ($discount ?? 0), 0, ',', '.') }}đ</span>
                        </div>
                
                        
                        <a href="{{ route('customer.checkout.checkout') }}" class="btn btn-primary btn-lg w-100">
                            Tiến hành thanh toán
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Script để xử lý sự kiện cập nhật số lượng tự động
    document.addEventListener('DOMContentLoaded', function() {
        // Thêm sự kiện khi sự thay đổi số lượng
        const quantityInputs = document.querySelectorAll('.quantity-control input');
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
    });
</script>
@endpush
@endsection