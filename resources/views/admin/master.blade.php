<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #007bff;
            color: white;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .dashboard-card {
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('user.index') }}" class="{{ request()->is('users') ? 'active' : '' }}">Users</a>
        <a href="{{ route('task') }}" class="{{ request()->is('task') ? 'active' : '' }}">Tasks</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#addUserForm').on('submit', function(event) {
            event.preventDefault();
            let formData = {
                name: $('#addUserName').val(),
                email: $('#addUserEmail').val(),
                password: $('#addUserPassword').val()
            };

            $.ajax({
                type: 'POST',
                url: "{{ route('user.store') }}",
                data: formData,
                success: function(response) {
                    $('#addUserModal').modal('hide');
                    Swal.fire('Success!', response.message, 'success');

                    $('table tbody').append(`
                        <tr>
                            <td>${response.user.name}</td>
                            <td>${response.user.email}</td>
                            <td>
                                <button class="btn btn-primary btn-sm edit-button"
                                    data-toggle="modal"
                                    data-target="#editUserModal"
                                    data-id="${response.user.id}"
                                    data-name="${response.user.name}"
                                    data-email="${response.user.email}">
                                    Edit
                                </button>

                            <button class="btn btn-danger btn-sm delete-button"
                                    data-id="${response.user.id}">
                                    Delete
                             </button>
                            </td>
                        </tr>
                    `);
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON.message, 'error');
                }
            });
        });

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

        $('#editUserForm').on('submit', function(event) {
            event.preventDefault();
            let formData = {
                id: $('#editUserId').val(),
                name: $('#editUserName').val(),
                email: $('#editUserEmail').val()
            };

            $.ajax({
                type: 'PUT',
                url: "{{ route('user.update') }}",
                data: formData,
                success: function(response) {
                    $('#editUserModal').modal('hide');
                    Swal.fire('Success!', response.message, 'success');
                    const row = $('table tbody').find(`button[data-id="${response.user.id}"]`).closest(
                        'tr');
                    row.find('td:eq(0)').text(response.user.name);
                    row.find('td:eq(1)').text(response.user.email);
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON.message, 'error');
                }
            });
        });

        $(document).on('click', '.delete-button', function() {
            const userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'DELETE',
                        url: `/users/${userId}`,
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success');
                            $('table tbody').find(`button[data-id="${userId}"]`).closest('tr')
                                .remove();
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });


        // Task

        $(document).ready(function() {
            // Add Task
            $('#addTaskForm').on('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('task.store') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#addTaskModal').modal('hide');
                        Swal.fire('Success!', response.message, 'success');
                        $('table tbody').append(`
                    <tr>
                        <td>${response.task.title}</td>
                        <td>${response.task.description}</td>
                        <td>${response.task.status}</td>
                        <td>${response.task.due_date}</td>
                        <td>${response.task.assigned_to_name ?? 'Unassigned'}</td>
                        <td>
                            <img src="{{ asset('storage/') }}/${response.task.image}" alt="Task Image" width="50" height="50">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm edit-task-button"
                                data-toggle="modal" data-target="#editTaskModal"
                                data-id="${response.task.id}"
                                data-title="${response.task.title}"
                                data-description="${response.task.description}"
                                data-status="${response.task.status}"
                                data-due_date="${response.task.due_date}"
                                data-assigned_to="${response.task.assigned_to}"
                                data-image="${response.task.image}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-task-button" data-id="${response.task.id}">Delete</button>
                        </td>
                    </tr>
                `);
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message, 'error');
                    }
                });
            });

            // Edit Task
            $(document).on('click', '.edit-task-button', function() {
                const id = $(this).data('id');
                const title = $(this).data('title');
                const description = $(this).data('description');
                const status = $(this).data('status');
                const dueDate = $(this).data('due_date');
                const assignedTo = $(this).data('assigned_to');
                const image = $(this).data('image');

                $('#editTaskId').val(id);
                $('#edittitle').val(title);
                $('#editdescription').val(description);
                $('#editstatus').val(status);
                $('#editduedate').val(dueDate);
                $('#editassigned_to').val(assignedTo);
                $('#currentImage').attr('src', "{{ asset('storage/') }}" + '/' + image);
            });

            // Update Task
            $('#editTaskForm').on('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('task.update') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#editTaskModal').modal('hide');
                        Swal.fire('Success!', response.message, 'success');
                        location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message, 'error');
                    }
                });
            });

            // Delete Task
            $(document).on('click', '.delete-task-button', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: `/tasks/${id}`,
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success');
                                $(this).closest('tr').remove();
                            }.bind(this),
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>