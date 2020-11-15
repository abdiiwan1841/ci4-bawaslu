<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{

    protected $table      = 'laporan';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'no_laporan',
        'tanggal_laporan',
        'nama_laporan',
        'no_surat_tugas',
        'tanggal_surat_tugas',
        'unit_pelaksana',
        'nip_pimpinan',
        'pimpinan_satuan_kerja',
        'nama_satuan_kerja',
        'tahun_anggaran',
        'nilai_anggaran',
        'realisasi_anggaran',
        'audit_anggaran',
        'jenis_anggaran',
        'id_auditor',
        'id_satuan_kerja'
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
        no_laporan,
        tanggal_laporan,
        nama_laporan,
        no_surat_tugas,
        tanggal_surat_tugas,
        unit_pelaksana,
        nip_pimpinan,
        pimpinan_satuan_kerja,
        nama_satuan_kerja,
        tahun_anggaran,
        nilai_anggaran,
        realisasi_anggaran,
        audit_anggaran,
        jenis_anggaran,
        id_auditor,
        id_satuan_kerja');
        $this->orderBy('nama_laporan', 'ASC');
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
        no_laporan,
        tanggal_laporan,
        nama_laporan,
        no_surat_tugas,
        tanggal_surat_tugas,
        unit_pelaksana,
        nip_pimpinan,
        pimpinan_satuan_kerja,
        nama_satuan_kerja,
        tahun_anggaran,
        nilai_anggaran,
        realisasi_anggaran,
        audit_anggaran,
        jenis_anggaran,
        id_auditor,
        id_satuan_kerja');
        $this->orderBy('nama_laporan', 'ASC');
        $this->where('id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
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
