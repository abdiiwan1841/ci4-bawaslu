<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class RekomendasiModel extends Model
{

    protected $table      = 'rekomendasi';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id',
        'no_rekomendasi',
        'id_jenis_rekomendasi',
        'memo_rekomendasi',
        'nilai_rekomendasi',
        'nama_penanggung_jawab',
        'id_sebab',
        'status',
        'alasan_tidak_di_tl',
        'lampiran_tidak_di_tl'
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
        no_rekomendasi,
        id_jenis_rekomendasi,
        memo_rekomendasi,
        nilai_rekomendasi,
        nama_penanggung_jawab,
        id_sebab,
        status,
        alasan_tidak_di_tl,
        lampiran_tidak_di_tl');
        $this->orderBy('no_rekomendasi', 'ASC');
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
        no_rekomendasi,
        id_jenis_rekomendasi,
        memo_rekomendasi,
        nilai_rekomendasi,
        nama_penanggung_jawab,
        id_sebab,
        status,
        alasan_tidak_di_tl,
        lampiran_tidak_di_tl');
        $this->orderBy('no_rekomendasi', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getJenisRekomendasi()
    {
        try {
            $sql = "SELECT
                    a.id,
                    CONCAT(a.kode,' - ',a.deskripsi) AS nama
                    FROM jenis_rekomendasi a 
                    WHERE a.deleted_at IS NULL
                    ORDER BY nama ASC";
            $query = $this->query($sql);
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
