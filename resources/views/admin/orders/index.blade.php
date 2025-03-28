 @extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Quản lý đơn hàng</h2>

    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th class="text-center">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ number_format($order->total_price, 0, ',', '.') }} VND</td>
                <td>
                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'processing' ? 'info' : ($order->status == 'completed' ? 'success' : 'danger')) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Xem
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
</div>
@endsection
