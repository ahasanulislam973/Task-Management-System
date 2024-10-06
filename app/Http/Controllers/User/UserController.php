<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $messages = [
            'email.required' => 'The email field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters.',
            'password.max' => 'The password may not be greater than :max characters.',
            'name.required' => 'The name field is required.',
        ];

        $validator = Validator::make($request->all(), [
            "email"     => ["required"],
            "password"  => ["required", "string", "min:4", "max:40"],
            "name"      => ["required"]
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => 'User registered successfully!'], 201);
    }


    public function index()
    {
        $totalTasks = 10;

        $user = Auth::user();

        $name = $user->name;

        return view('user.dashboard', compact('name'));
    }
}
