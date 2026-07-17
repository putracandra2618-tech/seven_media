<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Tag::class);

        $tags = auth()->user()
            ->tags()
            ->withCount('tasks')
            ->orderBy('name')
            ->paginate(15);

        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        $this->authorize('create', Tag::class);

        return view('tags.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Tag::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'in:primary,success,danger,warning,info,secondary'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['user_id'] = auth()->id();

        // unik per user
        $exists = auth()->user()->tags()
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Tag dengan nama ini sudah ada.']);
        }

        Tag::create($validated);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag berhasil dibuat!');
    }

    public function edit(Tag $tag)
    {
        $this->authorize('update', $tag);

        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'in:primary,success,danger,warning,info,secondary'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tag->update($validated);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag berhasil diupdate!');
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->tasks()->detach();
        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag dihapus.');
    }
}