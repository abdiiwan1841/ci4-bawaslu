<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | +62-852-2224-1987 | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JenisrekomendasiModel;

class Jenisrekomendasi extends BaseController
{
    protected $jenisrekomendasiModel;

    public function __construct()
    {
        $this->jenisrekomendasiModel = new JenisrekomendasiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Jenis Rekomendasi',
            'active' => 'jenisrekomendasi',
            'data' => null
        ];

        return view('jenisrekomendasi/index', $data);
    }


    public function datatables()
    {
        $table =
            "
            (
            SELECT a.id, a.kode, a.deskripsi
            FROM jenis_rekomendasi a
            WHERE a.deleted_at IS NULL
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'kode', 'dt' => 1),
            array('db' => 'deskripsi', 'dt' => 2),
            array(
                'db'        => 'id',
                'dt'        => 3,
                'formatter' => function ($i, $row) {
                    $html = "
                    <center>
                        " . link_detail('jenisrekomendasi/detail', $i) . "
                        " . link_edit('jenisrekomendasi/edit', $i) . "
                        " . link_delete('jenisrekomendasi/delete', $i) . "
                    </center>";
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
            'title' => 'Create New Jenisrekomendasi',
            'active' => 'jenisrekomendasi',
            'validation' => \Config\Services::validation()
        ];
        return view('jenisrekomendasi/create', $data);
    }

    public function save()
    {

        $validation = [

            'kode' => [
                'label' => 'Kode',
                'rules' => 'required|is_unique[jenis_rekomendasi.kode]',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'deskripsi' => [
                'label' => 'Deskripsi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/jenisrekomendasi/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $data = array(
                'id' => get_uuid(),
                'kode' => $this->request->getVar('kode'),
                'deskripsi' => $this->request->getVar('deskripsi'),
                'id_jenis_temuan' => $this->request->getVar('id_jenis_temuan'),
            );

            $this->jenisrekomendasiModel->insert($data);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/jenisrekomendasi/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/jenisrekomendasi/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/jenisrekomendasi');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Jenisrekomendasi',
            'active' => 'jenisrekomendasi',
            'data' => $this->jenisrekomendasiModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('jenisrekomendasi/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'kode' => [
                'label' => 'Kode',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ],
            'deskripsi' => [
                'label' => 'Deskripsi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/jenisrekomendasi/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = array(
                    'id' => $id,
                    'kode' => $this->request->getVar('kode'),
                    'deskripsi' => $this->request->getVar('deskripsi'),
                    'id_jenis_temuan' => $this->request->getVar('id_jenis_temuan'),
                );

                $this->jenisrekomendasiModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/jenisrekomendasi/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/jenisrekomendasi/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/jenisrekomendasi');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Jenisrekomendasi',
            'active' => 'jenisrekomendasi',
            'data' => $this->jenisrekomendasiModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data ' . $id . ' is not found.');
        }
        return view('jenisrekomendasi/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->jenisrekomendasiModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/jenisrekomendasi')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/jenisrekomendasi')->with('messages', $e->getMessage());
        }

        return redirect()->to('/jenisrekomendasi');
    }
}
