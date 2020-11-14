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
                a.id,
                CONCAT(a.product_name,' - ',a.color_name) AS product_name,
                b.safety_stock,
                COUNT(a.id) AS recent_stock
                FROM stock a
                JOIN product_colors b ON b.id=a.id_product_color
                WHERE a.deleted_at IS NULL
                AND b.deleted_at IS NULL
                AND a.location='IN'
                GROUP BY b.id 
                HAVING (COUNT(a.id)< b.safety_stock)
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'product_name', 'dt' => 1),
            array('db' => 'safety_stock', 'dt' => 2),
            array('db' => 'recent_stock', 'dt' => 3)
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }

    function singleMovingAverage(array $data, $range)
    {
        $sum = array_sum(array_slice($data, 0, $range));

        $result = array($range - 1 => $sum / $range);

        for ($i = $range, $n = count($data); $i != $n; ++$i) {
            $result[$i] = $result[$i - 1] + ($data[$i] - $data[$i - $range]) / $range;
        }

        return $result;
    }

    function getDataPieChartStock()
    {
        $location = $this->request->getVar('stock_type');
        $result = $this->dashboardModel->getDetailStock($location);

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

    function getDataSales()
    {
        $idProductColor = $this->request->getVar('id_product_color');
        $result = $this->dashboardModel->getDetailSales($idProductColor);
        // dd($result);

        $series = [];
        $data = [];
        $sma = [];
        $productName = '';
        $colorCode = '';
        foreach ($result as $r) {
            array_push($series, $r->bulan);
            array_push($data, $r->total);
            $productName = $r->product_name;
            $colorCode = $r->color_code;
        }

        /*FORCAST */
        if (sizeof($data) > 3) {
            $tmp = array(null, null, null);
            $tmp[0] = $data[0];
            $tmp[1] = $data[0];
            $tmp[2] = ($data[0] + $data[1]) / 2;
            $res = $this->singleMovingAverage($data, 3);
            $sma = array_merge($tmp, $res);
            array_push($series, 'forecast');
        }
        /*FORCAST */

        $response['series'] = $series;
        $response['data'] = $data;
        $response['forecast'] = $sma;
        $response['product_name'] = $productName;
        $response['color_code'] = $colorCode;

        echo json_encode($response);
    }

    public function datatables_stock()
    {
        $table =
            "
            (
                SELECT
                COUNT(a.id) AS total,
                a.id,
                a.stock_code,
                a.barcode,
                a.location,
                a.price,
                a.buy_price,
                a.sell_price,
                a.id_product_color,
                a.id_color,
                a.qty_yard,
                a.id_product,
                a.color_code,
                a.color_name,
                a.product_name,
                CONCAT(a.product_name,' - ',a.color_name) AS `name`,
                a.product_code,
                a.id_order,
                a.id_order_detail,
                a.order_number,
                a.order_date,
                a.id_transfer,
                a.id_transfer_detail,
                a.transfer_number,
                a.transfer_date
                FROM stock a 
                WHERE location='OUT'
                GROUP BY a.id_product_color
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'name', 'dt' => 1),
            array(
                'db'        => 'color_code',
                'dt'        => 2,
                'formatter' => function ($i, $row) {
                    $html = '<center><div style="background:' . $i . ';width: auto;border-radius: 5px;border-style: groove;">&nbsp;</div></center>';
                    $html = ($i) ? $html : '';
                    return $html;
                }
            ),
            array('db' => 'total', 'dt' => 3),
            array(
                'db'        => 'id_product_color',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $i = "'" . $i . "'";
                    $html = '
                    <center>
                        <button name="" onclick="forecast(' . $i . ')" value="' . $i . '" title="View Forecast" class="badge badge-info">View Forecast</button>
                    </center>';
                    return $html;
                }
            ),
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }
}
