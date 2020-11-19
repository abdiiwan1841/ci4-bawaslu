<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class LaporanAuditeeModel extends Model
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
            $data->temuan = $this->getTemuan($id);
            return $data;
        }
        return array();
    }

    public function getTemuan($idLaporan)
    {
        $result = [];
        try {
            $sql = "SELECT
                    a.`id`,
                    a.`no_temuan`,
                    a.`memo_temuan`,
                    a.`jenis_temuan`,
                    a.`nilai_temuan`,
                    a.`id_laporan`,
                    a.`created_at`,
                    a.`updated_at`,
                    a.`deleted_at`
                    FROM `temuan` a 
                    WHERE a.id_laporan=?
                    ORDER BY a.no_temuan ASC";
            $query = $this->query($sql, [$idLaporan]);
            $data = $query->getResult();
            if (isset($data)) {
                foreach ($data as $r) {
                    $r->rekomendasi = $this->getRekomendasi($r->id);
                    $r->sebab = $this->getSebab($r->id);
                    array_push($result, $r);
                }
                return $result;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRekomendasi($idTemuan)
    {
        try {
            $sql = "SELECT
                    a.`id`,
                    a.`no_rekomendasi`,
                    a.`memo_rekomendasi`,
                    a.`nilai_rekomendasi`,
                    a.`nama_penanggung_jawab`,
                    a.`id_temuan`,
                    a.`created_at`,
                    a.`updated_at`,
                    a.`deleted_at`
                    FROM `rekomendasi` a
                    WHERE a.id_temuan=?
                    ORDER BY a.no_rekomendasi ASC";
            $query = $this->query($sql, [$idTemuan]);
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSebab($idTemuan)
    {
        try {
            $sql = "SELECT
                    a.`id`,
                    a.`no_sebab`,
                    a.`memo_sebab`,
                    a.`id_temuan`,
                    a.`created_at`,
                    a.`updated_at`,
                    a.`deleted_at`
                    FROM `sebab` a
                    WHERE a.id_temuan=?
                    ORDER BY a.no_sebab ASC";
            $query = $this->query($sql, [$idTemuan]);
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTindakLanjut($idRekomendasi)
    {
        try {
            $sql = "SELECT
                    a.`no_rekomendasi`,
                    a.`memo_rekomendasi`,
                    a.`nama_penanggung_jawab`,
                    a.`nilai_rekomendasi`,
                    b.`no_temuan`,
                    b.`memo_temuan`,
                    b.`jenis_temuan`,
                    b.`nilai_temuan`,
                    c.`no_laporan`,
                    c.`nama_laporan`,
                    c.`nilai_anggaran`,
                    c.`audit_anggaran`,
                    c.`tanggal_laporan`,
                    c.`tahun_anggaran`
                    FROM rekomendasi a
                    JOIN temuan b ON b.`id`=a.`id_temuan` 
                    JOIN laporan c ON c.`id`=b.`id_laporan`
                    WHERE a.id=?
                    ";
            $query = $this->query($sql, [$idRekomendasi]);
            $data = $query->getRow();
            if (isset($data)) {
                $data->tindak_lanjut = $this->getRiwayatTindakLanjut($idRekomendasi);
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRiwayatTindakLanjut($idRekomendasi)
    {
        try {
            $sql = "SELECT
                    a.`id`,
                    a.`nilai_rekomendasi`,
                    a.`nilai_sisa_rekomendasi`,
                    a.`nilai_akhir_rekomendasi`,
                    a.`id_rekomendasi`,
                    a.`created_at`,
                    a.`updated_at`,
                    a.`deleted_at`
                    FROM `tindak_lanjut` a
                    WHERE a.id_rekomendasi=?
                    ORDER BY a.created_at DESC";
            $query = $this->query($sql, [$idRekomendasi]);
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
