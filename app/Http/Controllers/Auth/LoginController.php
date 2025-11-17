<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        // Redirect jika sudah login
        if (Auth::check()) {
            return redirect()->route('DbUpt');
        }

        return view('auth.login');
    }

    /**
     * Proses login user
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Cari user berdasarkan username
        $user = UserRole::where('username', $credentials['username'])->first();

        // Validasi user existence & password
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        // Validasi status user
        if (! $user->isActive()) {
            throw ValidationException::withMessages([
                'username' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ]);
        }

        // Login user
        Auth::login($user, $request->filled('remember'));

        // Update last login timestamp
        $user->update(['last_login_at' => now()]);

        // Regenerate session untuk security
        $request->session()->regenerate();

        // Redirect berdasarkan role (opsional)
        return redirect()->intended(route('GrafikClient'));
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
