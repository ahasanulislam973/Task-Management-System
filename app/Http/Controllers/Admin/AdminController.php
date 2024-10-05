<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function showLogin()
    {
        $admin = Auth::user();
        if ($admin) {
            if ($admin->role == 'admin') {
                return redirect()->route('admin.dashboard');
            }
        } else {
            return view('admin.login');
        }
    }


    public function index()
    {
        return view('admin.dashboard');
    }


    public function userList()
    {
        $users = User::all();
        return view('admin.user', compact('users'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Create the user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        // Return the created user as JSON response
        return response()->json(['user' => $user, 'message' => 'User added successfully!']);
    }


    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
        ]);

        // Find the user and update it
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Return the updated user as JSON response
        return response()->json(['user' => $user, 'message' => 'User updated successfully!']);
    }
}
