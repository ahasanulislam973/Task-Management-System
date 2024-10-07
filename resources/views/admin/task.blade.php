@extends('admin.master')

@section('title', 'tasks')

@section('content')
    <div class="dashboard-card">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Task List</h4>
            <button class="btn btn-success" data-toggle="modal" data-target="#addTaskModal">Add Task</button>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Due date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm edit-task-button" data-toggle="modal"
                                data-target="#editTaskModal" data-id="{{ $task->id }}" data-title="{{ $task->title }}"
                                data-description="{{ $task->description }}" data-status="{{ $task->status }}"
                                data-due_date="{{ $task->due_date }}">
                                Edit
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addTaskForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="addtitle">Title</label>
                            <input type="text" class="form-control" id="addtitle" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="adddescription">Description</label>
                            <input type="text" class="form-control" id="adddescription" name="description" required>
                        </div>
                        <div class="form-group">
                            <label for="addstatus">Status</label>
                            <input type="text" class="form-control" id="addstatus" name="status" required>
                        </div>

                        <div class="form-group">
                            <label for="addduedate">Due Date</label>
                            <input type="date" class="form-control" id="addduedate" name="due_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editTaskForm">
                    @csrf
                    @method('PUT')
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
                            <input type="text" class="form-control" id="editdescription" name="description" required>
                        </div>

                        <div class="form-group">
                            <label for="editstatus">Status</label>
                            <input type="text" class="form-control" id="editstatus" name="status" required>
                        </div>

                        <div class="form-group">
                            <label for="editduedate">Status</label>
                            <input type="text" class="form-control" id="editduedate" name="due_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
