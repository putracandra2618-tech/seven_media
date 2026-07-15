@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">
                        Profile
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>Terdaftar pada:</strong> {{ auth()->user()->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
