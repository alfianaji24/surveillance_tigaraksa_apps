<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'id_user' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check if login with username or email
        $loginField = filter_var($request->id_user, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $request->merge([$loginField => $request->id_user]);

        $credentials = $request->only($loginField, 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect based on user role
            if ($user->hasRole('Super Admin')) {
                return redirect()->intended(route('dashboard'));
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'id_user' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('id_user', 'remember'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        return view('dashboard', compact('user'));
    }
}
