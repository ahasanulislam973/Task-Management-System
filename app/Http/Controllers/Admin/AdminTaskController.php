<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminTaskController extends Controller
{
    public function list(Request $request)
    {
        $users = User::where('role', 'user')->get();

        $query = Task::query();

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('due_date') && !empty($request->due_date)) {
            $query->whereDate('due_date', $request->due_date);
        }
        $tasks = $query->get();
        return view('admin.task', compact('tasks', 'users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->assigned_to = $request->assigned_to;
        $task->started_at = now();

        if ($request->hasFile('image')) {
            $task->image = $request->file('image')->store('tasks', 'public');
        }
        $task->save();

        return response()->json(['task' => $task, 'message' => 'Task added successfully!']);
    }


    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
        ]);

        $task = Task::find($request->id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->due_date = $request->due_date;
        $task->assigned_to = $request->assigned_to;
        $task->started_at = now();


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
