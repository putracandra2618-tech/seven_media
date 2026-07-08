<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager')</title>

    {{-- Bootstrap 5 CSS via CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Konten utama --}}
    <main class="container py-4 flex-grow-1">
        @include('partials.alert')
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center text-muted py-3 border-top mt-auto">
        <small>&copy; {{ date('Y') }} Task Manager — Built with Laravel & Bootstrap</small>
    </footer>

    {{-- Bootstrap 5 JS via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>