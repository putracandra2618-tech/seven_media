@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
    <div class="mb-4">
        <a href="{{ route('auth.login') }}" class="text-decoration-none small">
            ← Kembali ke Login
        </a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-4">
           <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <input
                    type="email"
                    name="email"
                    placeholder="Masukkan email"
                    required
                >
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
                <button type="submit">
                    Kirim Link Reset Password
                </button>
            </form>
        </div>
    </div>
@endsection