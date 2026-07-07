<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <ul>
        <li><strong>Aplikasi:</strong> {{ $appName }}</li>
        <li><strong>Versi:</strong> {{ $version }}</li>
        <li><strong>Pembuat:</strong> {{ $author }}</li>
    </ul>
    <p>{{ $description }}</p>
    <p><a href="{{ route('home') }}">← Kembali ke Beranda</a></p>
</body>
</html>