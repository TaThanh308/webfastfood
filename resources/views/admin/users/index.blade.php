@extends('layouts.admin')

@section('title', 'Danh sách tài khoản')

@section('content')
<div class="container">
    <h3 class="text-center">📋 Danh sách tài khoản</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Quyền</th>
                <th>Ngày tạo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role === 'admin')
                        <span class="badge badge-danger">Admin</span>
                    @else
                        <span class="badge badge-primary">User</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
