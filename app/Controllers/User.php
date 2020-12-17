<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\GroupModel;
use App\Models\UserGroupModel;
use App\Models\AuthModel;

class User extends BaseController
{
	protected $userModel;
	protected $groupModel;
	protected $authModel;

	public function __construct()
	{
		$this->userModel = new UserModel();
		$this->groupModel = new GroupModel();
		$this->userGroupModel = new UserGroupModel();
	}

	public function index()
	{
		$data = [
			'title' => 'List of Users',
			'active' => 'user',
			'data' => null
		];

		return view('user/index', $data);
	}

	public function datatables()
	{
		$table =
			"
            (
                SELECT 
                u.id,
                u.username,
                u.name,
                    (SELECT GROUP_CONCAT(CONCAT('<span class=\"badge badge-info\">',g.name,'</span>') SEPARATOR ' ') 
                    FROM user_groups ug JOIN `groups` g ON ug.id_group=g.id 
                    WHERE id_user=u.id
                    ) AS 'groups',
                u.email
                FROM `users` u
                LEFT JOIN user_groups g ON g.id_user=u.id
                WHERE u.deleted_at IS NULL
                GROUP BY u.id
                ORDER BY u.name ASC
            ) temp
            ";

		$columns = array(
			array('db' => 'id', 'dt' => 0),
			array('db' => 'username', 'dt' => 1),
			array('db' => 'name', 'dt' => 2),
			array('db' => 'email', 'dt' => 3),
			array('db' => 'groups', 'dt' => 4),
			array(
				'db'        => 'id',
				'dt'        => 5,
				'formatter' => function ($i, $row) {
					$html = '
					<center>
                    ' . link_detail('user/detail', $i) . '
                    ' . link_edit('user/edit', $i) . '
                    ' . link_delete('user/delete', $i) . '
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

		$data = [
			'title' => 'Create New User',
			'active' => 'user',
			'groups_options' => $groups_options,
			'validation' => \Config\Services::validation()
		];
		return view('user/create', $data);
	}

	public function save()
	{
		if (!$this->validate([
			'name' => [
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
			'groups' => [
				'rules' => 'required',
				'errors' => [
					// 'required' => '{field} harus diisi.'
				]
			],
			'email' => [
				'rules' => 'required|is_unique[users.email]',
				'errors' => [
					// 'required' => '{field} harus diisi.'
				]
			]
		])) {
			return redirect()->to('/user/create')->withInput()->with("messages", "Validation Error");
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

			$this->userModel->insert([
				'id' => $idUser,
				'username' => $this->request->getVar('username'),
				'password' => sha1($this->request->getVar('password')),
				'name' => $this->request->getVar('name'),
				'email' => $this->request->getVar('email'),
				'image' => $namaFile
			]);

			/*Insert data baru ke table USER_GROUP berdasarkan ID_GROUP */
			foreach ($this->request->getVar('groups[]') as $r) {
				$userGroupdata[] = array(
					'id' => get_uuid(),
					'id_user' => $idUser,
					'id_group' => $r
				);
			}
			$this->userGroupModel->insertBatch($userGroupdata);

			session()->setFlashData('messages', 'new data added successfully');
			$db->transComplete();
			if ($db->transStatus() === FALSE) {
				return redirect()->to('/user/create')->withInput();
			}
		} catch (\Exception $e) {
			return redirect()->to('/user/create')->withInput()->with('messages', $e->getMessage());
		}
		return redirect()->to('/user');
	}

	public function edit($id)
	{

		$groups = $this->groupModel->getData();
		foreach ($groups as $r) {
			$groups_options[$r->id] = $r->name;
		}

		$data = [
			'title' => 'Edit User',
			'active' => 'user',
			'data' => $this->userModel->getDataById($id),
			'groups_options' => $groups_options,
			'groups_selected' => $this->userGroupModel->getUserGroupsSelected($id),
			'validation' => \Config\Services::validation()
		];
		return view('/user/edit', $data);
	}

	public function update($id)
	{
		//$slug = url_title($this->request->getVar('name'), '-', true);

		$exceptionMessages = '';

		$validation = [
			'name' => [
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
			'groups' => [
				'rules' => 'required',
				'errors' => [
					// 'required' => '{field} harus diisi.'
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
			return redirect()->to('/user/edit/' . $id)->withInput()->with('messages', 'Validation Error');
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
				'id' => $id,
				'name' => $this->request->getVar('name'),
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

				/*Update data ke table USER berdasarkan ID */
				$this->userModel->save($data);

				/*Delete data lama di table USER_GROUP berdasarkan ID_GROUP */
				$this->userGroupModel->where('id_user', $id);
				$this->userGroupModel->delete();

				/*Insert data baru ke table USER_GROUP berdasarkan ID_GROUP */
				foreach ($this->request->getVar('groups[]') as $r) {
					$userGroupdata[] = array(
						'id' => get_uuid(),
						'id_user' => $id,
						'id_group' => $r
					);
				}
				$this->userGroupModel->insertBatch($userGroupdata);
				$db->transComplete();
				if ($db->transStatus() === FALSE) {
					return redirect()->to('/user/edit/' . $id)->withInput();
				}

				/*update session USER_PERMISSIONS  DARI USER YANG SEDANG LOGIN*/
				$this->authModel = new AuthModel();
				session()->set('user_permissions', $this->authModel->getUserPermissions(session()->get('id_user')));

				session()->setFlashData('messages', 'Data was successfully updated' . $exceptionMessages);
			} catch (\Exception $e) {
				return redirect()->to('/user/edit/' . $id)->withInput()->with('messages', $e->getMessage());
			}
			return redirect()->to('/user');
		}
	}

	public function detail($id)
	{

		$groups = $this->groupModel->getData();
		foreach ($groups as $r) {
			$groups_options[$r->id] = $r->name;
		}

		$data = [
			'title' => 'Detail User',
			'active' => 'user',
			'data' => $this->userModel->getDataById($id),
			'groups_options' => $groups_options,
		];

		if (empty($data['data'])) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('User Data ' . $id . ' is not found.');
		}
		return view('user/detail', $data);
	}

	public function delete($id)
	{
		try {
			$exceptionMessages = '';

			$db      = \Config\Database::connect();

			$db->transStart();

			//cari gambar berdasarkan ID
			$users = $this->userModel->getDataById($id);

			$this->userGroupModel->where('id_user', $id);
			$this->userGroupModel->delete();

			$this->userModel->delete($id);
			$db->transComplete();
			if ($db->transStatus() === FALSE) {
				return redirect()->to('/user')->with('messages', 'failed delete data');
			} else {
				//jika nama file nya default.png jangan dihapus
				if ($users->image != 'default.png') {
					//hapus gambar
					try {
						unlink('images/' . $users->image);
					} catch (\Exception $e) {
						$exceptionMessages = '<br/>' . $e->getMessage();
						//return redirect()->to('/user/edit/' . $id)->withInput()->with('messages', $e->getMessage());
					}
				}
				session()->setFlashData('messages', 'Data was successfully deleted' . $exceptionMessages);
			}
			return redirect()->to('/user');
		} catch (\Exception $e) {
			return redirect()->to('/user')->with('messages', $e->getMessage());
		}
	}

	public function profile()
	{
		$id = session()->get('id_user');
		$data = [
			'title' => 'My Profile',
			'active' => 'profile',
			'data' => $this->userModel->getDataById($id),
			'groups' => $this->userGroupModel->getUserGroups($id)
		];

		if (empty($data['data'])) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Data User ' . $id . ' tidak ditemukan.');
		}
		return view('user/profile', $data);
	}

	public function profile_update()
	{
		$id = session()->get('id_user');

		$validation = [
			'name' => [
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
			return redirect()->to('/profile')->with('messages_error', 'Error Validation')->withInput();
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
				$file->move(WRITEPATH . 'images', $namaFile); //kalau di buar random nama file dijadikan parameter
				session()->set('image', $namaFile);
				//hapus file lama jika bukan file default
				if ($this->request->getVar('old_image') != 'default.png') {
					// unlink(FCPATH . 'images/' . $this->request->getVar('old_image'));
				}
			}

			$data = [
				'id' => $id,
				'name' => $this->request->getVar('name'),
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

				/*Update data ke table USER berdasarkan ID */
				$this->userModel->save($data);

				$db->transComplete();
				if ($db->transStatus() === FALSE) {
					return redirect()->to('/profile')->withInput();
				}

				session()->set('name', $data['name']);
				session()->set('email', $data['email']);

				session()->setFlashData('messages_success', 'Profile was successfully updated');
			} catch (\Exception $e) {
				return redirect()->to('/profile')->withInput()->with('messages_error', $e->getMessage());
			}
			return redirect()->to('/profile');
		}
	}
}
