<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Models;

use CodeIgniter\Model;
use stdClass;

class SessionTrackingModel extends Model
{
    public $id_wilayah;
    public $id_laporan;
    public $id_temuan;
    public $id_rekomendasi;
    public $id_tindak_lanjut;
    public $id_bukti;


    public function getIdWilayah()
    {
        $value = isset($_SESSION['tracking']['id_wilayah']) ? $_SESSION['tracking']['id_wilayah'] : '';
        return $value;
    }

    public function setIdWilayah($value)
    {
        $_SESSION['tracking']['id_wilayah'] = $value;
    }

    public function getIdLaporan()
    {
        $data = isset($_SESSION['tracking']['id_laporan']) ? $_SESSION['tracking']['id_laporan'] : '';
        return $data;
    }

    public function setIdLaporan($value)
    {
        $_SESSION['tracking']['id_laporan'] = $value;
    }

    public function getIdTemuan()
    {
        $data = isset($_SESSION['tracking']['id_temuan']) ? $_SESSION['tracking']['id_temuan'] : '';
        return $data;
    }

    public function setIdTemuan($value)
    {
        $_SESSION['tracking']['id_temuan'] = $value;
    }

    public function getIdRekomendasi()
    {
        $data = isset($_SESSION['tracking']['id_rekomendasi']) ? $_SESSION['tracking']['id_rekomendasi'] : '';
        return $data;
    }

    public function setIdRekomendasi($value)
    {
        $_SESSION['tracking']['id_rekomendasi'] = $value;
    }

    public function getIdTindakLanjut()
    {
        $data = isset($_SESSION['tracking']['id_tindak_lanjut']) ? $_SESSION['tracking']['id_tindak_lanjut'] : '';
        return $data;
    }

    public function setIdTindakLanjut($value)
    {
        $_SESSION['tracking']['id_tindak_lanjut'] = $value;
    }

    public function getIdBukti()
    {
        $data = isset($_SESSION['tracking']['id_bukti']) ? $_SESSION['tracking']['id_bukti'] : '';
        return $data;
    }

    public function setIdBukti($value)
    {
        $_SESSION['tracking']['id_bukti'] = $value;
    }
}
