<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\TemuanModel;

class Temuan extends BaseController
{

    protected $temuanModel;

    public function __construct()
    {
        $this->temuanModel = new TemuanModel();
    }

    public function index($idLaporan)
    {

        // session()->set('id_wilayah', $idWilayah);
        session()->set('id_laporan', $idLaporan);
        session()->set('id_temuan', '');
        session()->set('id_rekomendasi', '');
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $data = [
            'title' => 'Temuan',
            'active' => 'temuan',
            'id_laporan' => $idLaporan
        ];
        return view('temuan/index', $data);
    }

    public function datatables($idLaporan)
    {
        $table =
            "
            (
                SELECT 
                 a.id,
                 a.no_temuan,
                 a.memo_temuan,
                 a.jenis_temuan,
                 a.nilai_temuan,
                 a.id_laporan 
                FROM temuan a
                WHERE a.deleted_at IS NULL 
                AND a.id_laporan='" . $idLaporan . "'
                ORDER BY a.no_temuan ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_temuan', 'dt' => 1),
            array('db' => 'memo_temuan', 'dt' => 2),
            array('db' => 'jenis_temuan', 'dt' => 3),
            array(
                'db'        => 'nilai_temuan',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'id',
                'dt'        => 5,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <a href="' . base_url('temuan/edit/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">
                    Edit
                    </a>
                    <a href="' . base_url('rekomendasi/index/' . $i) . '" class="btn btn-success btn-small" data-original-title="Edit">
                    Rekomendasi
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

    public function create($idLaporan)
    {

        $data = [
            'title' => 'Buat Temuan Baru',
            'active' => 'temuan',
            'id_laporan' => $idLaporan,
            'validation' => \Config\Services::validation()
        ];
        return view('temuan/create', $data);
    }

    public function save()
    {
        $idLaporan = $this->request->getVar('id_laporan');
        if (!$this->validate([
            'no_temuan' => [
                'rules' => 'required|is_unique[temuan.no_temuan]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'memo_temuan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/temuan/create/' . $idLaporan)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->temuanModel->insert([
                'id' => get_uuid(),
                'no_temuan' => $this->request->getVar('no_temuan'),
                'memo_temuan' => $this->request->getVar('memo_temuan'),
                'jenis_temuan' => $this->request->getVar('jenis_temuan'),
                'nilai_temuan' => $this->request->getVar('nilai_temuan'),
                'id_laporan' => $this->request->getVar('id_laporan')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/temuan/create/' . $idLaporan)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/temuan/create/' . $idLaporan)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/temuan/index/' . $idLaporan);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Temuan',
            'active' => 'temuan',
            'data' => $this->temuanModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('temuan/edit', $data);
    }

    public function update($id)
    {

        $idLaporan = $this->request->getVar('id_laporan');

        $validation = [
            'no_temuan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'memo_temuan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/temuan/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'no_temuan' => $this->request->getVar('no_temuan'),
                    'memo_temuan' => $this->request->getVar('memo_temuan'),
                    'jenis_temuan' => $this->request->getVar('jenis_temuan'),
                    'nilai_temuan' => $this->request->getVar('nilai_temuan'),
                    'id_laporan' => $this->request->getVar('id_laporan')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->temuanModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/temuan/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/temuan/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/temuan/index/' . $idLaporan);
        }
    }
}
