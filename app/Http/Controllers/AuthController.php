<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('signin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email:rfc,dns|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
            ]);
        }

        $credentials['email'] = strtolower(trim($credentials['email']));

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email'));
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('signup');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc,dns|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'name.regex' => 'The name may only contain letters and spaces.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ]);

        $user = User::create([
            'name' => trim(strip_tags($validated['name'])),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Please login to access the dashboard.']);
        }
        
        if ($user->isAdmin()) {
            $allRequests = \App\Models\AdoptionRequest::with(['user', 'pet'])->latest()->get();
            $adoptionRequests = collect();
            
            return view('dashboard', compact('user', 'adoptionRequests', 'allRequests'));
        }
        
        $adoptionRequests = $user->adoptionRequests()->with('pet')->latest()->get();
        
        return view('dashboard', compact('user', 'adoptionRequests'));
    }
}
