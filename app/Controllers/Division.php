<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DivisionModel;

class Division extends BaseController
{
    protected $divisionModel;

    public function __construct()
    {
        $this->divisionModel = new DivisionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Divisions',
            'active' => 'Division',
            'data' => null
        ];

        return view('division/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.division_code,
                a.division_name,
                a.description
                FROM `division` a
                WHERE a.deleted_at IS NULL
                ORDER BY a.division_name ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'division_code', 'dt' => 1),
            array('db' => 'division_name', 'dt' => 2),
            array('db' => 'description', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('division/detail', $i) . '
                    ' . link_edit('division/edit', $i) . '
                    ' . link_delete('division/delete', $i) . '
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

        $data = [
            'title' => 'Create New Division',
            'active' => 'Division',
            'validation' => \Config\Services::validation()
        ];
        return view('division/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'division_name' => [
                'rules' => 'required|is_unique[division.division_name]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/division/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $divisionCode = $this->divisionModel->counterID();

            $this->divisionModel->insert([
                'id' => get_uuid(),
                'division_code' => $divisionCode,
                'division_name' => $this->request->getVar('division_name'),
                'description' => $this->request->getVar('description')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/division/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/division/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/division');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Division',
            'active' => 'Division',
            'data' => $this->divisionModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('division/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'division_name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/division/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'division_name' => $this->request->getVar('division_name'),
                    'description' => $this->request->getVar('description')
                ];

                /*Update data ke table Divisions berdasarkan ID */
                $this->divisionModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/division/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/division/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/division');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Division',
            'active' => 'Division',
            'data' => $this->divisionModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Division Data ' . $id . ' is not found.');
        }
        return view('division/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->divisionModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/devision')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/division')->with('messages', $e->getMessage());
        }

        return redirect()->to('/division');
    }
}
