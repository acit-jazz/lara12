<?php

namespace App\Http\Controllers\AuthAdmin;


use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AuthAdminController extends Controller
{
    /**
     * Display admin login view
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {


        return Inertia::render('auth/Login', [
            'status' => session('status'),
            'canResetPassword' => Route::has('password.request'),
        ]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
            // Ambil kredensial dari request
            $credentials = $request->only('email', 'password');
            // Coba login menggunakan guard 'admin'
            if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended(route('dashboard', absolute: false));
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
    }



    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
