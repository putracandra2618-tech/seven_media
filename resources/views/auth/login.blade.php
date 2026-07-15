@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold text-center mb-1">Login</h1>
                    <p class="text-muted text-center small mb-4">Masuk ke Task Manager</p>

                    <form action="{{ url('/login') }}" method="POST" novalidate>
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="email@example.com"
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Password kamu">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Remember me --}}
                        <div class="mb-4 form-check">
                            <input type="checkbox"
                                   name="remember"
                                   id="remember"
                                   class="form-check-input"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="form-check-label">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            Login
                        </button>

                        <p class="text-center small text-muted mb-0">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>
                        </p>

                        <p class="text-center small text-muted mb-0">
                            Lupa password?
                            <a href="{{ route('auth.forgotpassword') }}" class="text-decoration-none">Reset di sini</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection