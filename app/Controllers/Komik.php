<?php

namespace App\Controllers;



class Komik extends BaseController
{
    // protected $komikModel;

    // public function __construct()
    // {
    //     // $this->komikModel = new KomikModel();
    // }

    public function index()
    {
        // $komik = $this->komikModel->findAll();
        $db = \config\Database::connect();
        $komik = $db->query("SELECT * FROM komik");
        foreach($komik->getResultArray() as $row) {
            d($row);
        }
        

        $data = [
            'title' => 'Komik'
        ];
        return view('komik/index', $data);
    }
}
