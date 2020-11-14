<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PositionModel;

class Position extends BaseController
{
    protected $positionModel;

    public function __construct()
    {
        $this->positionModel = new PositionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Positions',
            'active' => 'Position',
            'data' => null
        ];

        return view('position/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.position_code,
                a.position_name,
                a.description
                FROM `position` a
                WHERE a.deleted_at IS NULL
                ORDER BY a.position_name ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'position_code', 'dt' => 1),
            array('db' => 'position_name', 'dt' => 2),
            array('db' => 'description', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('position/detail', $i) . '
                    ' . link_edit('position/edit', $i) . '
                    ' . link_delete('position/delete', $i) . '
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
            'title' => 'Create New Position',
            'active' => 'Position',
            'validation' => \Config\Services::validation()
        ];
        return view('position/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'position_code' => [
                'rules' => 'required|is_unique[position.position_code]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'position_name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/position/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->positionModel->insert([
                'id' => get_uuid(),
                'position_code' => $this->request->getVar('position_code'),
                'position_name' => $this->request->getVar('position_name'),
                'description' => $this->request->getVar('description')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/position/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/position/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/position');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Position',
            'active' => 'Position',
            'data' => $this->positionModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('position/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'position_code' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'position_name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/position/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'position_code' => $this->request->getVar('position_code'),
                    'position_name' => $this->request->getVar('position_name'),
                    'description' => $this->request->getVar('description')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->positionModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/position/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/position/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/position');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Position',
            'active' => 'Position',
            'data' => $this->positionModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Position Data ' . $id . ' is not found.');
        }
        return view('position/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->positionModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/position')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/position')->with('messages', $e->getMessage());
        }

        return redirect()->to('/position');
    }
}
