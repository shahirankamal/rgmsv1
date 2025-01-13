<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --sidebar-width: 250px;
            --dark-blue: #0d47a1;
            --darker-blue: #072c6b;
            --light-blue: #1976d2;
            --header-height: 60px;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            width: var(--sidebar-width);
            background: var(--dark-blue);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }

        #sidebar.collapsed {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        #content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
            position: relative;
            background: #f8f9fa;
        }

        #content.expanded {
            margin-left: 0;
        }

        .sidebar-brand {
            padding: 1rem;
            color: white;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            height: var(--header-height);
            display: flex;
            align-items: center;
        }

        .sidebar-nav {
            padding: 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: var(--darker-blue);
            color: white;
        }

        .sidebar-nav .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .top-navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: var(--header-height);
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }

        .user-dropdown .dropdown-toggle {
            color: var(--dark-blue);
            text-decoration: none;
        }

        .main-content {
            padding: 1rem;
            min-height: calc(100vh - var(--header-height));
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            #content {
                margin-left: 0;
            }
            
            #sidebar.active {
                margin-left: 0;
            }

            .top-navbar {
                padding-left: 1rem;
            }
        }

        /* Guest pages (login, register, etc.) */
        body.guest-page {
            background: #f8f9fa;
        }

        body.guest-page #sidebar,
        body.guest-page .top-navbar {
            display: none;
        }

        body.guest-page #content {
            margin-left: 0;
        }
    </style>
</head>
<body class="{{ !auth()->check() ? 'guest-page' : '' }}">
    <div class="wrapper">
        @auth
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('home') }}" class="text-decoration-none text-white">
                    Research Grant Management System
                </a>
            </div>
            
            <ul class="sidebar-nav mt-3">
                @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('academicians.*') ? 'active' : '' }}" 
                           href="{{ route('academicians.index') }}">
                            <i class="bi bi-people"></i> Academicians
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('researchgrants.*') ? 'active' : '' }}" 
                           href="{{ route('researchgrants.index') }}">
                            <i class="bi bi-file-text"></i> Research Grants
                        </a>
                    </li>
                @endif
                
                @if(auth()->user()->isProjectLeader())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" 
                           href="{{ route('projects.index') }}">
                            <i class="bi bi-folder"></i> My Projects
                        </a>
                    </li>
                @endif
                
                @if(auth()->user()->academician)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('projectmember.*') ? 'active' : '' }}" 
                           href="{{ route('projectmember.index') }}">
                            <i class="bi bi-person-badge"></i> Project Member
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        @endauth

        <!-- Page Content -->
        <div id="content" class="{{ !auth()->check() ? 'expanded' : '' }}">
            @auth
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <button id="sidebarCollapse" class="btn btn-link text-dark d-md-none">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="ms-auto">
                    <div class="dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            @endauth

            <div class="main-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarCollapse = document.getElementById('sidebarCollapse');

            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>
