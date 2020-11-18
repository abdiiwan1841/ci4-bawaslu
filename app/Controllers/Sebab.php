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

    public function index($idTemuan)
    {

        // session()->set('id_wilayah', $idWilayah);
        // session()->set('id_laporan', $idLaporan);
        session()->set('id_temuan', $idTemuan);
        session()->set('id_sebab', '');
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $data = [
            'title' => 'Sebab',
            'active' => 'sebab',
            'id_temuan' => $idTemuan
        ];
        return view('sebab/index', $data);
    }

    public function datatables($idTemuan)
    {
        $table =
            "
            (
                SELECT 
                 a.id,
                 a.no_sebab,
                 a.memo_sebab,
                 a.id_temuan 
                FROM sebab a
                WHERE a.deleted_at IS NULL 
                AND a.id_temuan='" . $idTemuan . "'
                ORDER BY a.no_sebab ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_sebab', 'dt' => 1),
            array('db' => 'memo_sebab', 'dt' => 2),
            array(
                'db'        => 'id',
                'dt'        => 3,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <a href="' . base_url('sebab/edit/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">
                    Edit
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

    public function create($idTemuan)
    {

        $data = [
            'title' => 'Buat Sebab Baru',
            'active' => 'sebab',
            'id_temuan' => $idTemuan,
            'validation' => \Config\Services::validation()
        ];
        return view('sebab/create', $data);
    }

    public function save()
    {
        $idTemuan = $this->request->getVar('id_temuan');
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
            return redirect()->to('/sebab/create/' . $idTemuan)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->sebabModel->insert([
                'id' => get_uuid(),
                'no_sebab' => $this->request->getVar('no_sebab'),
                'memo_sebab' => $this->request->getVar('memo_sebab'),
                'id_temuan' => $this->request->getVar('id_temuan')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/sebab/create/' . $idTemuan)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/sebab/create/' . $idTemuan)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/sebab/index/' . $idTemuan);
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

        $idTemuan = $this->request->getVar('id_temuan');

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
                    'id_temuan' => $this->request->getVar('id_temuan')
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

            return redirect()->to('/sebab/index/' . $idTemuan);
        }
    }
}
