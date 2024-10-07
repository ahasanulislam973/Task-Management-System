<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
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
        $highestSolver = User::withCount(['tasks' => function ($query) {
            $query->where('status', 'Completed');
        }])
        ->where('role', 'user')
        ->orderBy('tasks_count', 'desc')
        ->first();

        $solvingTimes = Task::with('assignedUser')
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->selectRaw('assigned_to, SUM(TIMESTAMPDIFF(SECOND, started_at, completed_at)) as total_seconds')
            ->groupBy('assigned_to')
            ->get();
    
        $solvingTimes->each(function ($item) {
            $item->total_time_formatted = gmdate('H:i:s', $item->total_seconds);
        });
    
        return view('admin.dashboard', compact('highestSolver', 'solvingTimes'));
    }


    public function userList()
    {
        $users = User::where('role', 'user')->get();
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
