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
            if ($user->role == 'admin') {
                Auth::guard('admin')->login($user);
                return redirect()->route('admin.dashboard');
            } elseif ($user->role == 'user') {
                Auth::guard('user')->login($user);
                return response()->json([
                    'message' => 'User login successful',
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
            $admin = Auth::guard('admin')->user();
            if ($admin) {
                return response()->json(['logged_in' => true, 'redirect_url' => route('admin.dashboard')]);
            } else {
                return response()->json(['logged_in' => true, 'redirect_url' => route('user.dashboard')]);
            }
        }
        return response()->json(['logged_in' => false]);
    }


    public function logout()
    {

        $admin = Auth::guard('admin')->user();
        if ($admin) {
            Auth::guard('admin')->logout();

            return redirect('admin/login');
        } else {
            Auth::guard('user')->logout();
            return redirect('/');
        }
    }
}
