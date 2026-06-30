<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        $info = [
            'title' => 'About',
            'content' => 'Welcome to my blog! Here I share my knowledge, projects and thoughts on programming and technology. My goal is to practise and share useful information with others.',
        ];

        return view('about', compact('info'));
    }
}
