<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class GroupPermissionModel extends Model
{

    protected $table      = 'group_permissions';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'id_group', 'id_permission'];

    protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function getData()
    {
        $this->select('id,id_group');
        $this->where('deleted_at', NULL);
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataById($id)
    {
        $this->select('id,id_group');
        $this->where('deleted_at', NULL);
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getGroupPermissions($idGroup)
    {
        if (isset($idGroup)) {

            $sql = "SELECT 
            a.id_group,
            a.id_permission,
            b.name
            FROM group_permissions a
            JOIN permissions b ON b.id=a.id_permission
            WHERE a.id_group=?";
            $query = $this->query($sql, [$idGroup]);
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
        }
        return array();
    }

    public function getGroupPermissionsSelected($idGroup)
    {
        if (isset($idGroup)) {

            $sql = "SELECT 
            a.id_group,
            a.id_permission,
            b.name
            FROM group_permissions a
            JOIN permissions b ON b.id=a.id_permission
            WHERE a.id_group=?";

            $query = $this->query($sql, [$idGroup]);

            $data = $query->getResult();
            $result = array();
            if (isset($data)) {
                foreach ($data as $r) {
                    array_push($result, $r->id_permission);
                }
                return $result;
            }
        }
        return array();
    }

    public function getGroupsSelectedByIdPermission($idPermission)
    {
        if (isset($idPermission)) {

            $sql = "SELECT 
            a.id_group,
            a.id_permission,
            b.name
            FROM group_permissions a
            JOIN permissions b ON b.id=a.id_permission
            WHERE a.id_permission=?";

            $query = $this->query($sql, [$idPermission]);

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
