@extends('layouts.user')

@section('content')
<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="login-container bg-white rounded shadow-lg p-4" style="width: 400px;">
        <h2 class="text-center mb-3">Đặt lại mật khẩu</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="mb-3">
                <input type="email" name="email" class="form-control rounded-3" placeholder="Nhập email của bạn" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control rounded-3" placeholder="Mật khẩu mới" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-success w-100 rounded-pill">Đổi mật khẩu</button>
        </form>
    </div>
</div>
@endsection
