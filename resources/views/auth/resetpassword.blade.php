@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold text-center mb-1">Reset Password</h1>
                    <p class="text-muted text-center small mb-4">Masukkan password baru untuk akun kamu</p>

                    <form action="{{ route('password.update') }}" method="POST" novalidate>
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email', $email) }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="email@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimal 8 karakter"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   placeholder="Ulangi password"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Reset Password
                        </button>

                        <p class="text-center small text-muted mb-0">
                            Sudah punya akun?
                            <a href="{{ route('auth.login') }}" class="text-decoration-none">Login di sini</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection