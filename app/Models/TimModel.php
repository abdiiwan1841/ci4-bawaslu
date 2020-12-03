<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class TimModel extends Model
{

    protected $table      = 'tim';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id',
        'id_laporan',
        'id_auditor'
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
        id_laporan,
        id_auditor');
        $this->orderBy('id_auditor', 'ASC');
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
        id_laporan,
        id_auditor');
        $this->orderBy('id_auditor', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getSelected($idLaporan)
    {
        if (isset($idLaporan)) {

            $sql = "SELECT 
            a.id_laporan,
            a.id_auditor,
            b.nama
            FROM tim a
            JOIN pegawai b ON b.id=a.id_auditor
            WHERE a.id_laporan=?";

            $query = $this->query($sql, [$idLaporan]);

            $data = $query->getResult();
            $result = array();
            if (isset($data)) {
                foreach ($data as $r) {
                    array_push($result, $r->id_auditor);
                }
                return $result;
            }
        }
        return array();
    }
}
