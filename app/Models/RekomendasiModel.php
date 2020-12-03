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

    public function isStatusBelumTL($id)
    {
        try {
            $sql = "SELECT
                    a.status
                    FROM rekomendasi a 
                    WHERE a.deleted_at IS NULL
                    AND a.id=?";
            $query = $this->query($sql, [$id]);
            $data = $query->getRow();
            if (isset($data)) {
                if ($data->status == 'BELUM_TL') {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            // return $e->getMessage();
            return false;
        }

        return false;
    }

    public function counter($idSebab)
    {
        try {
            $sql = "SELECT
            CONCAT(b.no_sebab,'.',COUNT(a.id)+1) AS counter
            FROM rekomendasi a 
            RIGHT JOIN sebab b ON b.id=a.id_sebab
            WHERE b.id=?";
            $query = $this->query($sql, [$idSebab]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->counter;
            }
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSelected($idRekomendasi)
    {
        if (isset($idRekomendasi)) {

            $sql = "SELECT 
            a.id,
            a.nama_penanggung_jawab
            FROM penanggung_jawab a 
            WHERE a.id_rekomendasi=?";

            $query = $this->query($sql, [$idRekomendasi]);

            $data = $query->getResult();
            $result = [];
            if (isset($data)) {
                foreach ($data as $r) {
                    array_push($result, $r->nama_penanggung_jawab);
                }
                return $result;
            }
        }
        return array();
    }
}
