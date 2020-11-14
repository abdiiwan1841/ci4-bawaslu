<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{

    protected $table      = 'stock';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'id',
        'stock_code',
        'barcode',
        'location',
        'price',
        'buy_price',
        'sell_price',
        'id_product_color',
        'id_color',
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
        'transfer_date',
        'id_retur_in',
        'id_retur_in_detail',
        'retur_in_number',
        'retur_in_date',
        'id_retur_out',
        'id_retur_out_detail',
        'retur_out_number',
        'retur_out_date',
        'remarks',
        'barcode_printed'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // protected $validationRules    = [];
    // protected $validationMessages = [];
    // protected $skipValidation     = false;

    public function getData()
    {
        $this->select('id,
        stock_code,
        barcode,
        location,
        price,
        id_product_color,
        id_color,
        qty_yard,
        created_at,
        updated_at,
        deleted_at,
        created_by,
        updated_by,
        deleted,
        id_product,
        color_code,
        color_name,
        product_name,
        product_code,
        id_incoming,
        id_incoming_detail,
        incoming_number,
        incoming_date,
        id_order,
        id_order_detail,
        order_number,
        order_date,
        id_transfer,
        id_transfer_detail,
        transfer_number,
        transfer_date,        
        id_retur_in,
        id_retur_in_detail,
        retur_in_number,
        retur_in_date,
        id_retur_out,
        id_retur_out_detail,
        retur_out_number,
        retur_out_date,
        remarks');
        $this->orderBy('stock_code', 'DESC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataByLocation($location = '', $keywords = '')
    {
        $this->select('id,
        stock_code,
        barcode,
        location,
        price,
        id_product_color,
        id_color,
        qty_yard,
        created_at,
        updated_at,
        deleted_at,
        created_by,
        updated_by,
        deleted,
        id_product,
        color_code,
        color_name,
        product_name,
        product_code,
        id_incoming,
        id_incoming_detail,
        incoming_number,
        incoming_date,
        id_order,
        id_order_detail,
        order_number,
        order_date,
        id_transfer,
        id_transfer_detail,
        transfer_number,
        transfer_date,        
        id_retur_in,
        id_retur_in_detail,
        retur_in_number,
        retur_in_date,
        id_retur_out,
        id_retur_out_detail,
        retur_out_number,
        retur_out_date,
        remarks');
        if ($location != '') {
            $this->where('location', $location);
        }
        if ($keywords != '') {
            $this->like(
                'id',
                $keywords
            )->orLike(
                'stock_code',
                $keywords
            )->orLike(
                'barcode',
                $keywords
            )->orLike(
                'location',
                $keywords
            )->orLike(
                'price',
                $keywords
            )->orLike(
                'id_product_color',
                $keywords
            )->orLike(
                'id_color',
                $keywords
            )->orLike(
                'qty_yard',
                $keywords
            )->orLike(
                'id_product',
                $keywords
            )->orLike(
                'color_code',
                $keywords
            )->orLike(
                'color_name',
                $keywords
            )->orLike(
                'product_name',
                $keywords
            )->orLike(
                'product_code',
                $keywords
            )->orLike(
                'id_incoming',
                $keywords
            )->orLike(
                'id_incoming_detail',
                $keywords
            )->orLike(
                'incoming_number',
                $keywords
            )->orLike(
                'incoming_date',
                $keywords
            )->orLike(
                'id_order',
                $keywords
            )->orLike(
                'id_order_detail',
                $keywords
            )->orLike(
                'order_number',
                $keywords
            )->orLike(
                'order_date',
                $keywords
            )->orLike(
                'id_transfer',
                $keywords
            )->orLike(
                'id_transfer_detail',
                $keywords
            )->orLike(
                'transfer_number',
                $keywords
            )->orLike(
                'transfer_date',
                $keywords
            )->orLike(
                'id_retur_in',
                $keywords
            )->orLike(
                'id_retur_in_detail',
                $keywords
            )->orLike(
                'retur_in_number',
                $keywords
            )->orLike(
                'retur_in_date',
                $keywords
            )->orLike(
                'id_retur_out',
                $keywords
            )->orLike(
                'id_retur_out_detail',
                $keywords
            )->orLike(
                'retur_out_number',
                $keywords
            )->orLike(
                'retur_out_date',
                $keywords
            )->orLike('remarks', $keywords);
        }
        $this->orderBy('stock_code', 'DESC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataById($id)
    {
        $this->select('a.id,
        a.stock_code,
        a.barcode,
        a.location,
        a.id_product_color,
        a.id_color,
        a.qty_yard,
        a.created_at,
        a.updated_at,
        a.deleted_at,
        a.created_by,
        a.updated_by,
        a.deleted,
        a.id_product,
        a.color_code,
        a.color_name,
        a.product_name,
        a.product_code,
        a.id_incoming,
        a.id_incoming_detail,
        a.incoming_number,
        a.incoming_date,
        a.id_order,
        a.id_order_detail,
        a.order_number,
        a.order_date,
        a.id_transfer,
        a.id_transfer_detail,
        a.transfer_number,
        a.transfer_date,        
        a.id_retur_in,
        a.id_retur_in_detail,
        a.retur_in_number,
        a.retur_in_date,
        a.id_retur_out,
        a.id_retur_out_detail,
        a.retur_out_number,
        a.retur_out_date,
        a.remarks,
        b.sell_price');
        $this->from('stock a');
        $this->join('product_colors b', 'b.id=a.id_product_color');
        $this->where('a.id', $id);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataByBarcode($barcode, $location)
    {
        $this->select('id,
        stock_code,
        barcode,
        location,
        price,
        id_product_color,
        id_color,
        qty_yard,
        created_at,
        updated_at,
        deleted_at,
        created_by,
        updated_by,
        deleted,
        id_product,
        color_code,
        color_name,
        product_name,
        product_code,
        id_incoming,
        id_incoming_detail,
        incoming_number,
        incoming_date,
        id_order,
        id_order_detail,
        order_number,
        order_date,
        id_transfer,
        id_transfer_detail,
        transfer_number,
        transfer_date,        
        id_retur_in,
        id_retur_in_detail,
        retur_in_number,
        retur_in_date,
        id_retur_out,
        id_retur_out_detail,
        retur_out_number,
        retur_out_date,
        remarks');
        $this->where('barcode', $barcode);
        $this->where('location', $location);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataByIdIncomingDetail($id)
    {
        $this->select('id,
        stock_code,
        barcode,
        location,
        price,
        id_product_color,
        id_color,
        qty_yard,
        created_at,
        updated_at,
        deleted_at,
        created_by,
        updated_by,
        deleted,
        id_product,
        color_code,
        color_name,
        product_name,
        product_code,
        id_incoming,
        id_incoming_detail,
        incoming_number,
        incoming_date,
        id_order,
        id_order_detail,
        order_number,
        order_date,
        id_transfer,
        id_transfer_detail,
        transfer_number,
        transfer_date,        
        id_retur_in,
        id_retur_in_detail,
        retur_in_number,
        retur_in_date,
        id_retur_out,
        id_retur_out_detail,
        retur_out_number,
        retur_out_date,
        remarks');
        $this->where('id_incoming_detail', $id);
        $this->orderBy('stock_code', 'DESC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataByIdOrderDetail($id)
    {
        $this->select('id,
        stock_code,
        barcode,
        location,
        price,
        id_product_color,
        id_color,
        qty_yard,
        created_at,
        updated_at,
        deleted_at,
        created_by,
        updated_by,
        deleted,
        id_product,
        color_code,
        color_name,
        product_name,
        product_code,
        id_incoming,
        id_incoming_detail,
        incoming_number,
        incoming_date,
        id_order,
        id_order_detail,
        order_number,
        order_date,
        id_transfer,
        id_transfer_detail,
        transfer_number,
        transfer_date,        
        id_retur_in,
        id_retur_in_detail,
        retur_in_number,
        retur_in_date,
        id_retur_out,
        id_retur_out_detail,
        retur_out_number,
        retur_out_date,
        remarks');
        $this->where('id_order_detail', $id);
        $this->orderBy('stock_code', 'DESC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataByIdTransferDetail($id)
    {
        $this->select('id,
        stock_code,
        barcode,
        location,
        price,
        id_product_color,
        id_color,
        qty_yard,
        created_at,
        updated_at,
        deleted_at,
        created_by,
        updated_by,
        deleted,
        id_product,
        color_code,
        color_name,
        product_name,
        product_code,
        id_incoming,
        id_incoming_detail,
        incoming_number,
        incoming_date,
        id_order,
        id_order_detail,
        order_number,
        order_date,
        id_transfer,
        id_transfer_detail,
        transfer_number,
        transfer_date,        
        id_retur_in,
        id_retur_in_detail,
        retur_in_number,
        retur_in_date,
        id_retur_out,
        id_retur_out_detail,
        retur_out_number,
        retur_out_date,
        remarks');
        $this->where('id_transfer_detail', $id);
        $this->orderBy('stock_code', 'DESC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataByIdReturInDetail($id)
    {
        $this->select('id,
        stock_code,
        barcode,
        location,
        price,
        id_product_color,
        id_color,
        qty_yard,
        created_at,
        updated_at,
        deleted_at,
        created_by,
        updated_by,
        deleted,
        id_product,
        color_code,
        color_name,
        product_name,
        product_code,
        id_incoming,
        id_incoming_detail,
        incoming_number,
        incoming_date,
        id_order,
        id_order_detail,
        order_number,
        order_date,
        id_transfer,
        id_transfer_detail,
        transfer_number,
        transfer_date,        
        id_retur_in,
        id_retur_in_detail,
        retur_in_number,
        retur_in_date,
        id_retur_out,
        id_retur_out_detail,
        retur_out_number,
        retur_out_date,
        remarks');
        $this->where('id_retur_in_detail', $id);
        $this->orderBy('stock_code', 'DESC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getDataByIdReturOutDetail($id)
    {
        $this->select('id,
        stock_code,
        barcode,
        location,
        price,
        id_product_color,
        id_color,
        qty_yard,
        created_at,
        updated_at,
        deleted_at,
        created_by,
        updated_by,
        deleted,
        id_product,
        color_code,
        color_name,
        product_name,
        product_code,
        id_incoming,
        id_incoming_detail,
        incoming_number,
        incoming_date,
        id_order,
        id_order_detail,
        order_number,
        order_date,
        id_transfer,
        id_transfer_detail,
        transfer_number,
        transfer_date,        
        id_retur_in,
        id_retur_in_detail,
        retur_in_number,
        retur_in_date,
        id_retur_out,
        id_retur_out_detail,
        retur_out_number,
        retur_out_date,
        remarks');
        $this->where('id_retur_out_detail', $id);
        $this->orderBy('stock_code', 'DESC');
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function getStockCode($idProductColor)
    {
        // $this->select("CONCAT(DATE_FORMAT(NOW(),'%Y%m%d'),LPAD(COUNT(id)+1,3,'0')) AS 'stock_code'");
        $this->select("COUNT(id) AS 'counter'");
        $this->table("stock");
        $this->where("DATE_FORMAT(incoming_date,'%Y-%m-%d')", date('Y-m-d'));
        $this->where("id_product_color", $idProductColor);
        $query = $this->get();
        $data = $query->getRow();
        if (isset($data)) {
            return $data->counter;
        }
        return 0;
    }



    public function getStockPrintBarcode($ids)
    {
        $this->select('stock.id,
        stock.stock_code,
        stock.barcode,
        stock.location,
        stock.price,
        stock.id_product_color,
        stock.id_color,
        stock.qty_yard,
        stock.id_product,
        stock.color_code,
        stock.color_name,
        stock.product_name,
        stock.product_code,
        stock.id_incoming,
        stock.id_incoming_detail,
        stock.incoming_number,
        stock.incoming_date,
        b.delivery_note_number');
        $this->join('incoming b', 'b.id=stock.id_incoming');
        $this->whereIn("stock.id", $ids);
        $query = $this->get();
        $data = $query->getResult();
        if (isset($data)) {
            return $data;
        }
        return array();
    }

    public function updateBarcodePrinted($ids)
    {
        try {

            $data = [
                'barcode_printed' => 1
            ];

            $this->whereIn('id', $ids);
            $this->set($data);
            $this->update();

            if ($this->affectedRows() > 0) {
                return true;
            }
        } catch (\Exception $e) {
            //dd($e->getMessage());
            return false;
        }

        return false;
    }
}
