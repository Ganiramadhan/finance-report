<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function dologin(Request $request)
    {
        // validasi
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {

            // buat ulang session login
            $request->session()->regenerate();

            if (auth()->user()->role_id === 1) {
                // jika user superadmin

                return redirect()->intended('/admin')->with('success', 'Selamat datang ' . auth()->user()->name . ', Anda berhasil login');
            } else {
                // jika user pegawai
                return redirect()->intended('/user');
            }
        }

        // jika email atau password salah
        // kirimkan session error
        return back()->with('error', 'Email atau password salah');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
