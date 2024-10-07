<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserTaskController extends Controller
{
    public function list()
    {

        $user = Auth::guard('user')->user();
        $tasks = Task::where('assigned_to', $user->id)->get();
        $users = User::all();
        return view('user.task', compact('tasks', 'users'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $task = Task::find($request->id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->status = 'Completed';
        $task->completed_at = now();

        if ($request->hasFile('image')) {
            if ($task->image) {
                Storage::disk('public')->delete($task->image);
            }
            $task->image = $request->file('image')->store('tasks', 'public');
        }


        $task->save();

        return response()->json(['task' => $task, 'message' => 'Task updated successfully!']);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.']);
    }
}
