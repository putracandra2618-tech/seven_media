<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Seven Media';
        $tagline = 'mengelola tugas dengan mudah';

        $features = [
            'Membuat, mengedit, dan menghapus tugas',
            'Membuat, mengedit, dan menghapus kategori tugas',
        ];

        return view('home', compact('title', 'tagline', 'features'));
    }
}