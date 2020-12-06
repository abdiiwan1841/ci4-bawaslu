<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class TemuanModel extends Model
{

    protected $table      = 'temuan';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'id',
        'no_temuan',
        'memo_temuan',
        'id_jenis_temuan1',
        'id_jenis_temuan2',
        'id_jenis_temuan3',
        'nilai_temuan',
        'id_laporan'
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
        no_temuan,
        memo_temuan,
        id_jenis_temuan1,
        id_jenis_temuan2,
        id_jenis_temuan3,
        nilai_temuan,
        id_laporan');
        $this->orderBy('no_temuan', 'ASC');
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
        no_temuan,
        memo_temuan,
        id_jenis_temuan1,
        id_jenis_temuan2,
        id_jenis_temuan3,
        nilai_temuan,
        id_laporan');
        $this->orderBy('no_temuan', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getJenisTemuan()
    {
        try {
            $sql = "SELECT
                    a.id,
                    CONCAT(a.kode,' - ',a.deskripsi) AS nama
                    FROM jenis_temuan a 
                    WHERE (a.id_parent IS NULL OR a.id_parent='')
                    AND a.deleted_at IS NULL
                    ORDER BY nama ASC";
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

    public function ajaxGetJenisTemuan($idParent)
    {
        try {
            $sql = "SELECT
                    a.id,
                    CONCAT(a.kode,' - ',a.deskripsi) AS nama
                    FROM jenis_temuan a 
                    WHERE a.id_parent=?
                    AND a.deleted_at IS NULL
                    ORDER BY nama ASC";
            $query = $this->query($sql, [$idParent]);
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function counter($idLaporan)
    {
        try {
            $sql = "SELECT
            CONCAT(b.no_laporan,'.',COUNT(a.id)+1) AS counter
            FROM temuan a 
            RIGHT JOIN laporan b ON b.id=a.id_laporan
            WHERE b.id=?";
            $query = $this->query($sql, [$idLaporan]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->counter;
            }
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getKetuaTim($idLaporan = '')
    {
        try {
            $sql = "SELECT
                    a.ketua_tim
                    FROM laporan a 
                    WHERE a.id=?";
            $query = $this->query($sql, [$idLaporan]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->ketua_tim;
            }
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
