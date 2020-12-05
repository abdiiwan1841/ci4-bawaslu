<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Models\DashboardModel;

class Dashboard extends BaseController
{

    protected $dashboardModel;

    public function __construct()
    {
        $this->dashboardModel = new DashboardModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'active' => 'dashboard'
        ];
        return view('dashboard/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT
                f.`nama` AS satuan_kerja,
                e.`no_laporan`,
                e.`nama_laporan`,
                d.`memo_temuan`,
                b.`no_rekomendasi`,
                b.`memo_rekomendasi`,
                a.id,
                a.`no_tindak_lanjut`,
                a.nilai_rekomendasi,
                a.status,
                a.read_status,
                a.created_at,
                a.id AS id_tindak_lanjut,
                b.id AS id_rekomendasi,
                c.id AS id_sebab,
                d.id AS id_temuan,
                e.id AS id_laporan,
                f.id AS id_satuan_kerja
                FROM tindak_lanjut a 
                JOIN rekomendasi b ON b.`id`=a.`id_rekomendasi`
                JOIN sebab c ON c.`id`=b.`id_sebab` 
                JOIN temuan d ON d.`id`=c.`id_temuan`
                JOIN laporan e ON e.id=d.`id_laporan` 
                JOIN eselon f ON f.`id`=e.`id_satuan_kerja`
                WHERE a.deleted_at IS NULL 
                AND b.deleted_at IS NULL
                AND c.deleted_at IS NULL
                AND d.deleted_at IS NULL
                AND e.deleted_at IS NULL
                AND f.deleted_at IS NULL
                AND a.read_status=0
                ORDER BY a.created_at DESC
            ) temp
            ";

        $columns = array(
            array('db' => 'created_at', 'dt' => 0),
            array('db' => 'satuan_kerja', 'dt' => 1),
            array('db' => 'nama_laporan', 'dt' => 2),
            array('db' => 'memo_temuan', 'dt' => 3),
            array('db' => 'memo_rekomendasi', 'dt' => 4),
            array(
                'db'        => 'nilai_rekomendasi',
                'dt'        => 5,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'id',
                'dt'        => 6,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <form action="' . base_url('tindaklanjut/verifikasi/' . $i) . '" method="post" class="d-inline">
                        ' . csrf_field() . '
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id_satuan_kerja" value="' . $row['id_satuan_kerja'] . '">
                        <input type="hidden" name="id_laporan" value="' . $row['id_laporan'] . '">
                        <input type="hidden" name="id_temuan" value="' . $row['id_temuan'] . '">
                        <input type="hidden" name="id_sebab" value="' . $row['id_sebab'] . '">
                        <input type="hidden" name="id_rekomendasi" value="' . $row['id_rekomendasi'] . '">
                        <input type="hidden" name="id_tindak_lanjut" value="' . $row['id_tindak_lanjut'] . '">
                        <button type="submit" class="btn btn-success btn-small">Verifikasi</button>
                    </form>
                    </center>';
                    return $html;
                }
            ),
            array('db' => 'id_satuan_kerja', 'dt' => 'id_satuan_kerja'),
            array('db' => 'id_laporan', 'dt' => 'id_laporan'),
            array('db' => 'id_temuan', 'dt' => 'id_temuan'),
            array('db' => 'id_sebab', 'dt' => 'id_sebab'),
            array('db' => 'id_rekomendasi', 'dt' => 'id_rekomendasi'),
            array('db' => 'id_tindak_lanjut', 'dt' => 'id_tindak_lanjut')
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }

    function getDataPieChart()
    {
        $result = $this->dashboardModel->getSummaryStatusTLPieChart();

        $colors = [];
        $series = [];

        foreach ($result as $r) {
            array_push($series, $r);
            array_push($colors, $r->color_code);
        }

        $data['colors'] = $colors;
        $data['series'] = $series;

        echo json_encode($data);
    }
}
