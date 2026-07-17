@extends('layouts.app')

@section('title', 'Kelola Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Users</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Admin Dashboard</a>
</div>

<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Tasks</th>
            <th>Bergabung</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->isAdmin())
                        <span class="badge text-bg-dark">Admin</span>
                    @elseif ($user->role === 'moderator')
                        <span class="badge text-bg-info">Moderator</span>
                    @else
                        <span class="badge text-bg-secondary">User</span>
                    @endif
                </td>
                <td>{{ $user->tasks_count }}</td>
                <td>{{ $user->created_at?->format('d M Y') }}</td>
                <td>
                    @if (auth()->user()->isAdmin() && auth()->id() !== $user->id)
                        <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="role" value="{{ $user->isAdmin() ? 'user' : 'admin' }}">
                            <button class="btn btn-sm {{ $user->isAdmin() ? 'btn-outline-warning' : 'btn-outline-dark' }}">
                                {{ $user->isAdmin() ? 'Jadikan User' : 'Jadikan Admin' }}
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $users->links() }}
@endsection