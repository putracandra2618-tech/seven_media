<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $total   = $user->tasks()->count();
        $done    = $user->tasks()->where('is_done', true)->count();
        $pending = $total - $done;

        $recentTasks = $user->tasks()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        $categories = $user->categories()
            ->withCount('tasks')
            ->orderByDesc('tasks_count')
            ->get();

        $popularTags = $user->tags()
            ->withCount('tasks')
            ->orderByDesc('tasks_count')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'total',
            'done',
            'pending',
            'recentTasks',
            'categories',
            'popularTags'
        ));
    }
}