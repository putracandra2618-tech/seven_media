<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile', [
            'user' => $user,
            'total' => $user->tasks()->count(),
            'done' => $user->tasks()->where('is_done', true)->count()
        ]);
    }
}
