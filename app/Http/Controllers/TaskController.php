<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        $tasks = Task::latest()->paginate(5);

        $total   = Task::count();
        $done    = Task::where('is_done', true)->count();
        $pending = $total - $done;

        return view('tasks.index', compact('tasks', 'total', 'done', 'pending'));
    }

    public function pending()
    {
        $tasks = Task::where('is_done', false)->latest()->get();
        return view('tasks.pending', compact('tasks'));
    }   

}
