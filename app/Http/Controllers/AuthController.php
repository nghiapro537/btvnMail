<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Hiển thị form đăng ký
    public function showRegister()
    {
        return view('auth.register');
    }

    // Xử lý Đăng ký + Gửi mail chào mừng + Ghi log thời gian đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Ghi log thời gian đăng ký
        Log::info('User registered successfully', [
            'email' => $user->email,
            'registered_at' => now()
        ]);

        // Gửi mail chào mừng
        Mail::to($user->email)->send(new WelcomeMail($user));

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Hãy kiểm tra email của bạn.');
    }

    // Hiển thị form đăng nhập
    public function showLogin()
    {
        return view('auth.login');
    }

    // Xử lý Đăng nhập + Ghi log thời gian đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ghi log thời gian đăng nhập thành công
            Log::info('User logged in', [
                'email' => Auth::user()->email,
                'logged_in_at' => now()
            ]);

            return redirect()->intended('/post');
        }

        return back()->withErrors(['email' => 'Tài khoản hoặc mật khẩu không chính xác.']);
    }

    // Hiển thị form quên mật khẩu
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    // Xử lý Quên mật khẩu + Cấp lại mật khẩu mới + Ghi log thời gian đổi/cấp lại
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        // Tạo mật khẩu ngẫu nhiên mới
        $newPassword = rand(100000, 999999);
        $user->password = Hash::make($newPassword);
        $user->save();

        // Ghi log thời gian cấp lại/đổi mật khẩu
        Log::info('Password reset successfully', [
            'email' => $user->email,
            'reset_at' => now()
        ]);

        // Gửi mail cấp lại mật khẩu
        Mail::to($user->email)->send(new ForgotPasswordMail($newPassword));

        return back()->with('success', 'Mật khẩu mới đã được gửi về email của bạn!');
    }

    // Xử lý Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }
}
