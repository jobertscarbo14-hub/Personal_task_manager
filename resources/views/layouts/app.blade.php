<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Personal Task Manager') | Personal Task Manager</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('tasks.index') }}">
                <span class="brand-mark">PT</span>
                <span>Personal Task Manager</span>
            </a>
            <a class="button button-primary topbar-button" href="{{ route('tasks.create') }}">+ Add Task</a>
        </div>
    </header>

    <main class="page-shell">
        @if (session('success'))
            <div class="notice" role="status">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">Personal Task Manager <span>|</span> WST21-PM-2026-SF</footer>
</body>
</html>