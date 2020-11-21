<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class GroupModel extends Model
{

    protected $table      = 'groups';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id', 'name', 'description', 'landing_page'];

    protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function getData()
    {
        $this->select('groups.id,groups.name,groups.description,permissions.name as landing_page');
        $this->join('permissions', 'permissions.id=groups.landing_page', 'left');
        $this->where('groups.deleted_at', NULL);
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataById($id)
    {
        $this->select('groups.id,
        groups.name,
        groups.description,
        permissions.id as landing_page,
        permissions.name as landing_page_uri');
        $this->join('permissions', 'permissions.id=groups.landing_page', 'left');
        $this->where('groups.deleted_at', NULL);
        $this->where('groups.id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }
}
