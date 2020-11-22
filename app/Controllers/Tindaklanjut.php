<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\TindaklanjutModel;

class Tindaklanjut extends BaseController
{

    protected $tindaklanjutModel;

    public function __construct()
    {
        $this->tindaklanjutModel = new TindaklanjutModel();
    }

    public function index($idRekomendasi)
    {

        // session()->set('id_wilayah', $idWilayah);
        // session()->set('id_laporan', $idLaporan);
        // session()->set('id_temuan', $idTemuan);
        // session()->set('id_sebab', $idSebab);
        session()->set('id_rekomendasi', $idRekomendasi);
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $data = [
            'title' => 'Tindak Lanjut',
            'active' => 'tindaklanjut',
            'id_rekomendasi' => $idRekomendasi
        ];
        return view('tindak_lanjut/index', $data);
    }

    public function datatables($idRekomendasi)
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.nilai_rekomendasi,
                a.nilai_sisa_rekomendasi,
                a.nilai_akhir_rekomendasi,
                a.id_rekomendasi
                FROM tindak_lanjut a
                WHERE a.deleted_at IS NULL 
                AND a.id_rekomendasi='" . $idRekomendasi . "'
                ORDER BY a.nilai_rekomendasi ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array(
                'db'        => 'nilai_rekomendasi',
                'dt'        => 1,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'nilai_akhir_rekomendasi',
                'dt'        => 2,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'nilai_sisa_rekomendasi',
                'dt'        => 3,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <a href="' . base_url('bukti/index/' . $i) . '" class="btn btn-success btn-small" data-original-title="Edit">
                    Bukti
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

    public function create($idRekomendasi)
    {

        $data = [
            'title' => 'Buat Tindak Lanjut Baru',
            'active' => 'tindaklanjut',
            'id_rekomendasi' => $idRekomendasi,
            'validation' => \Config\Services::validation()
        ];
        return view('tindak_lanjut/create', $data);
    }

    public function save()
    {
        $idRekomendasi = $this->request->getVar('id_rekomendasi');
        if (!$this->validate([
            'nilai_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'nilai_akhir_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/tindaklanjut/create/' . $idRekomendasi)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->tindaklanjutModel->insert([
                'id' => get_uuid(),
                'nilai_rekomendasi' => $this->request->getVar('nilai_rekomendasi'),
                'nilai_sisa_rekomendasi' => $this->request->getVar('nilai_sisa_rekomendasi'),
                'nilai_akhir_rekomendasi' => $this->request->getVar('nilai_akhir_rekomendasi'),
                'id_rekomendasi' => $this->request->getVar('id_rekomendasi')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/tindaklanjut/create/' . $idRekomendasi)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/tindaklanjut/create/' . $idRekomendasi)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/tindaklanjut/index/' . $idRekomendasi);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Tindaklanjut',
            'active' => 'tindaklanjut',
            'data' => $this->tindaklanjutModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('tindak_lanjut/edit', $data);
    }

    public function update($id)
    {

        $idRekomendasi = $this->request->getVar('id_rekomendasi');

        $validation = [
            'nilai_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nilai_akhir_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/tindaklanjut/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'nilai_rekomendasi' => $this->request->getVar('nilai_rekomendasi'),
                    'nilai_sisa_rekomendasi' => $this->request->getVar('nilai_sisa_rekomendasi'),
                    'nilai_akhir_rekomendasi' => $this->request->getVar('nilai_akhir_rekomendasi'),
                    'id_rekomendasi' => $this->request->getVar('id_rekomendasi')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->tindaklanjutModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/tindaklanjut/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/tindaklanjut/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/tindaklanjut/index/' . $idRekomendasi);
        }
    }
}
