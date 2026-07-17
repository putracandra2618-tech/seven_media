@extends('layouts.app')

@section('title', 'Trash Task')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Trash</h1>
    <div class="d-flex gap-2">
        <form action="{{ route('tasks.restoreAll') }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-success btn-sm" type="submit">Restore Semua</button>
        </form>
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Tasks</a>
    </div>
</div>

@if ($tasks->isEmpty())
    <div class="alert alert-info">Trash kosong.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Dihapus</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->deleted_at?->diffForHumans() }}</td>
                        <td class="text-end">
                            <form action="{{ route('tasks.restore', $task->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Restore</button>
                            </form>
                            <form action="{{ route('tasks.forceDelete', $task->id) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus permanen? Lampiran akan ikut terhapus. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus Permanen</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $tasks->links() }}
@endif
@endsection