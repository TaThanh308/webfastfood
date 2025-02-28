<?php

use Illuminate\Support\Facades\Route;
// Import AuthController
use App\Http\Controllers\AuthController;

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

Route::get('/', function () {
    return view('welcome');
})->name('home');


// Đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
