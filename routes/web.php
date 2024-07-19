<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'cache.headers:no_store;private;max_age=0',
])->group(function (): void {
    // 最初にアクセスした際はログインページにリダイレクト
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // ログインページ表示
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // ログイン処理
    Route::post('/login', [AuthController::class, 'login']);

    // ホームページ（認証が必要）
    Route::middleware('auth:sanctum')->get('/home', function () {
        return view('home');
    })->name('home');

    // ログアウト処理
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});