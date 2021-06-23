<?php 

namespace App\Models;

use CodeIgniter\Model;

class KomikModel extends Model 
{
    protected $table = 'komik';
    protected $useTimestamps = true;

    public function getKomik($slug = null)
    {
        if($slug == null) {

            return $this->findAll(); 
        }

        return $this->where(['slug' => $slug])->first();
    }
}