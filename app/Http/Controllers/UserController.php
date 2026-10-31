<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;   
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // 1. Lấy dữ liệu email và password từ request
        $credentials = $request->only(['email', 'password']);

        // 2. Kiểm tra thông tin đăng nhập với Auth::attempt
        if (Auth::attempt($credentials)) {
            // Tái tạo session để bảo mật chống tấn công session fixation
            $request->session()->regenerate();

            // Ghi log thông tin khi đăng nhập thành công kèm theo thông tin user hiện tại
            Log::info('login success', ['user' => Auth::user()]);

            // Chuyển hướng đến trang danh sách bài viết (hoặc trang dashboard)
            return redirect()->route('post.index');
        }

        // 3. Nếu đăng nhập thất bại, quay lại trang trước kèm theo lỗi và giữ lại input email
        return back()
            ->withErrors([
                'email' => 'Tài khoản hoặc mật khẩu không chính xác.'
            ])
            ->onlyInput('email');
    }
}
