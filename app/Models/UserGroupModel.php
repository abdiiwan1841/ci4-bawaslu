<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class UserGroupModel extends Model
{

    protected $table      = 'user_groups';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'id_user', 'id_group'];

    protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function getData($id = false)
    {
        if ($id) {
            return $this->where(['id' => $id])->first();
        }

        return $this->findAll();
    }

    public function getUserGroups($idUser)
    {
        if (isset($idUser)) {

            $sql = "SELECT 
            -- a.id_user,
            a.id_group,
            b.name
            FROM user_groups a
            JOIN groups b ON b.id=a.id_group
            WHERE a.id_user=?";

            $query = $this->query($sql, [$idUser]);

            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
        }
        return array();
    }

    public function getUserGroupsSelected($idUser)
    {
        if (isset($idUser)) {

            $sql = "SELECT 
            a.id_user,
            a.id_group,
            b.name
            FROM user_groups a
            JOIN groups b ON b.id=a.id_group
            WHERE a.id_user=?";

            $query = $this->query($sql, [$idUser]);

            $data = $query->getResult();
            $result = array();
            if (isset($data)) {
                foreach ($data as $r) {
                    array_push($result, $r->id_group);
                }
                return $result;
            }
        }
        return array();
    }
}
