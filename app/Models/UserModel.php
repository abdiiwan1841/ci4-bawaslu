<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{

    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id', 'username', 'password', 'name', 'email', 'image'];

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
        users.id,
        users.name,
        users.username,
        users.password,
        users.email,
        users.image,
        user_groups.id_group');
        $this->join('user_groups', 'user_groups.id_user=users.id', 'left');
        $this->where('users.deleted_at', NULL);
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
        users.id,
        users.name,
        users.username,
        users.password,
        users.email,
        users.image,
        user_groups.id_group');
        $this->join('user_groups', 'user_groups.id_user=users.id', 'left');
        $this->where('users.deleted_at', NULL);
        $this->where('users.id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }
}
