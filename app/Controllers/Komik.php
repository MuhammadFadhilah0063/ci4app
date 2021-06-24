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
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1048]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png,image/jfif]',
                'errors' => [
                    'max_size' => 'ukuran {field} terlalu besar, max.1MB',
                    'is_image' => '{field} yang anda pilih bukan gambar',
                    'mime_in' => '{field} yang anda pilih bukan gambar'
                ]
            ]
        ])) {

            return redirect()->to('/komik/create')->withInput();
        }

        // ambil gambar 
        $fileSampul = $this->request->getFile('sampul');

        // cek apakah tidak ada gambar, jika tidak ada gunakan gambar default
        if ($fileSampul->getError() == 4) {
            $namaSampul = "img.png";
        } else {
            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan file ke folder img
            $fileSampul->move('img', $namaSampul);
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);
        
        session()->setFlashData('pesan', 'Data berhasil ditambahkan!');

        return redirect()->to('/komik');
    }

    public function delete($id)
    {
        // Cari nama gambar berdasarkan id
        $komik = $this->komikModel->find($id);

        // cek jika nama file adalah default maka jangan hapus file sampul di img
        if ($komik['sampul'] != 'img.png') {
            // hapus gambar di folder img
            unlink('img/' . $komik['sampul']);
        }

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
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1048]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png,image/jfif]',
                'errors' => [
                    'max_size' => 'ukuran {field} terlalu besar, max.1MB',
                    'is_image' => '{field} yang anda pilih bukan gambar',
                    'mime_in' => '{field} yang anda pilih bukan gambar'
                ]
            ]
        ])) {

            return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
        }

        // ambil gambar 
        $fileSampul = $this->request->getFile('sampul');
        $sampulLama = $this->request->getVar('sampulLama');

        // cek apakah tetap gambar lama
        if ($fileSampul->getError() == 4) {
            $namaSampul = $sampulLama;
        } else {
            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan file ke folder img
            $fileSampul->move('img', $namaSampul);
            // hapus sampul lama
            unlink('img/' . $sampulLama);
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);

        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashData('pesan', 'Data berhasil diubah!');

        return redirect()->to('/komik');
    }
}
