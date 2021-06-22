<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'HOME'
        ];
        return view('pages/home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'ABOUT'
        ];
        return view('pages/about', $data);
    }
}
