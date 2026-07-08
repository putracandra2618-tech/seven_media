@extends('layouts.app')

@section('title', $title)

@section('content')
    <h1 class="h2 fw-bold mb-4">{{ $title }}</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="text-muted"> Hubungi kami untuk informasi lebih lanjut.</p>  
    <p>
        <strong>Nama:</strong> {{ $name }}
    </p>
    <p>
        <strong>Email:</strong> {{ $email }}
    </p>
    <p>
        <strong>Telepon:</strong> {{ $phone }}
    </p>
    <p><a href="{{ route('home') }}">← Kembali ke Beranda</a></p>
@endsection