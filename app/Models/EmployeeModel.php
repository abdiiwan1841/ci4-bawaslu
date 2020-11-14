<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{

    protected $table      = 'employee';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id',
        'employee_id',
        'name',
        'position_code',
        'position_percent',
        'section_code',
        'department_code',
        'division_code',
        'directorate_code',
        'address',
        'district',
        'city',
        'pob',
        'dob',
        'entry_date'
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
        $this->select('id,
                        employee_id,
                        name,
                        position_code,
                        position_percent,
                        section_code,
                        department_code,
                        division_code,
                        directorate_code,
                        address,
                        district,
                        city,
                        pob,
                        dob,
                        entry_date');
        $this->orderBy('name', 'ASC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataById($id)
    {
        $this->select('id,
                        employee_id,
                        name,
                        position_code,
                        position_percent,
                        section_code,
                        department_code,
                        division_code,
                        directorate_code,
                        address,
                        district,
                        city,
                        pob,
                        dob,
                        entry_date');
        $this->orderBy('name', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }
}
