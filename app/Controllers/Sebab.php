<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\SebabModel;

class Sebab extends BaseController
{

    protected $sebabModel;

    public function __construct()
    {
        $this->sebabModel = new SebabModel();
    }

    public function index($idLaporan)
    {

        // session()->set('id_wilayah', $idWilayah);
        session()->set('id_laporan', $idLaporan);
        session()->set('id_sebab', '');
        session()->set('id_rekomendasi', '');
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $data = [
            'title' => 'Sebab',
            'active' => 'sebab',
            'id_laporan' => $idLaporan
        ];
        return view('sebab/index', $data);
    }

    public function datatables($idLaporan)
    {
        $table =
            "
            (
                SELECT 
                 a.id,
                 a.no_sebab,
                 a.memo_sebab,
                 a.jenis_sebab,
                 a.nilai_sebab,
                 a.id_laporan 
                FROM sebab a
                WHERE a.deleted_at IS NULL 
                AND a.id_laporan='" . $idLaporan . "'
                ORDER BY a.no_sebab ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_sebab', 'dt' => 1),
            array('db' => 'memo_sebab', 'dt' => 2),
            array('db' => 'jenis_sebab', 'dt' => 3),
            array(
                'db'        => 'nilai_sebab',
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
                    <a href="' . base_url('sebab/edit/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">
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
            'title' => 'Buat Sebab Baru',
            'active' => 'sebab',
            'id_laporan' => $idLaporan,
            'validation' => \Config\Services::validation()
        ];
        return view('sebab/create', $data);
    }

    public function save()
    {
        $idLaporan = $this->request->getVar('id_laporan');
        if (!$this->validate([
            'no_sebab' => [
                'rules' => 'required|is_unique[sebab.no_sebab]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'memo_sebab' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/sebab/create/' . $idLaporan)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->sebabModel->insert([
                'id' => get_uuid(),
                'no_sebab' => $this->request->getVar('no_sebab'),
                'memo_sebab' => $this->request->getVar('memo_sebab'),
                'jenis_sebab' => $this->request->getVar('jenis_sebab'),
                'nilai_sebab' => $this->request->getVar('nilai_sebab'),
                'id_laporan' => $this->request->getVar('id_laporan')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/sebab/create/' . $idLaporan)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/sebab/create/' . $idLaporan)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/sebab/index/' . $idLaporan);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Sebab',
            'active' => 'sebab',
            'data' => $this->sebabModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('sebab/edit', $data);
    }

    public function update($id)
    {

        $idLaporan = $this->request->getVar('id_laporan');

        $validation = [
            'no_sebab' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'memo_sebab' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/sebab/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'no_sebab' => $this->request->getVar('no_sebab'),
                    'memo_sebab' => $this->request->getVar('memo_sebab'),
                    'jenis_sebab' => $this->request->getVar('jenis_sebab'),
                    'nilai_sebab' => $this->request->getVar('nilai_sebab'),
                    'id_laporan' => $this->request->getVar('id_laporan')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->sebabModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/sebab/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/sebab/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/sebab/index/' . $idLaporan);
        }
    }
}
