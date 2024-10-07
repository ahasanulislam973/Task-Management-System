@extends('user.master')

@section('title', 'tasks')

@section('content')
<div class="dashboard-card">

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Assigned To</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->status }}</td>
                <td>{{ $task->due_date }}</td>
                <td>{{ $task->assignedUser->name ?? 'Unassigned' }}</td>
                <td>
                    @if($task->image)
                    <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" width="50" height="50">
                    @else
                    No Image
                    @endif
                </td>
                <td>
                    <button class="btn btn-success btn-sm edit-task-button" data-toggle="modal"
                        data-target="#editTaskModal" data-id="{{ $task->id }}" data-title="{{ $task->title }}"
                        data-description="{{ $task->description }}"
                        data-due_date="{{ $task->due_date }}"
                        data-image="{{ $task->image }}">
                        Finish
                    </button>
                    <button class="btn btn-danger btn-sm delete-task-button" data-id="{{ $task->id }}">
                        Delete
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editTaskForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editTaskId" name="id">
                    <div class="form-group">
                        <label for="edittitle">Title</label>
                        <input type="text" class="form-control" id="edittitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="editdescription">Description</label>
                        <textarea class="form-control" id="editdescription" name="description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="editimage">Upload Image</label>
                        <input type="file" class="form-control" id="editimage" name="image" accept="image/*">
                    </div>
                    <div id="currentImageContainer" class="mt-2">
                        <strong>Current Image:</strong>
                        <img id="currentImage" src="" alt="Current Task Image" width="50" height="50">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Finish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection