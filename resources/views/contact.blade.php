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
        <li><strong>Nama:</strong> {{ $name }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Telepon:</strong> {{ $phone }}</li>
    </ul>
    <p><a href="{{ route('home') }}">← Kembali ke Beranda</a></p>
</body>
</html>