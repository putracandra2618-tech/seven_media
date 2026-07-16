@extends('layouts.app')

@section('title', 'Tags')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Tags</h1>
    <a href="{{ route('tags.create') }}" class="btn btn-primary">Tambah Tag</a>
</div>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Slug</th>
            <th>Jumlah Task</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tags as $tag)
            <tr>
                <td><span class="badge bg-{{ $tag->color }}">{{ $tag->name }}</span></td>
                <td><code>{{ $tag->slug }}</code></td>
                <td>{{ $tag->tasks_count }}</td>
                <td class="text-end">
                    <a href="{{ route('tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus tag ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-muted">Belum ada tag.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $tags->links() }}
@endsection