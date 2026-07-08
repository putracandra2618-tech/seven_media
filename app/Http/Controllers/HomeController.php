<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Seven Media';
        $tagline = 'mengelola tugas dengan mudah';

        $features = ['Kelola Task Harian', 'Tandai Selesai', 'Filter Kategori', 'Dashboard Statistik'];
        
        return view('home', compact('title', 'tagline', 'features'));
    }
}