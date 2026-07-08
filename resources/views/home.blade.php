@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="text-center py-5">
        <h1 class="display-5 fw-bold mb-3">{{ $title }}</h1>
        <p class="lead text-muted mb-4">{{ $tagline }}</p>

        <a href="{{ route('about') }}" class="btn btn-primary btn-lg">
            Pelajari Lebih Lanjut
        </a>
    </div>

    <div class="row mt-5">
        @foreach($features as $feature)
            <div class="col-md-3 mb-3">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <p class="card-text">{{ $feature }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection