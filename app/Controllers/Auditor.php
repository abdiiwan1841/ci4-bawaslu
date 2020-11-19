<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\AuditorModel;
use App\Models\GroupModel;

class Auditor extends BaseController
{

    protected $auditorModel;
    protected $groupModel;

    public function __construct()
    {
        $this->auditorModel = new AuditorModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Auditor',
            'active' => 'auditor'
        ];
        return view('auditor/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                 a.id,
                 a.nip,
                 a.nama
                FROM auditor a
                WHERE a.deleted_at IS NULL
                ORDER BY a.nama ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'nip', 'dt' => 1),
            array('db' => 'nama', 'dt' => 2),
            array(
                'db'        => 'id',
                'dt'        => 3,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('auditor/detail', $i) . '
                    ' . link_edit('auditor/edit', $i) . '
                    ' . link_delete('auditor/delete', $i) . '
                    </center>';
                    return $html;
                }
            ),
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }

    public function create()
    {
        $groups_options = array();

        $groups = $this->groupModel->getData();
        foreach ($groups as $r) {
            $groups_options[$r->id] = $r->name;
        }

        $data = [
            'title' => 'Buat Auditor Baru',
            'active' => 'auditor',
            'groups_options' => $groups_options,
            'validation' => \Config\Services::validation()
        ];
        return view('auditor/create', $data);
    }

    public function save()
    {
        $idTemuan = $this->request->getVar('id_temuan');
        if (!$this->validate([
            'no_auditor' => [
                'rules' => 'required|is_unique[auditor.no_auditor]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'memo_auditor' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/auditor/create/' . $idTemuan)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->auditorModel->insert([
                'id' => get_uuid(),
                'no_auditor' => $this->request->getVar('no_auditor'),
                'memo_auditor' => $this->request->getVar('memo_auditor'),
                'id_temuan' => $this->request->getVar('id_temuan')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/auditor/create/' . $idTemuan)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/auditor/create/' . $idTemuan)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/auditor/index/' . $idTemuan);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Auditor',
            'active' => 'auditor',
            'data' => $this->auditorModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('auditor/edit', $data);
    }

    public function update($id)
    {

        $idTemuan = $this->request->getVar('id_temuan');

        $validation = [
            'no_auditor' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'memo_auditor' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/auditor/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'no_auditor' => $this->request->getVar('no_auditor'),
                    'memo_auditor' => $this->request->getVar('memo_auditor'),
                    'id_temuan' => $this->request->getVar('id_temuan')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->auditorModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/auditor/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/auditor/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/auditor/index/' . $idTemuan);
        }
    }
}
