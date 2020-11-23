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
        nilai_rekomendasi,
        nilai_sisa_rekomendasi,
        nilai_akhir_rekomendasi,
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
        nilai_rekomendasi,
        nilai_sisa_rekomendasi,
        nilai_akhir_rekomendasi,
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

    public function showButtonSesuai($idRekomendasi)
    {
        try {
            $sql = "SELECT
                    COUNT(a.status) AS status_terima,
                    (
                        SELECT 
                        COUNT(b.id) 
                        FROM tindak_lanjut b 
                        WHERE b.deleted_at IS NULL 
                        AND b.id_rekomendasi=?
                    ) AS jumlah_tl
                    FROM tindak_lanjut a 
                    WHERE a.status='TERIMA' 
                    AND a.id_rekomendasi=?
                    AND a.deleted_at IS NULL";
            $query = $this->query($sql, [$idRekomendasi, $idRekomendasi]);
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
