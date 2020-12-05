<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{

    protected $table      = 'laporan';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id'
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

    public function getSummaryStatusTLPieChart()
    {
        try {
            $sql = "SELECT 
                    CASE b.status 
                    WHEN 'BELUM_SESUAI' THEN 'Belum Sesuai' 
                    WHEN 'SESUAI' THEN 'Sesuai'
                    WHEN 'BELUM_TL' THEN 'Belum ditindaklanjuti' 
                    WHEN 'TIDAK_DAPAT_DI_TL' THEN 'Tidak Dapat ditindaklanjuti'
                    ELSE b.status 
                    END AS `name`,
                    CASE b.status 
                    WHEN 'BELUM_SESUAI' THEN '#deda50' 
                    WHEN 'SESUAI' THEN '#88bd91'
                    WHEN 'BELUM_TL' THEN '#c5c5c2' 
                    WHEN 'TIDAK_DAPAT_DI_TL' THEN '#ff6565'
                    ELSE b.status 
                    END AS `color_code`,
                    COUNT(b.status) AS `value`
                    FROM rekomendasi b 
                    JOIN sebab c ON c.id=b.id_sebab 
                    JOIN temuan d ON d.id=c.id_temuan
                    GROUP BY b.status";
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

    public function getSummaryTL()
    {
        try {
            $sql = "SELECT 
                    (
                    SELECT 
                    COUNT(b.status) 
                    FROM rekomendasi b 
                    JOIN sebab c ON c.id=b.id_sebab 
                    JOIN temuan d ON d.id=c.id_temuan
                    WHERE b.status='SESUAI'
                    ) AS sesuai,
                    (
                    SELECT 
                    COUNT(b.status) 
                    FROM rekomendasi b 
                    JOIN sebab c ON c.id=b.id_sebab 
                    JOIN temuan d ON d.id=c.id_temuan
                    WHERE b.status='BELUM_SESUAI'
                    ) AS belum_sesuai,
                    (
                    SELECT 
                    COUNT(b.status) 
                    FROM rekomendasi b 
                    JOIN sebab c ON c.id=b.id_sebab 
                    JOIN temuan d ON d.id=c.id_temuan
                    WHERE b.status='BELUM_TL'
                    ) AS belum_ditindak_lanjuti,
                    (
                    SELECT 
                    COUNT(b.status) 
                    FROM rekomendasi b 
                    JOIN sebab c ON c.id=b.id_sebab 
                    JOIN temuan d ON d.id=c.id_temuan
                    WHERE b.status='TIDAK_DAPAT_DI_TL'
                    ) AS tidak_dapat_ditindak_lanjuti
                    FROM DUAL";
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
}
