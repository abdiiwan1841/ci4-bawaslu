<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SectionModel;

class Section extends BaseController
{
    protected $sectionModel;

    public function __construct()
    {
        $this->sectionModel = new SectionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Sections',
            'active' => 'Section',
            'data' => null
        ];

        return view('section/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.section_code,
                a.section_name,
                a.description
                FROM `section` a
                WHERE a.deleted_at IS NULL
                ORDER BY a.section_name ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'section_code', 'dt' => 1),
            array('db' => 'section_name', 'dt' => 2),
            array('db' => 'description', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('section/detail', $i) . '
                    ' . link_edit('section/edit', $i) . '
                    ' . link_delete('section/delete', $i) . '
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
        return view('section/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'section_name' => [
                'rules' => 'required|is_unique[section.section_name]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/section/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $id = $this->sectionModel->counterID();

            $this->sectionModel->insert([
                'id' => $id,
                'section_name' => $this->request->getVar('section_name'),
                'description' => $this->request->getVar('description')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/section/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/section/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/section');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Section',
            'active' => 'Section',
            'data' => $this->sectionModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('section/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'section_name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/section/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'section_name' => $this->request->getVar('section_name'),
                    'description' => $this->request->getVar('description')
                ];

                /*Update data ke table Sections berdasarkan ID */
                $this->sectionModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/section/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/section/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/section');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Section',
            'active' => 'Section',
            'data' => $this->sectionModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Section Data ' . $id . ' is not found.');
        }
        return view('section/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->sectionModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/section')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/section')->with('messages', $e->getMessage());
        }

        return redirect()->to('/section');
    }
}
