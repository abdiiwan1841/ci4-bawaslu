<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupModel;
use App\Models\UserGroupModel;
use App\Models\PegawaiModel;

class Auditor extends BaseController
{

    protected $pegawaiModel;
    protected $userModel;
    protected $userGroupModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->userGroupModel = new UserGroupModel();
        $this->pegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Auditor',
            'active' => 'auditor',
            'data' => null
        ];

        return view('auditor/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
				SELECT 
				a.`id`,
				a.`nip`,
				a.`nama`,
				a.`jabatan`,
				a.`id_user`,
				b.username,
				a.`created_at`,
				a.`updated_at`,
				a.`deleted_at`
				FROM `pegawai` a
				LEFT JOIN users b ON b.id=a.id_user
				WHERE a.deleted_at IS NULL 
				AND a.type='AUDITOR'
				ORDER BY a.nama ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'nip', 'dt' => 1),
            array('db' => 'nama', 'dt' => 2),
            array('db' => 'jabatan', 'dt' => 3),
            array('db' => 'username', 'dt' => 4),
            array(
                'db'        => 'id',
                'dt'        => 5,
                'formatter' => function ($i, $row) {
                    $html = '
					<center>
                    ' . link_detail('auditor/detail', $i) . '
                    ' . link_edit('auditor/edit', $i) . '
                    ' . link_delete('auditor/delete', $i) . '
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

        $groups_options = array();

        $groups = $this->groupModel->getData();
        foreach ($groups as $r) {
            $groups_options[$r->id] = $r->name;
        }

        $eselon1_options = array();

        $eselon1 = $this->pegawaiModel->getEselon1();
        foreach ($eselon1 as $r) {
            $eselon1_options[$r->id] = $r->nama;
        }

        $data = [
            'title' => 'Create New Auditor',
            'active' => 'auditor',
            'eselon1_options' => $eselon1_options,
            'groups_options' => $groups_options,
            'validation' => \Config\Services::validation()
        ];
        return view('auditor/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'nip' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'username' => [
                'rules' => 'required|is_unique[users.username]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'repeat_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'matches' => 'inputan {field} tidak sama dengan password'
                ]
            ],
            'image' => [
                'rules' => 'max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|ext_in[image,jpg,jpeg,png]',
                'errors' => [
                    // 'max_size' => 'ukuran tidak boleh melebihi 1024 KB',
                    // 'is_image' => 'Yang anda pilih bukan gambar',
                    // 'mime_in' => 'Yang anda pilih bukan gambar',
                    // 'ext_in' => 'Harus JPG/JPEG/PNG'
                ]
            ],
            'email' => [
                'rules' => 'required|is_unique[users.email]',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/auditor/create')->withInput()->with("messages", "Validation Error");
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $idUser = get_uuid();

            //ambil gambar
            $file = $this->request->getFile('image');

            if ($file->getError() == 4) { //4 = ga ada file yang di upload
                $namaFile = "default.png";
            } else {

                //ambil nama file;
                // $namaFile = $file->getName();
                $namaFile = $file->getRandomName();

                //pindahkan file ke folder IMAGES
                $file->move('images', $namaFile); //kalau di buar random nama file dijadikan parameter
            }

            $this->pegawaiModel->insert([
                'id' => get_uuid(),
                'nip' => $this->request->getVar('nip'),
                'nama' => $this->request->getVar('nama'),
                'id_user' => $idUser,
                'type' => 'AUDITOR'
            ]);

            $this->userModel->insert([
                'id' => $idUser,
                'username' => $this->request->getVar('username'),
                'password' => sha1($this->request->getVar('password')),
                'name' => $this->request->getVar('nama'),
                'email' => $this->request->getVar('email'),
                'image' => $namaFile
            ]);

            /*Insert data baru ke table USER_GROUP berdasarkan ID_GROUP */

            $userGroupdata = array(
                'id' => get_uuid(),
                'id_user' => $idUser,
                'id_group' => '1f20964c-c8e4-c8f6-bb00-2d092bf8bb5e' //id group AUDITOR
            );

            $this->userGroupModel->insert($userGroupdata);

            session()->setFlashData('messages', 'new data added successfully');
            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/auditor/create')->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->to('/auditor/create')->withInput()->with('messages', $e->getMessage());
        }
        return redirect()->to('/auditor');
    }

    public function edit($id)
    {

        $groups_options = array();

        $groups = $this->groupModel->getData();
        foreach ($groups as $r) {
            $groups_options[$r->id] = $r->name;
        }

        $eselon1_options = array();

        $eselon1 = $this->pegawaiModel->getEselon1();
        foreach ($eselon1 as $r) {
            $eselon1_options[$r->id] = $r->nama;
        }

        $data = [
            'title' => 'Edit Auditor',
            'active' => 'auditor',
            'eselon1_options' => $eselon1_options,
            'groups_options' => $groups_options,
            'data' => $this->pegawaiModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];
        return view('/auditor/edit', $data);
    }

    public function update($id)
    {
        //$slug = url_title($this->request->getVar('name'), '-', true);

        $exceptionMessages = '';

        $validation = [
            'nip' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nama' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'provinsi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'image' => [
                'rules' => 'max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|ext_in[image,jpg,jpeg,png]',
                'errors' => [
                    // 'max_size' => 'ukuran tidak boleh melebihi 1024 KB',
                    // 'is_image' => 'Yang anda pilih bukan gambar',
                    // 'mime_in' => 'Yang anda pilih bukan gambar',
                    // 'ext_in' => 'Harus JPG/JPEG/PNG'
                ]
            ],
            'email' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if ($this->request->getVar('password')) {
            $validation['repeat_password'] = [
                'rules' => 'required|matches[password]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'matches' => 'inputan {field} tidak sama dengan password'
                ]
            ];
        }

        if (!$this->validate($validation)) {
            return redirect()->to('/auditor/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            //ambil gambar
            $file = $this->request->getFile('image');

            //cek gambar apakah ada perubahan
            if ($file->getError() == 4) { //4 = ga ada file yang di upload
                $namaFile = $this->request->getVar('old_image');
            } else {

                //ambil nama file;
                // $namaFile = $file->getName();
                $namaFile = $file->getRandomName();

                //pindahkan file ke folder IMAGES
                $file->move('images', $namaFile); //kalau di buar random nama file dijadikan parameter
                session()->set('image', $namaFile);
                //hapus file lama jika bukan file default
                if ($this->request->getVar('old_image') != 'default.png') {
                    try {
                        unlink('images/' . $this->request->getVar('old_image'));
                    } catch (\Exception $e) {
                        $exceptionMessages = '<br/>' . $e->getMessage();
                        //return redirect()->to('/user/edit/' . $id)->withInput()->with('messages', $e->getMessage());
                    }
                }
            }

            $data = [
                'id' => $this->request->getVar('id_user'),
                'name' => $this->request->getVar('nama'),
                'email' => $this->request->getVar('email'),
                'image' => $namaFile
            ];

            /*jika ada perubahan password*/
            if ($this->request->getVar('password')) {
                $data['password'] = sha1($this->request->getVar('password'));
            }

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $idSatuanKerja = ($this->request->getVar('kabupaten')) ? $this->request->getVar('kabupaten') : $this->request->getVar('provinsi');

                $this->pegawaiModel->save([
                    'id' => $id,
                    'nip' => $this->request->getVar('nip'),
                    'nama' => $this->request->getVar('nama'),
                    'jabatan' => $this->request->getVar('jabatan'),
                    'id_provinsi' => $this->request->getVar('provinsi'),
                    'id_kabupaten' => $this->request->getVar('kabupaten'),
                    'id_satuan_kerja' => $idSatuanKerja
                ]);

                /*Update data ke table USER berdasarkan ID */
                $this->userModel->save($data);


                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/auditor/edit/' . $id)->withInput();
                }


                session()->setFlashData('messages', 'Data was successfully updated' . $exceptionMessages);
            } catch (\Exception $e) {
                return redirect()->to('/auditor/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }
            return redirect()->to('/auditor');
        }
    }

    public function ajaxGetEselon2($idEselon1)
    {
        $response['data'] = $this->laporanModel->getEselon2($idEselon1);
        echo json_encode($response);
    }

    public function ajaxGetEselon3($idEselon2)
    {
        $response['data'] = $this->laporanModel->getEselon3($idEselon2);
        echo json_encode($response);
    }
}
