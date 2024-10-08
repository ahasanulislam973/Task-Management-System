@extends('admin.master')

@section('title', 'tasks')

@section('content')
    <div class="dashboard-card">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Task List</h4>
            <button class="btn btn-success" data-toggle="modal" data-target="#addTaskModal">Add Task</button>
        </div>


        <form method="GET" action="{{ route('task') }}" class="mt-3 mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="filter_status">Filter by Status</label>
                    <select class="form-control" id="filter_status" name="status">
                        <option value="">-- Select Status --</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress
                        </option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter_due_date">Filter by Due Date</label>
                    <input type="date" class="form-control" id="filter_due_date" name="due_date"
                        value="{{ request('due_date') }}">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('task') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

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
                            @if ($task->image)
                                <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" width="50"
                                    height="50">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm edit-task-button" data-toggle="modal"
                                data-target="#editTaskModal" data-id="{{ $task->id }}"
                                data-title="{{ $task->title }}" data-description="{{ $task->description }}"
                                data-status="{{ $task->status }}" data-due_date="{{ $task->due_date }}"
                                data-assigned_to="{{ $task->assigned_to }}" data-image="{{ $task->image }}">
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

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="addTaskForm" enctype="multipart/form-data">
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
                            <textarea class="form-control" id="adddescription" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="addstatus">Status</label>
                            <select class="form-control" id="addstatus" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="addduedate">Due Date</label>
                            <input type="date" class="form-control" id="addduedate" name="due_date" required>
                        </div>
                        <div class="form-group">
                            <label for="addassigned_to">Assigned To</label>
                            <select class="form-control" id="addassigned_to" name="assigned_to">
                                <option value="">-- Select User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="addimage">Upload Image</label>
                            <input type="file" class="form-control" id="addimage" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Task</button>
                    </div>
                </form>
            </div>
        </div>
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
                            <label for="editstatus">Status</label>
                            <select class="form-control" id="editstatus" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editduedate">Due Date</label>
                            <input type="date" class="form-control" id="editduedate" name="due_date" required>
                        </div>
                        <div class="form-group">
                            <label for="editassigned_to">Assigned To</label>
                            <select class="form-control" id="editassigned_to" name="assigned_to">
                                <option value="">-- Select User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editimage">Upload Image</label>
                            <input type="file" class="form-control" id="editimage" name="image" accept="image/*">
                        </div>
                        <div id="currentImageContainer" class="mt-2">
                            <strong>Current Image:</strong>
                            <img id="currentImage" src="" alt="Current Task Image" width="50"
                                height="50">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
