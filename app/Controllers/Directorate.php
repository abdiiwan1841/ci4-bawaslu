<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DirectorateModel;

class Directorate extends BaseController
{
    protected $directorateModel;

    public function __construct()
    {
        $this->directorateModel = new DirectorateModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Directorates',
            'active' => 'Directorate',
            'data' => null
        ];

        return view('directorate/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.directorate_code,
                a.directorate_name,
                a.description
                FROM `directorate` a
                WHERE a.deleted_at IS NULL
                ORDER BY a.directorate_name ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'directorate_code', 'dt' => 1),
            array('db' => 'directorate_name', 'dt' => 2),
            array('db' => 'description', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('directorate/detail', $i) . '
                    ' . link_edit('directorate/edit', $i) . '
                    ' . link_delete('directorate/delete', $i) . '
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
            'title' => 'Create New Directorate',
            'active' => 'Directorate',
            'validation' => \Config\Services::validation()
        ];
        return view('directorate/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'directorate_name' => [
                'rules' => 'required|is_unique[directorate.directorate_name]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/directorate/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $id = $this->directorateModel->counterID();

            $this->directorateModel->insert([
                'id' => $id,
                'directorate_name' => $this->request->getVar('directorate_name'),
                'description' => $this->request->getVar('description')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/directorate/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/directorate/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/directorate');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Directorate',
            'active' => 'Directorate',
            'data' => $this->directorateModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('directorate/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'directorate_name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/directorate/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'directorate_name' => $this->request->getVar('directorate_name'),
                    'description' => $this->request->getVar('description')
                ];

                /*Update data ke table Directorates berdasarkan ID */
                $this->directorateModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/directorate/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/directorate/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/directorate');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Directorate',
            'active' => 'Directorate',
            'data' => $this->directorateModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Directorate Data ' . $id . ' is not found.');
        }
        return view('directorate/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->directorateModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/directorate')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/directorate')->with('messages', $e->getMessage());
        }

        return redirect()->to('/directorate');
    }
}
