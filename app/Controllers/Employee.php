<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;

class Employee extends BaseController
{
    protected $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Employee',
            'active' => 'Section',
            'data' => null
        ];

        return view('employee/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT
                a.id,
                a.employee_id,
                a.name,
                a.position_code,
                a.position_percent,
                a.section_code,
                b.section_name,
                a.department_code,
                c.department_name,
                a.division_code,
                d.division_name,
                a.directorate_code,
                e.directorate_name,
                a.address,
                a.district,
                a.city,
                a.pob,
                a.dob,
                a.entry_date
                FROM employee a
                LEFT JOIN section b ON b.section_code=a.section_code
                LEFT JOIN department c ON c.department_code=a.department_code
                LEFT JOIN division d ON d.division_code=a.division_code
                LEFT JOIN directorate e ON e.directorate_code=a.directorate_code
                WHERE a.deleted_at IS NULL
                AND b.deleted_at IS NULL
                AND c.deleted_at IS NULL
                AND d.deleted_at IS NULL
                AND e.deleted_at IS NULL
                ORDER BY a.name ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'employee_id', 'dt' => 1),
            array('db' => 'name', 'dt' => 2),
            array('db' => 'position_code', 'dt' => 3),
            array('db' => 'section_name', 'dt' => 4),
            array('db' => 'department_name', 'dt' => 5),
            array('db' => 'division_name', 'dt' => 6),
            array('db' => 'directorate_name', 'dt' => 7),
            array(
                'db'        => 'id',
                'dt'        => 8,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('employee/detail', $i) . '
                    ' . link_edit('employee/edit', $i) . '
                    ' . link_delete('employee/delete', $i) . '
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
            'title' => 'Create New Section',
            'active' => 'Section',
            'validation' => \Config\Services::validation()
        ];
        return view('employee/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'employee_name' => [
                'rules' => 'required|is_unique[employee.employee_name]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/employee/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $id = $this->employeeModel->counterID();

            $this->employeeModel->insert([
                'id' => $id,
                'employee_name' => $this->request->getVar('employee_name'),
                'description' => $this->request->getVar('description')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/employee/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/employee/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/employee');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Section',
            'active' => 'Section',
            'data' => $this->employeeModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('employee/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'employee_name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/employee/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'employee_name' => $this->request->getVar('employee_name'),
                    'description' => $this->request->getVar('description')
                ];

                /*Update data ke table Sections berdasarkan ID */
                $this->employeeModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/employee/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/employee/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/employee');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Section',
            'active' => 'Section',
            'data' => $this->employeeModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Section Data ' . $id . ' is not found.');
        }
        return view('employee/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->employeeModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/employee')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/employee')->with('messages', $e->getMessage());
        }

        return redirect()->to('/employee');
    }
}
