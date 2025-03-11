@extends('layouts.user')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 rounded-4" style="max-width: 450px; width: 100%;">
        <h2 class="text-center mb-4 text-primary">Đổi Mật Khẩu</h2>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <form action="{{ route('password.change') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">🔑 Mật khẩu hiện tại</label>
                <input type="password" name="current_password" class="form-control rounded-pill" required>
                @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">🔒 Mật khẩu mới</label>
                <input type="password" name="new_password" class="form-control rounded-pill" required>
                @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">✔️ Xác nhận mật khẩu mới</label>
                <input type="password" name="new_password_confirmation" class="form-control rounded-pill" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-pill">Cập nhật mật khẩu</button>
        </form>
    </div>
</div>

<style>
    body {
        background: linear-gradient(to right, #ff758c, #ff7eb3);
        font-family: 'Arial', sans-serif;
    }

    .card {
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        padding: 20px;
    }

    h2 {
        font-weight: bold;
    }

    .form-control {
        border-radius: 30px;
        padding: 10px 15px;
        font-size: 16px;
    }

    .btn-primary {
        background-color: #ff5a5f;
        border: none;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #e0484d;
        transform: scale(1.05);
    }
</style>
@endsection
