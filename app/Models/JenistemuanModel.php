<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | +62-852-2224-1987 | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class JenistemuanModel extends Model
{

    protected $table      = 'jenis_temuan';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id', 'kode', 'deskripsi', 'id_parent',
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
        id,kode,deskripsi,id_parent');
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
        id,kode,deskripsi,id_parent');
        $this->orderBy('id', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getJenisTemuan($idJenisTemuan = '')
    {
        try {
            $sql = "SELECT
                    a.id,
                    CONCAT(a.kode,' - ',a.deskripsi) AS nama
                    FROM jenis_temuan a 
                    WHERE a.id <> ?
                    AND a.deleted_at IS NULL
                    ORDER BY nama ASC";
            $query = $this->query($sql, [$idJenisTemuan]);
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
