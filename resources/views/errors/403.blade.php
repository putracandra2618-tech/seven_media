@extends('layouts.app')

@section('title', 'Akses Ditolak')

@section('content')
<div class="text-center py-5">
    <h1 class="display-6">403</h1>
    <p class="text-muted">Kamu tidak punya izin untuk mengakses halaman ini.</p>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Kembali</a>
    <a href="{{ route('home') }}" class="btn btn-primary">Beranda</a>
</div>
@endsection
