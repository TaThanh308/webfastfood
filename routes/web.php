<?php

use Illuminate\Support\Facades\Route;
// Import AuthController
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\NewsController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductDiscountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DiscountProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('home');})->name('home');


// Đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('auth/{provider}', function ($provider) {
    return Socialite::driver($provider)->redirect();
})->where('provider', 'google|facebook')->name('social.login');

Route::get('auth/{provider}/callback', function ($provider) {
    $socialUser = Socialite::driver($provider)->user();

    $user = User::updateOrCreate([
        'email' => $socialUser->getEmail(),
    ], [
        'name' => $socialUser->getName(),
        'social_id' => $socialUser->getId(),
        'social_provider' => $provider,
        'password' => bcrypt('password') // Mặc định đặt mật khẩu (có thể thay đổi sau)
    ]);

    Auth::login($user);

    return redirect()->route('home');
})->where('provider', 'google|facebook');

// Trang quản trị dành cho admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Trang chính cho khách hàng
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
});



// Nhóm route dành cho admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::resource('admin/products', ProductController::class);
    Route::resource('news', NewsController::class);
    Route::resource('banners', BannerController::class);
    Route::resource('admin/discounts', ProductDiscountController::class);
});



Route::get('/products', [ProductUserController::class, 'index'])->name('productsUser.index');
Route::get('/products/{id}', [ProductUserController::class, 'show'])->name('products.show');

Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('customer.cart.index');
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

});


Route::get('/discounts', [DiscountProductController::class, 'index'])->name('customer.discounts.index');
Route::get('/discounted-products/{id}', [DiscountProductController::class, 'show'])->name('products.discounted.show');
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'reset'])->name('password.update');