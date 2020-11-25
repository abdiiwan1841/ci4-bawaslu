<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{

    protected $table      = 'pegawai';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id',
        'nip',
        'nama',
        'jabatan',
        'id_satuan_kerja',
        'id_user',
        'type',
        'id_provinsi',
        'id_kabupaten'
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
        nip,
        nama,
        jabatan,
        id_satuan_kerja,
        id_user,
        type,
        id_provinsi,
        id_kabupaten');
        $this->orderBy('nama', 'ASC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    // public function getDataById($id)
    // {
    //     $this->select('
    //     id,
    //     nip,
    //     nama,
    //     jabatan,
    //     id_satuan_kerja,
    //     id_user,
    //     type,
    //     id_provinsi,
    //     id_kabupaten');
    //     $this->orderBy('nama', 'ASC');
    //     $this->where('id', $id);
    //     $query = $this->get();
    //     $data = $query->getRow();
    //     if (isset($data)) {
    //         return $data;
    //     }
    //     return array();
    // }

    public function getDataById($id)
    {
        try {
            $sql = "SELECT 
            a.`id`,
            a.`nip`,
            a.`nama`,
            a.`jabatan`,
            a.`id_satuan_kerja`,
            c.wilayah,
            a.`id_user`,
            a.id_provinsi,
            a.id_kabupaten,
            b.username,
            b.email,
            b.image,
            a.`created_at`,
            a.`updated_at`,
            a.`deleted_at`
            FROM `pegawai` a
            LEFT JOIN users b ON b.id=a.id_user
            LEFT JOIN (
                        SELECT
                        x.`id`,
                        x.`nama_provinsi` AS wilayah
                        FROM provinsi `x` 
                        UNION
                        SELECT 
                        y.id,
                        y.nama_kabupaten AS wilayah
                        FROM
                        kabupaten `y`
                    ) c ON c.id=a.`id_satuan_kerja`
            WHERE a.deleted_at IS NULL
            AND a.id=?
            ORDER BY a.nama ASC;";
            $query = $this->query($sql, [$id]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEselon1()
    {
        try {
            $sql = "SELECT
                    a.id,
                    a.nama
                    FROM eselon a 
                    WHERE a.level_eselon='1' 
                    AND a.deleted_at IS NULL
                    ORDER BY a.nama ASC";
            $query = $this->query($sql);
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEselon2($idEselon1)
    {
        try {
            $sql = "SELECT
                    a.id,
                    a.nama
                    FROM eselon a 
                    WHERE a.level_eselon='2' 
                    AND a.id_parent=?
                    AND a.deleted_at IS NULL
                    ORDER BY a.nama ASC";
            $query = $this->query($sql, [$idEselon1]);
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEselon3($idEselon2)
    {
        try {
            $sql = "SELECT
                    a.id,
                    a.nama
                    FROM eselon a 
                    WHERE a.level_eselon='3' 
                    AND a.id_parent=?
                    AND a.deleted_at IS NULL
                    ORDER BY a.nama ASC";
            $query = $this->query($sql, [$idEselon2]);
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
