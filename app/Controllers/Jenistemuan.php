<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | +62-852-2224-1987 | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JenistemuanModel;

class Jenistemuan extends BaseController
{
    protected $jenistemuanModel;

    public function __construct()
    {
        $this->jenistemuanModel = new JenistemuanModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Jenis Temuan',
            'active' => 'jenistemuan',
            'data' => null
        ];

        return view('jenistemuan/index', $data);
    }


    public function datatables()
    {
        $table =
            "
            (
            SELECT 
            a.id, 
            a.kode, 
            a.deskripsi,
            a.id_parent,
            concat(b.kode ,' - ',b.deskripsi) AS parent            
            FROM jenis_temuan a 
            LEFT JOIN jenis_temuan b ON b.id=a.id_parent
            WHERE a.deleted_at IS NULL 
            AND b.deleted_at IS NULL
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'kode', 'dt' => 1),
            array('db' => 'deskripsi', 'dt' => 2),
            array('db' => 'parent', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = "
                    <center>
                        " . link_detail('jenistemuan/detail', $i) . "
                        " . link_edit('jenistemuan/edit', $i) . "
                        " . link_delete('jenistemuan/delete', $i) . "
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

        $jenis_temuan_options = ['' => '--Pilih Select--'];

        $jenisTemuan = $this->jenistemuanModel->getJenisTemuan();
        foreach ($jenisTemuan as $r) {
            $jenis_temuan_options[$r->id] = $r->nama;
        }

        $data = [
            'title' => 'Create New Jenis Temuan',
            'active' => 'jenistemuan',
            'jenis_temuan_options' => $jenis_temuan_options,
            'validation' => \Config\Services::validation()
        ];
        return view('jenistemuan/create', $data);
    }

    public function save()
    {

        $validation = [

            'kode' => ['label' => 'Kode', 'rules' => 'required|is_unique[jenis_temuan.kode]', 'errors' => ['required' => '{field} harus diisi.']], 'deskripsi' => ['label' => 'Deskripsi', 'rules' => 'required', 'errors' => ['required' => '{field} harus diisi.']]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/jenistemuan/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $data = array(
                'id' => get_uuid(),
                'kode' => $this->request->getVar('kode'),
                'deskripsi' => $this->request->getVar('deskripsi'),
                'id_parent' => $this->request->getVar('id_parent')
            );

            $this->jenistemuanModel->insert($data);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/jenistemuan/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/jenistemuan/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/jenistemuan');
    }

    public function edit($id)
    {
        $jenis_temuan_options = ['' => '--Pilih Select--'];

        $jenisTemuan = $this->jenistemuanModel->getJenisTemuan($id);
        foreach ($jenisTemuan as $r) {
            $jenis_temuan_options[$r->id] = $r->nama;
        }

        $data = [
            'title' => 'Edit Jenis Temuan',
            'active' => 'jenistemuan',
            'data' => $this->jenistemuanModel->getDataById($id),
            'jenis_temuan_options' => $jenis_temuan_options,
            'validation' => \Config\Services::validation()
        ];

        return view('jenistemuan/edit', $data);
    }

    public function update($id)
    {

        $validation = [

            'kode' => ['label' => 'Kode', 'rules' => 'required', 'errors' => ['required' => '{field} harus diisi.']], 'deskripsi' => ['label' => 'Deskripsi', 'rules' => 'required', 'errors' => ['required' => '{field} harus diisi.']]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/jenistemuan/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = array(
                    'id' => $id,
                    'kode' => $this->request->getVar('kode'),
                    'deskripsi' => $this->request->getVar('deskripsi'),
                    'id_parent' => $this->request->getVar('id_parent')
                );

                $this->jenistemuanModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/jenistemuan/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/jenistemuan/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/jenistemuan');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Jenis Temuan',
            'active' => 'jenistemuan',
            'data' => $this->jenistemuanModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data ' . $id . ' is not found.');
        }
        return view('jenistemuan/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->jenistemuanModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/jenistemuan')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/jenistemuan')->with('messages', $e->getMessage());
        }

        return redirect()->to('/jenistemuan');
    }
}
