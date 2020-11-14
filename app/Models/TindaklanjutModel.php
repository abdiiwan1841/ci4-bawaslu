<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class TindaklanjutModel extends Model
{

    protected $table      = 'tindak_lanjut';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id',
        'nilai_rekomendasi',
        'nilai_sisa_rekomendasi',
        'nilai_akhir_rekomendasi',
        'id_rekomendasi'
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
        id,
        nilai_rekomendasi,
        nilai_sisa_rekomendasi,
        nilai_akhir_rekomendasi,
        id_rekomendasi');
        $this->orderBy('nilai_rekomendasi', 'ASC');
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
        id,
        nilai_rekomendasi,
        nilai_sisa_rekomendasi,
        nilai_akhir_rekomendasi,
        id_rekomendasi');
        $this->orderBy('nilai_rekomendasi', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }
}
