<?php

namespace App\Controllers;

class Coba extends BaseController
{
    public function index()
    {
        echo "Ini controller Coba Method Index";
    }

    public function about($nama = "Kosong")
    {
        echo "Nama Saya $nama";
    }
}
