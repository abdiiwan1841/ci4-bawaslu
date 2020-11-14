<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class AuthModel extends Model
{

    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id', 'username', 'password', 'name', 'email', 'image', 'token_password', 'token_password_expired'];

    protected $useTimestamps = true;

    protected $dateTime;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';token_password_expired
    // protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function __construct()
    {
        $this->dateTime = new Time('now', 'Asia/Jakarta', 'id_ID');
    }

    public function login($username, $password)
    {
        $this->select('users.id,
        users.username,
        users.name,
        users.email,
        users.image,
        b.id_group,
        c.name AS group_name,
        d.uri AS landing_page');
        $this->join('user_groups b', 'b.id_user=users.id');
        $this->join('groups c', 'c.id=b.id_group');
        $this->join('permissions d', 'd.id=c.landing_page');
        $this->where('users.username', $username);
        $this->where('users.password', $password);
        $query = $this->get();
        $data = $query->getRow();

        if (isset($data)) {
            return $data;
        }
        return false;
    }

    public function getUserPermissions($idUser)
    {
        if (isset($idUser)) {

            // $sql = "SELECT 
            //         -- a.id,
            //         -- a.username,
            //         -- a.name,
            //         -- b.id_group,
            //         -- c.name AS group_name,
            //         -- d.id_permission,
            //         -- e.name AS page_name,
            //         e.uri 
            //         FROM users a
            //         JOIN user_groups b ON b.id_user=a.id
            //         JOIN groups c ON c.id=b.id_group
            //         JOIN group_permissions d ON d.id_group=c.id
            //         JOIN permissions e ON e.id=d.id_permission
            //         WHERE a.id=?";

            // $query = $this->query($sql, [$idUser]);

            $this->select('e.uri');
            $this->join('user_groups b', 'b.id_user=users.id');
            $this->join('groups c', 'c.id=b.id_group');
            $this->join('group_permissions d', 'd.id_group=c.id');
            $this->join('permissions e', 'e.id=d.id_permission');
            $this->where('users.id', $idUser);
            $query = $this->get();
            $data = $query->getResult();
            $result = array();
            if (isset($data)) {
                foreach ($data as $r) {
                    array_push($result, $r->uri);
                }
                return $result;
            }
        }
        return array();
    }
}
