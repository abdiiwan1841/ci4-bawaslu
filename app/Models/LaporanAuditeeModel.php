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
        'ketua_tim',
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
        ketua_tim,
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
        ketua_tim,
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
                    a.id,
                    a.no_temuan,
                    a.memo_temuan,
                    a.id_jenis_temuan3,
                    a.nilai_temuan,
                    a.id_laporan,
                    a.created_at,
                    a.updated_at,
                    a.deleted_at
                    FROM temuan a 
                    WHERE a.id_laporan=?
                    ORDER BY a.no_temuan ASC";
            $query = $this->query($sql, [$idLaporan]);
            $data = $query->getResult();
            if (isset($data)) {
                foreach ($data as $r) {
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

    public function getRekomendasi($idSebab)
    {
        try {
            $sql = "SELECT
                    a.id,
                    a.no_rekomendasi,
                    a.memo_rekomendasi,
                    a.nilai_rekomendasi,
                    (SELECT GROUP_CONCAT(b.nama_penanggung_jawab SEPARATOR ', ') FROM penanggung_jawab b WHERE b.id_rekomendasi=a.id) AS nama_penanggung_jawab,
                    a.id_sebab,
                    a.created_at,
                    a.updated_at,
                    a.deleted_at
                    FROM rekomendasi a
                    WHERE a.id_sebab=?
                    ORDER BY a.no_rekomendasi ASC";
            $query = $this->query($sql, [$idSebab]);
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
        $result = [];
        try {
            $sql = "SELECT
                    a.id,
                    a.no_sebab,
                    a.memo_sebab,
                    a.id_temuan,
                    a.created_at,
                    a.updated_at,
                    a.deleted_at
                    FROM sebab a
                    WHERE a.id_temuan=?
                    ORDER BY a.no_sebab ASC";
            $query = $this->query($sql, [$idTemuan]);
            $data = $query->getResult();
            if (isset($data)) {
                foreach ($data as $r) {
                    $r->rekomendasi = $this->getRekomendasi($r->id);
                    array_push($result, $r);
                }
                return $result;
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
                    a.id,
                    a.no_rekomendasi,
                    e.deskripsi AS jenis_rekomendasi,
                    a.memo_rekomendasi,
                    (SELECT GROUP_CONCAT(g.nama_penanggung_jawab SEPARATOR ', ') FROM penanggung_jawab g WHERE g.id_rekomendasi=a.id) AS nama_penanggung_jawab,
                    a.nilai_rekomendasi,
                    b.id AS id_sebab,
                    b.no_sebab,
                    b.memo_sebab,
                    c.no_temuan,
                    f.deskripsi AS jenis_temuan,
                    c.memo_temuan,
                    c.id_jenis_temuan3,
                    c.nilai_temuan,
                    d.id AS id_laporan,
                    d.no_laporan,
                    d.nama_laporan,
                    d.nilai_anggaran,
                    d.audit_anggaran,
                    d.tanggal_laporan,
                    d.tahun_anggaran
                    FROM rekomendasi a
                    JOIN sebab b ON b.id=a.id_sebab 
                    JOIN temuan c ON c.id=b.id_temuan 
                    JOIN laporan d ON d.id=c.id_laporan
                    LEFT JOIN jenis_rekomendasi e ON e.id=a.id_jenis_rekomendasi
                    LEFT JOIN jenis_temuan f ON f.id=c.id_jenis_temuan3
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
                    a.id,
                    a.no_tindak_lanjut,
                    a.nilai_rekomendasi,
                    a.nilai_sisa_rekomendasi,
                    a.nilai_tindak_lanjut,
                    a.nilai_terverifikasi,
                    a.remark_auditee,
                    a.remark_auditor,
                    a.id_rekomendasi,
                    a.created_at,
                    a.updated_at,
                    a.deleted_at
                    FROM tindak_lanjut a
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

    public function getBukti($idTindakLanjut)
    {
        try {
            $sql = "SELECT
                    a.id,
                    a.no_tindak_lanjut,
                    a.nilai_tindak_lanjut,
                    a.nilai_terverifikasi,
                    a.remark_auditee,
                    a.remark_auditor,
                    b.id AS id_rekomendasi,
                    b.no_rekomendasi,
                    f.deskripsi AS jenis_rekomendasi,
                    b.memo_rekomendasi,
                    (SELECT GROUP_CONCAT(f.nama_penanggung_jawab SEPARATOR ', ') FROM penanggung_jawab f WHERE f.id_rekomendasi=b.id) AS nama_penanggung_jawab,
                    b.nilai_rekomendasi,
                    c.id AS id_sebab,
                    c.no_sebab,
                    c.memo_sebab,
                    d.no_temuan,
                    d.memo_temuan,
                    d.id_jenis_temuan3,
                    g.deskripsi AS jenis_temuan,
                    d.nilai_temuan,
                    e.no_laporan,
                    e.nama_laporan,
                    e.nilai_anggaran,
                    e.audit_anggaran,
                    e.tanggal_laporan,
                    e.tahun_anggaran
                    FROM tindak_lanjut a
                    JOIN rekomendasi b ON b.id=a.id_rekomendasi
                    JOIN sebab c ON c.id=b.id_sebab 
                    JOIN temuan d ON d.id=c.id_temuan 
                    JOIN laporan e ON e.id=d.id_laporan
                    LEFT JOIN jenis_rekomendasi f ON f.id=b.id_jenis_rekomendasi
                    LEFT JOIN jenis_temuan g ON g.id=d.id_jenis_temuan3
                    WHERE a.id=?
                    ";
            $query = $this->query($sql, [$idTindakLanjut]);
            $data = $query->getRow();
            if (isset($data)) {
                $data->bukti = $this->getRiwayatBukti($idTindakLanjut);
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getRiwayatBukti($idTindakLanjut)
    {
        try {
            $sql = "SELECT
                    a.id,
                    a.no_bukti,
                    a.nama_bukti,
                    a.nilai_bukti,
                    a.id_tindak_lanjut,
                    a.lampiran,
                    a.created_at,
                    a.updated_at,
                    a.deleted_at
                    FROM bukti a
                    WHERE a.id_tindak_lanjut=?
                    ORDER BY a.created_at DESC";
            $query = $this->query($sql, [$idTindakLanjut]);
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTotalNilaiTerverifikasi($idRekomendasi)
    {
        try {
            $sql = "SELECT 
            IFNULL(SUM(nilai_terverifikasi),0) AS total
            FROM tindak_lanjut 
            WHERE id_rekomendasi=?";
            $query = $this->query($sql, [$idRekomendasi]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->total;
            }
            return 0;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function counter($idRekomendasi)
    {
        try {
            $sql = "SELECT
            CONCAT(b.no_rekomendasi,'.',COUNT(a.id)+1) AS counter
            FROM tindak_lanjut a 
            RIGHT JOIN rekomendasi b ON b.id=a.id_rekomendasi
            WHERE b.id=?";
            $query = $this->query($sql, [$idRekomendasi]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->counter;
            }
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function counterBukti($idTindakLanjut)
    {
        try {
            $sql = "SELECT
            CONCAT(b.no_tindak_lanjut,'.',COUNT(a.id)+1) AS counter
            FROM bukti a 
            RIGHT JOIN tindak_lanjut b ON b.id=a.id_tindak_lanjut
            WHERE b.id=?";
            $query = $this->query($sql, [$idTindakLanjut]);
            $data = $query->getRow();
            if (isset($data)) {
                return $data->counter;
            }
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
