<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EselonModel;

class Eselon extends BaseController
{
    protected $eselonModel;

    public function __construct()
    {
        $this->eselonModel = new EselonModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Eselon',
            'active' => 'Section',
            'data' => null
        ];

        return view('eselon/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT
                a.id,
                a.nama,
                a.level_eselon,
                a.id_parent
                FROM eselon a
                WHERE a.deleted_at IS NULL
                ORDER BY a.nama ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'nama', 'dt' => 1),
            array('db' => 'level_eselon', 'dt' => 2),
            array('db' => 'id_parent', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('eselon/detail', $i) . '
                    ' . link_edit('eselon/edit', $i) . '
                    ' . link_delete('eselon/delete', $i) . '
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
            'title' => 'Create New Eselon',
            'active' => 'eselon',
            'validation' => \Config\Services::validation()
        ];
        return view('eselon/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/eselon/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->eselonModel->insert([
                'id' => get_uuid(),
                'nama' => $this->request->getVar('nama'),
                'level_eselon' => $this->request->getVar('level_eselon'),
                'id_parent' => $this->request->getVar('id_parent')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/eselon/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/eselon/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/eselon');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Eselon',
            'active' => 'Eselon',
            'data' => $this->eselonModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('eselon/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/eselon/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'nama' => $this->request->getVar('nama'),
                    'level_eselon' => $this->request->getVar('level_eselon'),
                    'id_parent' => $this->request->getVar('id_parent')
                ];

                /*Update data ke table Sections berdasarkan ID */
                $this->eselonModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/eselon/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/eselon/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/eselon');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Eselon',
            'active' => 'Eselon',
            'data' => $this->eselonModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data ' . $id . ' is not found.');
        }
        return view('eselon/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->eselonModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/eselon')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/eselon')->with('messages', $e->getMessage());
        }

        return redirect()->to('/eselon');
    }
}
