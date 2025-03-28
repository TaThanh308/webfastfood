@extends('layouts.user')

@section('content')

<style>
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>

<div class="container">
    <h2 class="my-4">Đơn hàng của tôi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
    @else
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ number_format($order->total_price, 0, ',', '.') }} VND</td>
                    <td><span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($order->status) }}</span></td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">Xem chi tiết</a>

                        @if ($order->status === 'pending')
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">Hủy đơn</button>
                            </form>
                        @endif

                        @if ($order->status === 'completed' && !$order->reviews->where('user_id', auth()->id())->count())
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal-{{ $order->id }}">
                                Đánh giá
                            </button>
                        @endif
                    </td>
                </tr>
<!-- Hiển thị phân trang nếu có -->
          
              <!-- Hiển thị phân trang -->
             
                <!-- Modal Đánh Giá -->
                <div class="modal fade" id="reviewModal-{{ $order->id }}" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewModalLabel">Đánh giá sản phẩm</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('reviews.store', $order->id) }}" method="POST" onsubmit="submitButton.disabled = true; return true;">
                                    @csrf
                                    <label for="rating">Chọn số sao:</label>
                                    <select name="rating" class="form-control mb-2" required>
                                        <option value="5">5 sao</option>
                                        <option value="4">4 sao</option>
                                        <option value="3">3 sao</option>
                                        <option value="2">2 sao</option>
                                        <option value="1">1 sao</option>
                                    </select>

                                    <label for="comment">Nhận xét:</label>
                                    <textarea name="comment" class="form-control mb-3" rows="3"></textarea>

                                    <button type="submit" name="submitButton" class="btn btn-primary">Gửi đánh giá</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
<div class="d-flex justify-content-center mt-3">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
@endsection