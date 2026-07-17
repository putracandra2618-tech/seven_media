@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="h3 mb-4">Admin Dashboard</h1>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="border rounded p-3">
            <div class="text-muted small">Users</div>
            <div class="fs-3">{{ $stats['users'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="border rounded p-3">
            <div class="text-muted small">Tasks (aktif)</div>
            <div class="fs-3">{{ $stats['tasks_live'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="border rounded p-3">
            <div class="text-muted small">Trash</div>
            <div class="fs-3">{{ $stats['trashed'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="border rounded p-3">
            <div class="text-muted small">Tags</div>
            <div class="fs-3">{{ $stats['tags'] }}</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <h2 class="h5">User terbaru</h2>
        <ul class="list-group">
            @foreach ($latestUsers as $user)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $user->name }} <small class="text-muted">{{ $user->email }}</small></span>
                    @if ($user->isAdmin())
                        <span class="badge text-bg-dark">Admin</span>
                    @else
                        <span class="badge text-bg-secondary">User</span>
                    @endif
                </li>
            @endforeach
        </ul>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary mt-2">Lihat semua users</a>
    </div>

    <div class="col-lg-6 mb-4">
        <h2 class="h5">Task terbaru (semua user)</h2>
        <ul class="list-group">
            @foreach ($latestTasks as $task)
                <li class="list-group-item">
                    <strong>{{ $task->title }}</strong>
                    <div class="small text-muted">
                        oleh {{ $task->user?->name ?? '-' }}
                        @if ($task->category)
                            · {{ $task->category->name }}
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection