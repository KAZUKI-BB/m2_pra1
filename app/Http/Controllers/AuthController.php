<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 新しいコントローラを作成する宣言
class AuthController extends Controller
{
    // showLoginForm関数の宣言
    public function showLoginForm()
    {
        // ログインページに遷移
        return view('login');
    }

    // login関数の宣言,$requestにRequestオブジェクトを格納
    public function login(Request $request)
    {
        // $credentialsにemailとpasswordを保存
        $credentials = $request->only('email', 'password');

        // Auth::attempt($credentials)でemailとpasswordが正しいかチェック
        if (Auth::attempt($credentials)) {
            // $userにユーザー情報を保存
            $user = Auth::user();
            // 新しい認証トークンの生成と$tokenへの保存
            $token = $user->createToken('auth_token')->plainTextToken;
            // $requestのsessionに認証トークンを保存
            $request->session()->put('auth_token', $token);
            // homeに遷移
            return redirect()->route('home');
        }

        // ログイン失敗時にエラーメッセージを表示してログインページに戻る
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // logout関数の宣言,$requestにRequestオブジェクトを格納
    public function logout(Request $request)
    {
        // $requestに含まれるuserのトークンをすべて削除
        $request->user()->tokens()->delete();
        // ユーザーをログアウト
        Auth::logout();
        // セッションからトークン情報を削除
        $request->session()->forget('auth_token');
        // ログインページに遷移
        return redirect()->route('login');
    }
}
