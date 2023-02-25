<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function index()
    {
        # code...
        return view('admin.users.login', ['title' => "Đăng nhập hệ thống"]);
    }
    public function store(Request $request)
    {

        $request->validate([
            'email' => 'required|email:filter',
            'password' => 'required'
        ]);

        //Auth => config/auth, này sẽ truy data bảng user xem có khơp không
        if (
            Auth::attempt(
                [
                    'email' => $request->input('email'),
                    'password' => $request->input('password'),
                    // 'level'=> 1 // này để dùng phân quyền admin
                ], $request->input('remember')
            )
        ) {
            return redirect()->route('admin');
        }
        $request->session()->flash('error', 'email hoặc mật khẩu không đúng');
        return redirect()->back();
        # code...
        // return view('admin.users.login', ['title' => "Đăng nhập hệ thống"]);
    }
}