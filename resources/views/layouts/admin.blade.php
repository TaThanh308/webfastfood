<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bảng điều khiển Admin')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            height: 100vh;
            overflow: hidden;
            margin: 0;
        }
        .sidebar {
            height: 100%;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #ccc;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            padding: 20px;
            margin-left: 250px;
            width: calc(100% - 250px);
            overflow-y: auto;
            height: 100vh;
        }
        .top-bar {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .btn-logout {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <h2>Quản Lý</h2>
        <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('categories.index') }}"><i class="fas fa-list"></i> Danh mục</a>

        <a href="#"><i class="fas fa-utensils"></i> Sản phẩm</a>
        <a href="#"><i class="fas fa-tags"></i> Giảm Giá</a>
        <a href="#"><i class="fas fa-shopping-cart"></i> Đơn Hàng</a>
    </div>
    <div class="content">
        <div class="top-bar">
            <h4>Chào mừng, Admin</h4>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-logout" type="submit">Đăng xuất</button>
            </form>
        </div>
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>
