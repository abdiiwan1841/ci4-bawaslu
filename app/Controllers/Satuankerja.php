<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SatuanKerjaModel;

class Satuankerja extends BaseController
{
    protected $satuanKerjaModel;

    public function __construct()
    {
        $this->satuanKerjaModel = new SatuanKerjaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Satuan Kerja',
            'active' => 'satuan_kerja',
            'data' => null
        ];

        return view('satuan_kerja/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.kode_satuan_kerja,
                a.nama_satuan_kerja,
                a.id_wilayah,
                a.id_pimpinan
                FROM satuan_kerja a
                WHERE a.deleted_at IS NULL
                ORDER BY a.nama_satuan_kerja ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'kode_satuan_kerja', 'dt' => 1),
            array('db' => 'nama_satuan_kerja', 'dt' => 2),
            array('db' => 'id_wilayah', 'dt' => 3),
            array('db' => 'id_pimpinan', 'dt' => 4),
            array(
                'db'        => 'id',
                'dt'        => 5,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('satuankerja/detail', $i) . '
                    ' . link_edit('satuankerja/edit', $i) . '
                    ' . link_delete('satuankerja/delete', $i) . '
                    </center>';
                    return $html;
                }
            ),
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }

    public function auditi_datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.nip,
                a.`name` AS nama,
                c.`name` AS jabatan
                FROM users a
                JOIN user_groups b ON b.`id_user`=a.`id` 
                JOIN `groups` c ON c.`id`=b.`id_group`
                WHERE c.name='Auditee'
                ORDER BY  a.`name` ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'nip', 'dt' => 1),
            array('db' => 'nama', 'dt' => 2),
            array('db' => 'jabatan', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                        <button name="" onclick="displayResult(this.form,\'' . $i . '\',\'' . $row['nip'] . '\',\'' . $row['nama'] . '\',\'' . $row['jabatan'] . '\')" value="' . $i . '" title="Pilih Form" data-dismiss="modal" class="badge badge-info">Pilih</button>
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

        $provinsi_options = array();

        $provinsi = $this->satuanKerjaModel->getProvinsi();
        foreach ($provinsi as $r) {
            $provinsi_options[$r->id] = $r->nama_provinsi;
        }

        $data = [
            'title' => 'Create New Satuan Kerja',
            'active' => 'SatuanKerja',
            'provinsi_options' => $provinsi_options,
            'validation' => \Config\Services::validation()
        ];
        return view('satuan_kerja/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'kode_satuan_kerja' => [
                'rules' => 'required|is_unique[satuan_kerja.kode_satuan_kerja]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'nama_satuan_kerja' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'provinsi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'nama_pimpinan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'nip' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/satuankerja/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $id = $this->satuanKerjaModel->counterID();

            $this->satuanKerjaModel->insert([
                'id' => $id,
                'satuan_kerja_name' => $this->request->getVar('satuan_kerja_name'),
                'description' => $this->request->getVar('description')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/satuankerja/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/satuankerja/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/satuankerja');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit SatuanKerja',
            'active' => 'SatuanKerja',
            'data' => $this->satuanKerjaModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('satuan_kerja/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'satuan_kerja_name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/satuan_kerja/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'satuan_kerja_name' => $this->request->getVar('satuan_kerja_name'),
                    'description' => $this->request->getVar('description')
                ];

                /*Update data ke table SatuanKerjas berdasarkan ID */
                $this->satuanKerjaModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/satuan_kerja/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/satuan_kerja/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/satuan_kerja');
        }
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail SatuanKerja',
            'active' => 'SatuanKerja',
            'data' => $this->satuanKerjaModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('SatuanKerja Data ' . $id . ' is not found.');
        }
        return view('satuan_kerja/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->satuanKerjaModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/satuan_kerja')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception $e) {
            return redirect()->to('/satuan_kerja')->with('messages', $e->getMessage());
        }

        return redirect()->to('/satuan_kerja');
    }

    public function ajaxGetKabupatenByProvinsiId($idProvinsi)
    {
        $response['data'] = $this->satuanKerjaModel->getKabupaten($idProvinsi);
        echo json_encode($response);
    }
}
