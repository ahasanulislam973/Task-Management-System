@extends('admin.master')

@section('title', 'Users')

@section('content')
<div class="dashboard-card">
    <div class="d-flex justify-content-between align-items-center">
        <h4>User List</h4>
        <button class="btn btn-success" data-toggle="modal" data-target="#addUserModal">Add User</button>
    </div>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <button class="btn btn-primary btn-sm edit-button"
                        data-toggle="modal"
                        data-target="#editUserModal"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-email="{{ $user->email }}">
                        Edit
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="addUserName">Name</label>
                        <input type="text" class="form-control" id="addUserName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="addUserEmail">Email</label>
                        <input type="email" class="form-control" id="addUserEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="addUserPassword">Password</label>
                        <input type="password" class="form-control" id="addUserPassword" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editUserId">
                    <div class="form-group">
                        <label for="editUserName">Name</label>
                        <input type="text" class="form-control" id="editUserName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" name="email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    
    $('#editUserModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); 
        var userId = button.data('id'); 
        var userName = button.data('name');
        var userEmail = button.data('email');
        var modal = $(this);
        modal.find('#editUserId').val(userId);
        modal.find('#editUserName').val(userName);
        modal.find('#editUserEmail').val(userEmail);
    });
</script>
@endsection
