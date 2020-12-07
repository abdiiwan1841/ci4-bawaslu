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
        'no_tindak_lanjut',
        'nilai_rekomendasi',
        'nilai_tindak_lanjut',
        'nilai_terverifikasi',
        'nilai_sisa_rekomendasi',
        'id_rekomendasi',
        'remark_auditor',
        'remark_auditee',
        'status',
        'read_status'
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
        no_tindak_lanjut,
        nilai_rekomendasi,
        nilai_tindak_lanjut,
        nilai_terverifikasi,
        nilai_sisa_rekomendasi,
        id_rekomendasi,
        remark_auditor,
        remark_auditee,
        status,
        read_status');
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
        no_tindak_lanjut,
        nilai_rekomendasi,
        nilai_tindak_lanjut,
        nilai_terverifikasi,
        nilai_sisa_rekomendasi,
        id_rekomendasi,
        remark_auditor,
        remark_auditee,
        status,
        read_status');
        $this->orderBy('nilai_rekomendasi', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function showButton($idRekomendasi)
    {
        try {
            $sql = "SELECT 
                    COUNT(a.id) AS jumlah_tl
                    FROM tindak_lanjut a
                    WHERE a.deleted_at IS NULL 
                    AND a.id_rekomendasi=?
                    AND a.deleted_at IS NULL";
            $query = $this->query($sql, [$idRekomendasi]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTotalNilaiTerverifikasi($idRekomendasi)
    {
        try {
            $sql = "SELECT 
            IFNULL(SUM(nilai_terverifikasi),0) AS total
            FROM tindak_lanjut 
            WHERE id_rekomendasi=?";
            $query = $this->query($sql, [$idRekomendasi]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->total;
            }
            return 0;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSisaNilaiRekomendasi($idRekomendasi)
    {
        try {
            $sql = "SELECT
                        IFNULL(b.nilai_rekomendasi,0) AS nilai_rekomendasi,
                        IFNULL(SUM(a.nilai_tindak_lanjut),0) AS total_nilai_tindak_lanjut,
                        IFNULL(SUM(a.nilai_terverifikasi),0) AS total_nilai_terverifikasi,
                        (IFNULL(b.nilai_rekomendasi,0)-IFNULL(SUM(a.nilai_terverifikasi),0)) AS sisa_nilai_rekomendasi,
                        b.status
                    FROM tindak_lanjut a 
                    JOIN rekomendasi b ON b.id=a.id_rekomendasi
                    WHERE a.deleted_at IS NULL 
                    AND b.deleted_at IS NULL
                    AND a.id_rekomendasi=?";
            $query = $this->query($sql, [$idRekomendasi]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
