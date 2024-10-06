<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminUserController extends Controller
{
    public function showLogin()
    {
        $admin = Auth::guard('admin')->user();
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
        $users = User::where('role','user')->get();
        return view('admin.user', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['user' => $user, 'message' => 'User added successfully!']);
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
        ]);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['user' => $user, 'message' => 'User updated successfully!']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
