@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Chi tiết đơn hàng #{{ $order->id }}</h2>

    <!-- Thông tin chung -->
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Khách hàng:</strong> {{ $order->user->name ?? 'Khách vãng lai' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone_number ?? 'Không có sẵn' }}</p>
<p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address ?? 'Không có sẵn' }}</p>
            <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái thanh toán:</strong> 
                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                    {{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                </span>
            </p>
        </div>
    </div>

    <!-- Danh sách sản phẩm -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Sản phẩm trong đơn hàng</h5>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Giá</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                    <td>{{ $item->product->name ?? 'Không có tên sản phẩm' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">{{ number_format($item->price, 0, ',', '.') }} VND</td>
                        <td class="text-end">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VND</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cập nhật trạng thái -->
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Cập nhật trạng thái đơn hàng</h5>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <select name="status" class="form-select">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Hủy</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection
