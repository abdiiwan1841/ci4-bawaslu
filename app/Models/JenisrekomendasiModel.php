<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | +62-852-2224-1987 | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class JenisrekomendasiModel extends Model
{

    protected $table      = 'jenis_rekomendasi';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id', 'kode', 'deskripsi', 'id_jenis_temuan',
    ];

    protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function getData()
    {
        $this->select('
        id,kode,deskripsi,id_jenis_temuan');
        $this->orderBy('id', 'ASC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataById($id)
    {
        $this->select('
        id,kode,deskripsi,id_jenis_temuan');
        $this->orderBy('id', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }
}
