<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <button class="nav-link btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#registerModal">Sign Up</button>
                    </li>
                    <li class="nav-item">
                    <button class="nav-link btn btn-success btn-login">Login</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="text-center">
            <h1>Welcome to the Task Management System</h1>
        </div>
    </div>

    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="w-100 text-center">
                        <h5 class="modal-title" id="registerModalLabel">User Sign Up</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-submit btn-md">Sign Up</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    Already have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Login here</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="w-100 text-center">
                        <h5 class="modal-title" id="loginModalLabel">User Login</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-submit btn-md">Login</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    Don't have an account? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Register here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .btn-submit {
            background-color: #005364;
            color: #fff;
        }

        .btn-submit:hover {
            background-color: #1f3c42;
            color: #fff;
        }

        .modal-header {
            display: flex;
            justify-content: center;
        }

        .modal-title {
            margin: 0;
        }
    </style>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route("register") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.message) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#registerModal').modal('hide');
                                    $('#registerForm')[0].reset();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            const errorMessages = Object.values(errors).flat().join('<br>');
                            Swal.fire({
                                title: 'Error!',
                                html: errorMessages,
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            });
                        }
                    }
                });
            });

            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route("login") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.message) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirect to the dashboard or the appropriate URL
                                    window.location.href = data.redirect_url;
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            const errorMessages = Object.values(errors).flat().join('<br>');
                            Swal.fire({
                                title: 'Error!',
                                html: errorMessages,
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                                icon: 'error',
                                confirmButtonText: 'Okay'
                            });
                        }
                    }
                });
            });

            $('#registerModal').on('show.bs.modal', function() {
                $('#registerForm')[0].reset();
            });

            $('#loginModal').on('show.bs.modal', function() {
                $('#loginForm')[0].reset();
            });
        });



        $(document).ready(function() {
    // Login button click handler
    $('.btn-login').on('click', function() {
        // Check if the user is logged in
        $.ajax({
            url: '{{ route("checkLoginStatus") }}', // A route to check login status
            type: 'GET',
            success: function(data) {
                if (data.logged_in) {
                    // User is logged in, redirect to dashboard
                    window.location.href = data.redirect_url;
                } else {
                    // User is not logged in, show login modal
                    $('#loginModal').modal('show');
                }
            },
            error: function() {
                // In case of error, assume user is not logged in and show the login modal
                $('#loginModal').modal('show');
            }
        });
    });
});


    </script>

</body>

</html>