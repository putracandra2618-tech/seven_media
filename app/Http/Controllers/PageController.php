<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('about', [
            'title' => 'Tentang Aplikasi',
            'appName' => 'Task Manager',
            'version' => '1.0.0',
            'author' => 'Nama Kamu',
            'description' => 'Aplikasi manajemen task sederhana yang dibangun dengan Laravel sebagai media belajar.',
        ]);

    }

    public function contact()
    {
        return view('contact', [
            'title' => 'Kontak',
            'name' => 'Candra',
            'email' => 'contact@example.com',
            'phone' => '+62 123 456 7890',
            ]); 

    }
}
 