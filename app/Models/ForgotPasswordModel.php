<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class ForgotPasswordModel extends Model
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

    public function generateTokenByEmail($email)
    {
        try {

            $token = get_uuid();

            $data = [
                'token_password' => $token,
                'token_password_expired' => $this->dateTime->now()->addHours(3)->format('Y-m-d H:i:s')
            ];

            $this->where('email', $email);
            $this->set($data);
            $this->update();

            if ($this->affectedRows() > 0) {
                return $token;
            }
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return false;
        }

        return false;
    }

    public function checkTokenExpired($token)
    {
        $this->select('token_password,token_password_expired');
        $this->where('token_password', $token);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }

        return false;
    }

    public function saveNewPassword($token, $data)
    {
        try {

            $this->where('token_password', $token);
            $this->set($data);
            $this->update();

            if ($this->affectedRows() > 0) {
                return true;
            }
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return false;
        }

        return false;
    }
}
