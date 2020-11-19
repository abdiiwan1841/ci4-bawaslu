<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\LaporanAuditeeModel;

class Laporanauditee extends BaseController
{

    protected $laporanAuditeeModel;
    protected $sessionTrackingModel;

    public function __construct()
    {
        $this->laporanAuditeeModel = new LaporanAuditeeModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan Auditee',
            'active' => 'laporan'
        ];
        return view('laporan_auditee/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.no_laporan,
                a.tanggal_laporan,
                a.nama_laporan,
                '0' AS sesuai,
                '0' AS belum_sesuai,
                '0' AS belum_ditindak_lanjuti,
                '0' AS tidak_dapat_ditindak_lanjuti
                FROM laporan a
                WHERE a.deleted_at IS NULL
                ORDER BY a.nama_laporan ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_laporan', 'dt' => 1),
            array('db' => 'tanggal_laporan', 'dt' => 2),
            array('db' => 'nama_laporan', 'dt' => 3),
            array('db' => 'sesuai', 'dt' => 4),
            array('db' => 'belum_sesuai', 'dt' => 5),
            array('db' => 'belum_ditindak_lanjuti', 'dt' => 6),
            array('db' => 'tidak_dapat_ditindak_lanjuti', 'dt' => 7),
            array(
                'db'        => 'id',
                'dt'        => 8,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <a href="' . base_url('laporanauditee/detail/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">
                    Detail
                    </a>
                    </center>';
                    return $html;
                }
            ),
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Laporan Auditee',
            'active' => 'Laporan Auditee',
            'data' => $this->laporanAuditeeModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);

        return view('laporan_auditee/detail', $data);
    }

    public function tindaklanjut($idRekomendasi)
    {
        $data = [
            'title' => 'Detail Laporan Auditee',
            'active' => 'Laporan Auditee',
            'data' => $this->laporanAuditeeModel->getTindakLanjut($idRekomendasi),
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);

        return view('laporan_auditee/tindaklanjut', $data);
    }
}
