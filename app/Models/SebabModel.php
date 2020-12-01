<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class SebabModel extends Model
{

    protected $table      = 'sebab';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id',
        'no_sebab',
        'memo_sebab',
        'id_temuan'
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
        no_sebab,
        memo_sebab,
        id_temuan');
        $this->orderBy('no_sebab', 'ASC');
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
        no_sebab,
        memo_sebab,
        id_temuan');
        $this->orderBy('no_sebab', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function counter($idTemuan)
    {
        try {
            $sql = "SELECT
            CONCAT(b.no_temuan,'.',COUNT(a.id)+1) AS counter
            FROM sebab a 
            JOIN temuan b ON b.id=a.id_temuan
            WHERE a.id_temuan=?";
            $query = $this->query($sql, [$idTemuan]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->counter;
            }
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
