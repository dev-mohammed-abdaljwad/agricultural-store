<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'governorate' => 'required|string',
            'address' => 'required|string',
            'customer_type' => 'required|in:farmer,trader',
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'customer_type' => $validated['customer_type'],
            'governorate' => $validated['governorate'],
            'address' => $validated['address'],
            'status' => 'active',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'تم إنشاء الحساب بنجاح! أهلاً وسهلاً بك.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
