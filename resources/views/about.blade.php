@extends('layouts.app')

@section('title', $title)

@section('content')
    <h1 class="h2 fw-bold mb-4">{{ $title }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-borderless mb-3">
                <tr>
                    <td width="120"><strong>Aplikasi</strong></td>
                    <td>{{ $appName }}</td>
                </tr>
                <tr>
                    <td><strong>Versi</strong></td>
                    <td>{{ $version }}</td>
                </tr>
                <tr>
                    <td><strong>Pembuat</strong></td>
                    <td>{{ $author }}</td>
                </tr>
            </table>

            <p class="text-muted mb-0">{{ $description }}</p>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            @include('partials.card', [
                'title' => 'Laravel 12',
                'body' => 'Framework PHP modern'
            ])
        </div>

        <div class="col-md-4">
            @include('partials.card', [
                'title' => 'Bootstrap 5',
                'body' => 'CSS framework via CDN'
            ])
        </div>

        <div class="col-md-4">
            @include('partials.card', [
                'title' => 'MySQL 8',
                'body' => 'Database relational'
            ])
        </div>
    </div>
@endsection