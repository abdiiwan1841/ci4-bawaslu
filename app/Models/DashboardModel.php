<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{

    protected $table      = 'stock';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id',
        'stock_code',
        'barcode',
        'location',
        'price',
        'id_product_color',
        'id_color',
        'qty_yard_per_piece',
        'qty_yard',
        'id_product',
        'color_code',
        'color_name',
        'product_name',
        'product_code',
        'id_incoming',
        'id_incoming_detail',
        'incoming_number',
        'incoming_date',
        'id_order',
        'id_order_detail',
        'order_number',
        'order_date',
        'id_transfer',
        'id_transfer_detail',
        'transfer_number',
        'transfer_date'
    ];

    protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function getStock()
    {
        try {
            $sql = "SELECT
                (SELECT COUNT(location) FROM stock WHERE location='IN') AS 'stock_in',
                (SELECT COUNT(location) FROM stock WHERE location='TAMIM') AS 'stock_tamim',
                (SELECT COUNT(location) FROM stock WHERE location='OUT') AS 'stock_out'
                FROM DUAL";
            $query = $this->query($sql);
            $data = $query->getRow();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getDetailStock($location)
    {
        try {
            $sql = "SELECT
                    COUNT(id_product_color) AS `value`,                    
                    CONCAT(product_name,' - ',color_name) AS `name`,
                    color_code
                    FROM stock
                    WHERE location=?
                    AND deleted_at IS NULL
                    GROUP BY id_product_color";
            $query = $this->query($sql, array($location));
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getDetailSales($idProductColor)
    {
        try {
            $sql = "SELECT
                    DATE_FORMAT(order_date,'%Y-%m') AS bulan,
                    COUNT(id) AS total,                    
                    CONCAT(product_name,' - ',color_name) AS product_name,
                    IFNULL(color_code,'#ffffff') AS color_code
                    FROM stock
                    WHERE location='OUT'
                    AND deleted_at IS NULL
                    AND id_product_color=?
                    GROUP BY bulan  
                    ORDER BY bulan ASC";
            $query = $this->query($sql, array($idProductColor));
            $data = $query->getResult();
            if (isset($data)) {
                return $data;
            }
            return array();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getOrderByIProductColor($idProductColor)
    {
        try {
            $sql = "SELECT 
                    b.`order_date`,
                    -- SUM(a.`qty_piece`) AS sum_piece,
                    SUM(a.`qty_yard`) AS sum_yard
                    -- a.id,
                    -- a.`id_order`,
                    -- a.id_stock,
                    -- a.`id_product_color`,
                    -- a.`price`,
                    -- a.`qty_piece`,
                    -- a.`qty_yard`
                    FROM order_detail a
                    JOIN orders b ON b.`id`=a.`id_order`
                    WHERE a.`id_product_color`=?
                    GROUP BY b.`order_date`
                    ORDER BY b.`order_date` DESC";
            $query = $this->query($sql, array($idProductColor));
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
