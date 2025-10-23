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
        // Redirect to dashboard if already authenticated
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('signin');
    }

    public function login(Request $request)
    {
        // Validate input with strict rules
        $credentials = $request->validate([
            'email' => 'required|email:rfc,dns|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        // Rate limiting key based on email and IP
        $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();

        // Check if too many login attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
            ]);
        }

        // Sanitize email (convert to lowercase and trim)
        $credentials['email'] = strtolower(trim($credentials['email']));

        // Attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);
            
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();
            
            // Redirect to intended page or dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($throttleKey, 60); // Lock for 60 seconds after 5 attempts

        // Return error with input (except password)
        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email'));
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        // Redirect to dashboard if already authenticated
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('signup');
    }

    public function register(Request $request)
    {
        // Validate with strict rules
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc,dns|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ], [
            'name.regex' => 'The name may only contain letters and spaces.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ]);

        // Sanitize inputs
        $validated['name'] = trim(strip_tags($validated['name']));
        $validated['email'] = strtolower(trim($validated['email']));

        // Create user with sanitized data
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        // Login the user
        Auth::login($user);
        
        // Regenerate session
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show the user dashboard
     */
    public function dashboard()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Ensure user is authenticated
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Please login to access the dashboard.']);
        }
        
        // Get user's adoption requests with pet details
        $adoptionRequests = $user->adoptionRequests()
            ->with('pet')
            ->latest()
            ->get();
        
        return view('dashboard', compact('user', 'adoptionRequests'));
    }
}
