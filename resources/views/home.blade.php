<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>{{ $tagline }}</p>
    @if (isset($features))
        <ul>
            @foreach ($features as $feature)
                <li>{{ $feature }}</li>
            @endforeach
        </ul>
    @endif
    <p><a href="{{ route('about') }}">Tentang Aplikasi →</a></p>
    <p><a href="{{ route('contact') }}">Kontak →</a></p>
</body>
</html>