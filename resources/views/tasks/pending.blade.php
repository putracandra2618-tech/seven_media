@extends('layouts.app')

@section('title', 'Pending Tasks')

@section('content')
    @if($tasks->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <p class="text-muted mb-3">Tidak ada task yang masih pending.</p>
        </div>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-hover bg-white shadow-sm">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Judul</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <a href="{{ route('tasks.show', $task) }}"
                               class="text-decoration-none fw-semibold">
                                {{ $task->title }}
                            </a>

                            @if($task->category)
                                <span class="badge bg-{{ $task->category->color }} ms-1">
                                    {{ $task->category->name }}
                                </span>
                            @endif

                            @if($task->description)
                                <br>
                                <small class="text-muted">
                                    {{ Str::limit($task->description, 60) }}
                                </small>
                            @endif
                        </td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('tasks.edit', $task) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('tasks.destroy', $task) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin hapus task ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
