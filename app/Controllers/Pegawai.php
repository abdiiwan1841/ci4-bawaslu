<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupModel;
use App\Models\UserGroupModel;
// use App\Models\AuthModel;
use App\Models\PegawaiModel;

class Pegawai extends BaseController
{

	protected $pegawaiModel;
	protected $userModel;
	protected $groupModel;
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
			'title' => 'List of Pegawai',
			'active' => 'pegawai',
			'data' => null
		];

		return view('pegawai/index', $data);
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
				a.`id_satuan_kerja`,
				c.wilayah,
				a.`id_user`,
				b.username,
				a.`created_at`,
				a.`updated_at`,
				a.`deleted_at`
				FROM `pegawai` a
				LEFT JOIN users b ON b.id=a.id_user
				LEFT JOIN (
							SELECT
							x.`id`,
							x.`nama_provinsi` AS wilayah
							FROM provinsi `x` 
							UNION
							SELECT 
							y.id,
							y.nama_kabupaten AS wilayah
							FROM
							kabupaten `y`
							) c ON c.id=a.`id_satuan_kerja`
				WHERE a.deleted_at IS NULL 
				AND a.type='AUDITEE'
				ORDER BY a.nama ASC
            ) temp
            ";

		$columns = array(
			array('db' => 'id', 'dt' => 0),
			array('db' => 'nip', 'dt' => 1),
			array('db' => 'nama', 'dt' => 2),
			array('db' => 'jabatan', 'dt' => 3),
			array('db' => 'wilayah', 'dt' => 4),
			array('db' => 'username', 'dt' => 5),
			array(
				'db'        => 'id',
				'dt'        => 6,
				'formatter' => function ($i, $row) {
					$html = '
					<center>
                    ' . link_detail('pegawai/detail', $i) . '
                    ' . link_edit('pegawai/edit', $i) . '
                    ' . link_delete('pegawai/delete', $i) . '
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
			'title' => 'Create New Pegawai',
			'active' => 'pegawai',
			'eselon1_options' => $eselon1_options,
			'groups_options' => $groups_options,
			'validation' => \Config\Services::validation()
		];
		return view('pegawai/create', $data);
	}

	public function ajaxGetEselon2($idEselon1)
	{
		$response['data'] = $this->pegawaiModel->getEselon2($idEselon1);
		echo json_encode($response);
	}

	public function ajaxGetEselon3($idEselon2)
	{
		$response['data'] = $this->pegawaiModel->getEselon3($idEselon2);
		echo json_encode($response);
	}

	public function ajaxGetKabupatenByProvinsiId($idProvinsi)
	{
		$response['data'] = $this->pegawaiModel->getKabupaten($idProvinsi);
		echo json_encode($response);
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
			return redirect()->to('/pegawai/create')->withInput()->with("messages", "Validation Error");
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

			$idSatuanKerja = ($this->request->getVar('kabupaten')) ? $this->request->getVar('kabupaten') : $this->request->getVar('provinsi');

			$this->pegawaiModel->insert([
				'id' => get_uuid(),
				'nip' => $this->request->getVar('nip'),
				'nama' => $this->request->getVar('nama'),
				'jabatan' => $this->request->getVar('jabatan'),
				'id_user' => $idUser,
				'id_satuan_kerja' => $idSatuanKerja,
				'id_provinsi' => $this->request->getVar('provinsi'),
				'id_kabupaten' => $this->request->getVar('kabupaten'),
				'type' => 'AUDITEE'
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
				'id_group' => 'ccc95e11-a95f-e106-a8e8-34fb8f5bdccf' //id group AUDITEE
			);

			$this->userGroupModel->insert($userGroupdata);

			session()->setFlashData('messages', 'new data added successfully');
			$db->transComplete();
			if ($db->transStatus() === FALSE) {
				return redirect()->to('/pegawai/create')->withInput();
			}
		} catch (\Exception $e) {
			return redirect()->to('/pegawai/create')->withInput()->with('messages', $e->getMessage());
		}
		return redirect()->to('/pegawai');
	}

	public function edit($id)
	{

		$provinsi_options = array();

		$provinsi = $this->pegawaiModel->getProvinsi();
		foreach ($provinsi as $r) {
			$provinsi_options[$r->id] = $r->nama_provinsi;
		}

		$kabupaten_options = array();
		$kabupaten = $this->pegawaiModel->getKabupaten();
		foreach ($kabupaten as $r) {
			$kabupaten_options[$r->id] = $r->nama_kabupaten;
		}

		$data = [
			'title' => 'Edit Pegawai',
			'active' => 'pegawai',
			'provinsi_options' => $provinsi_options,
			'kabupaten_options' => $kabupaten_options,
			'data' => $this->pegawaiModel->getDataById($id),
			'validation' => \Config\Services::validation()
		];
		return view('/pegawai/edit', $data);
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
			return redirect()->to('/pegawai/edit/' . $id)->withInput()->with('messages', 'Validation Error');
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
					return redirect()->to('/pegawai/edit/' . $id)->withInput();
				}


				session()->setFlashData('messages', 'Data was successfully updated' . $exceptionMessages);
			} catch (\Exception $e) {
				return redirect()->to('/pegawai/edit/' . $id)->withInput()->with('messages', $e->getMessage());
			}
			return redirect()->to('/pegawai');
		}
	}
}
