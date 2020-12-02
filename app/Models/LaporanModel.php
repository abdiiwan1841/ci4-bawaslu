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

    public function counter()
    {
        try {
            $sql = "SELECT
                    COUNT(a.id)+1 AS counter
                    FROM laporan a";
            $query = $this->query($sql);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->counter;
            }
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function getReport($idSatuanKerja)
    {

        try {
            $sql = "SELECT
            a.no_laporan,
            a.nama_laporan,
            b.memo_temuan,
            b.id AS jumlah_temuan,
            b.nilai_temuan,
            d.memo_rekomendasi,
            d.id AS jumlah_rekomendasi,
            d.nilai_rekomendasi,
            '' AS jumlah_sesuai_rekomendasi,
            (
                SELECT SUM(e.nilai_terverifikasi)
                FROM tindak_lanjut e 
                WHERE e.id_rekomendasi=d.id
            ) AS nilai_sesuai_rekomendasi,
            '' AS jumlah_belum_sesuai_rekomendasi,
            (
                (
                    SELECT SUM(f.nilai_tindak_lanjut)
                    FROM tindak_lanjut f 
                    WHERE f.id_rekomendasi=d.id
                )
                -
                (
                    SELECT SUM(g.nilai_terverifikasi)
                    FROM tindak_lanjut g 
                    WHERE g.id_rekomendasi=d.id
                )
            ) AS nilai_yang_belum_sesuai_rekomendasi_dan_dalam_proses_tindak_lanjut,
            '' AS jumlah_belum_ditindaklanjuti,
            (
                d.nilai_rekomendasi
                -
                (
                    SELECT SUM(h.nilai_tindak_lanjut)
                    FROM tindak_lanjut h 
                    WHERE h.id_rekomendasi=d.id
                )
            ) AS nilai_belum_ditindaklanjuti,
            '' AS jumlah_tidak_dapat_ditindaklanjuti,
            (
                IF(d.status='TIDAK_DAPAT_DI_TL',d.nilai_rekomendasi,0)
            ) AS nilai_tidak_dapat_ditindaklanjuti
        FROM laporan a 
        JOIN temuan b ON b.id_laporan=a.id 
        JOIN sebab c ON c.id_temuan=b.id 
        JOIN rekomendasi d ON d.id_sebab=c.id 
        WHERE a.id_satuan_kerja=?";


            $query = $this->query($sql, [$idSatuanKerja]);
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
