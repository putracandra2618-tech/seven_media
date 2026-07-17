<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users'      => User::count(),
            'admins'     => User::where('role', 'admin')->count(),
            'tasks'      => Task::withTrashed()->count(),
            'tasks_live' => Task::count(),
            'trashed'    => Task::onlyTrashed()->count(),
            'categories' => Category::count(),
            'tags'       => Tag::count(),
        ];

        $latestUsers = User::latest()->take(5)->get();
        $latestTasks = Task::with(['user', 'category'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'latestUsers', 'latestTasks'));
    }
}