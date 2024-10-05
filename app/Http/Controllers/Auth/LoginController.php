<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->apiOutput($this->getValidationError($validator), 400);
        }

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }
            elseif ($user->role == 'user') {
                return response()->json([
                    'message' => 'Login successful',
                    'redirect_url' => route('user.dashboard')
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'errors' => ['email' => 'The provided credentials do not match our records.'],
            ], 422);
        }
        return redirect()->back()->with('error', 'The provided credentials do not match our records.');
    }



    public function checkLoginStatus()
    {
        if (Auth::check()) {
            // User is logged in, return the redirect URL based on their role
            $user = Auth::user();
            if ($user->role == 'admin') {
                return response()->json(['logged_in' => true, 'redirect_url' => route('admin.dashboard')]);
            } elseif ($user->role == 'user') {
                return response()->json(['logged_in' => true, 'redirect_url' => route('user.dashboard')]);
            }
        }

        // User is not logged in
        return response()->json(['logged_in' => false]);
    }


    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session to prevent session fixation attacks
        $request->session()->regenerateToken();

        // Redirect to your desired location after logout
        return redirect('/')->with('status', 'You have been logged out successfully.');
    }
}
