<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Research Grants Management System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-image: url('/images/buildingplain.jpg'); /* Replace with your image path */
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0;
            }
            .card {
                background: rgba(255, 255, 255, 0.95);
                border: none;
                box-shadow: 0 8px 32px rgba(13, 71, 161, 0.1);
            }
            .btn-primary {
                background-color: #0d47a1;
                border-color: #0d47a1;
            }
            .btn-primary:hover {
                background-color: #1976d2;
                border-color: #1976d2;
            }
            .welcome-text {
                background: linear-gradient(45deg, #0d47a1, #1976d2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
        </style>
    </head>
    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card p-5 text-center">
                        <h1 class="display-4 fw-bold mb-4 welcome-text">Welcome to Research Grants Management System</h1>
                        <p class="lead text-muted mb-5">Streamline your research grant applications and management process</p>
                        
                        @if (Route::has('login'))
                            <div class="d-grid gap-3 d-md-flex justify-content-center">
                                @auth
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-5">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
