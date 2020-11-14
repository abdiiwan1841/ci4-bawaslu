<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DepartmentModel;

class Department extends BaseController
{
    protected $departmentModel;

    public function __construct()
    {
        $this->departmentModel = new DepartmentModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Departments',
            'active' => 'Department',
            'data' => null
        ];

        return view('department/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.department_code,
                a.department_name,
                a.description
                FROM `department` a
                WHERE a.deleted_at IS NULL
                ORDER BY a.department_name ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'department_code', 'dt' => 1),
            array('db' => 'department_name', 'dt' => 2),
            array('db' => 'description', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('department/detail', $i) . '
                    ' . link_edit('department/edit', $i) . '
                    ' . link_delete('department/delete', $i) . '
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
            'title' => 'Create New Department',
            'active' => 'Department',
            'validation' => \Config\Services::validation()
        ];
        return view('department/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'department_name' => [
                'rules' => 'required|is_unique[department.department_name]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/department/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $id = $this->departmentModel->counterID();

            $this->departmentModel->insert([
                'id' => $id,
                'department_name' => $this->request->getVar('department_name'),
                'description' => $this->request->getVar('description')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/department/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/department/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/department');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Department',
            'active' => 'Department',
            'data' => $this->departmentModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('department/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'department_name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/department/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'department_name' => $this->request->getVar('department_name'),
                    'description' => $this->request->getVar('description')
                ];

                /*Update data ke table Departments berdasarkan ID */
                $this->departmentModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/department/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/department/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/department');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Department',
            'active' => 'Department',
            'data' => $this->departmentModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Department Data ' . $id . ' is not found.');
        }
        return view('department/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->departmentModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/department')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/department')->with('messages', $e->getMessage());
        }

        return redirect()->to('/department');
    }
}
