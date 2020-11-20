<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class SatuanKerjaModel extends Model
{

    protected $table      = 'satuan_kerja';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = ['id', 'kode_satuan_kerja', 'nama_satuan_kerja', 'id_wilayah', 'id_pimpinan'];

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
        kode_satuan_kerja,
        nama_satuan_kerja,
        id_wilayah,
        id_pimpinan');
        $this->orderBy('nama_satuan_kerja', 'ASC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataById($id)
    {
        try {
            $sql = "SELECT 
                    a.id,
                    a.kode_satuan_kerja,
                    a.nama_satuan_kerja,
                    a.id_wilayah,
                    a.id_pimpinan,
                    b.`name` AS nama_pimpinan,
                    b.nip
                    FROM satuan_kerja a
                    JOIN users b ON b.id=a.`id_pimpinan`
                    WHERE a.deleted_at IS NULL 
                    AND a.id=? ";
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

    public function getProvinsi()
    {
        try {
            $sql = "SELECT
                    a.id,
                    a.nama_provinsi
                    FROM provinsi a 
                    JOIN kabupaten b ON b.id_provinsi=a.id
                    ORDER BY a.nama_provinsi ASC";
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

    public function getKabupaten($idProvinsi)
    {
        try {
            $sql = "SELECT
                    b.id,
                    b.nama_kabupaten
                    FROM provinsi a 
                    JOIN kabupaten b ON b.id_provinsi=a.id
                    WHERE a.id=?
                    ORDER BY b.nama_kabupaten ASC";
            $query = $this->query($sql, [$idProvinsi]);
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
