<?php

namespace App\Controllers;

use App\Models\KomikModel;

class Komik extends BaseController
{
    protected $komikModel;

    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }

    public function index()
    {

        $data = [
            'title' => 'Komik',
            'komik' => $this->komikModel->getKomik()
        ];
        
        return view('komik/index', $data);
    }

    public function detail($slug)
    {
        $data = [
            'title' => 'Detail Komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];

        if (empty($data['komik'])) {
            // throw new \CodeIgniter\Exceptions\PageNotFoundException('Mohon kembali ke halaman tambah data');
            return redirect()->to('komik/create');
        }

        return view('komik/detail', $data);
    } 

    public function create() 
    {
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
        ];

        return view('komik/create', $data);
    }

    public function save() 
    {
        // Validasi Input
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'is_unique' => '{field} komik sudah ada',
                    'required' => '{field} komik harus diisi'
                ]
            ]
        ])) {

            return redirect()->to('/komik/create')->withInput();
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);
        
        session()->setFlashData('pesan', 'Data berhasil ditambahkan!');

        return redirect()->to('/komik');
    }

    public function delete($id)
    {
        $this->komikModel->delete($id);
        session()->setFlashData('pesan', 'Data berhasil dihapus!');
        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Ubah Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];

        return view('komik/edit', $data);
    }

    public function update($id)
    {
        // Cek judul untuk validasi
        // $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        // if ($komikLama['judul'] == $this->request->getVar('judul')) {
        //     $rule_judul = 'required';
        // } else {
        //     $rule_judul = 'required|is_unique[komik.judul]';
        // }

        // Validasi Input
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul,id,'.$id.']', // Validasi Placeholder
                'errors' => [
                    'is_unique' => '{field} komik sudah ada',
                    'required' => '{field} komik harus diisi'
                ]
            ]
        ])) {

            return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashData('pesan', 'Data berhasil diubah!');

        return redirect()->to('/komik');
    }
}
