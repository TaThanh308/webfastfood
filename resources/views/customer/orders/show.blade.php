@extends('layouts.user')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Tiêu đề -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Chi tiết đơn hàng #{{ $order->id }}</h2>
                <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'processing' ? 'info' : ($order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'secondary'))) }} fs-6">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-2"><strong>Phương thức thanh toán:</strong> {{ $order->payment_method ?? 'Thanh toán khi nhận hàng' }}</p>
                    <p class="mb-2"><strong>Trạng thái thanh toán:</strong> 
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Thông tin người nhận -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông tin người nhận</h5>
                </div>
                <div class="card-body">
                    <p><strong>Họ tên:</strong> {{ $order->customer_name ?? auth()->user()->name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->phone_number }}</p>
                    <p><strong>Email:</strong> {{ $order->email ?? auth()->user()->email }}</p>
                    <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Danh sách sản phẩm</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(isset($item->product->image))
                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                        @endif
                                        <div class="ms-3">
                                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                                            @if(isset($item->product_options) && !empty($item->product_options))
                                            <small class="text-muted">
                                                @foreach(json_decode($item->product_options, true) ?? [] as $key => $value)
                                                    {{ $key }}: {{ $value }}@if(!$loop->last), @endif
                                                @endforeach
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    {{ number_format($item->price, 0, ',', '.') }} VND
                                </td>
                                <td class="text-center align-middle">
                                    {{ $item->quantity }}
                                </td>
                                <td class="text-end align-middle fw-bold">
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tổng kết đơn hàng -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Tổng kết đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span>{{ number_format($order->subtotal ?? $order->total_price, 0, ',', '.') }} VND</span>
                            </div>
                            @if(isset($order->discount) && $order->discount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Giảm giá:</span>
                                <span>- {{ number_format($order->discount, 0, ',', '.') }} VND</span>
                            </div>
                            @endif
                            @if(isset($order->shipping_fee) && $order->shipping_fee > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }} VND</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-0">
                                <span class="fw-bold">Tổng cộng:</span>
                                <span class="fw-bold fs-5">{{ number_format($order->total_price, 0, ',', '.') }} VND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nút điều hướng -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('orders.my') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách đơn hàng
                </a>
                @if($order->status == 'pending' || $order->status == 'processing')
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-times-circle me-2"></i>Hủy đơn hàng
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
