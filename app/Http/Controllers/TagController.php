<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = auth()->user()
            ->tags()
            ->withCount('tasks')
            ->orderBy('name')
            ->paginate(15);

        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
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
        abort_unless($tag->user_id === auth()->id(), 403);

        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        abort_unless($tag->user_id === auth()->id(), 403);

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
        abort_unless($tag->user_id === auth()->id(), 403);

        $tag->tasks()->detach();
        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag dihapus.');
    }
}