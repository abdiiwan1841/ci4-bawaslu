<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GroupModel;
use App\Models\PermissionModel;
use App\Models\GroupPermissionModel;
use App\Models\AuthModel;

class Group extends BaseController
{
    protected $groupModel;
    protected $permissionModel;
    protected $groupPermissionModel;
    protected $authModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel();
        $this->permissionModel = new PermissionModel();
        $this->groupPermissionModel = new GroupPermissionModel();
        $this->authModel = new AuthModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Groups',
            'active' => 'group',
            'data' => null
        ];

        return view('group/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.name,
                a.description,
                a.landing_page,
                c.name AS permission_name
                FROM groups a
                LEFT JOIN group_permissions b ON b.id_permission=a.landing_page
                LEFT JOIN permissions c ON c.id=b.id_permission
                WHERE a.deleted_at IS NULL
                GROUP BY a.id 
                ORDER BY a.name ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'name', 'dt' => 1),
            array('db' => 'description', 'dt' => 2),
            array('db' => 'permission_name', 'dt' => 3),
            array(
                'db'        => 'id',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('group/detail', $i) . '
                    ' . link_edit('group/edit', $i) . '
                    ' . link_delete('group/delete', $i) . '
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

        $permission_options = array();

        $permissions = $this->permissionModel->getData();
        foreach ($permissions as $r) {
            $permission_options[$r->id] = $r->uri;
        }

        $data = [
            'title' => 'Create New Group',
            'active' => 'group',
            'permission_options' => $permission_options,
            'permission_selected' => array(),
            'validation' => \Config\Services::validation()
        ];
        return view('group/create', $data);
    }

    public function save()
    {
        //dd($this->request->getVar());

        if (!$this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'landing_page' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'permissions' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/group/create')->withInput()->with("messages", "Validation Error");
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $idGroup = get_uuid();

            $this->groupModel->insert([
                'id' => $idGroup,
                'name' => $this->request->getVar('name'),
                'landing_page' => $this->request->getVar('landing_page'),
                'description' => $this->request->getVar('description')
            ]);

            foreach ($this->request->getVar('permissions[]') as $r) {
                $groupdata[] = array(
                    'id' => get_uuid(),
                    'id_group' => $idGroup,
                    'id_permission' => $r
                );
            }
            $this->groupPermissionModel->insertBatch($groupdata);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/group/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');

                /*update session USER_PERMISSIONS*/
                $this->authModel = new AuthModel();
                session()->set('user_permissions', $this->authModel->getUserPermissions(session()->get('id_user')));
            }
        } catch (\Exception $e) {
            return redirect()->to('/group/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/group');
    }

    public function edit($id)
    {
        $permissions = $this->permissionModel->getData();
        foreach ($permissions as $r) {
            $permission_options[$r->id] = $r->uri;
        }

        $data = [
            'title' => 'Edit Group',
            'active' => 'group',
            'data' => $this->groupModel->getDataById($id),
            'permission_options' => $permission_options,
            'permission_selected' => $this->groupPermissionModel->getGroupPermissionsSelected($id),
            'validation' => \Config\Services::validation()
        ];

        return view('group/edit', $data);
    }

    public function update($id)
    {

        // dd($_POST);

        $validation = [
            'name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'landing_page' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'permissions' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/group/edit/' . $id)->withInput()->with("messages", "Validation Error");
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'name' => $this->request->getVar('name'),
                    'landing_page' => $this->request->getVar('landing_page'),
                    'description' => $this->request->getVar('description')
                ];

                /*Update data ke table GROUP berdasarkan ID */
                $this->groupModel->save($data);

                /*Delete data lama di table GROUP_PERMISSIONS berdasarkan ID_GROUP */
                $this->groupPermissionModel->where('id_group', $id);
                $this->groupPermissionModel->delete();

                /*Insert data baru ke table GROUP_PERMISSIONS berdasarkan ID_GROUP */
                foreach ($this->request->getVar('permissions[]') as $r) {
                    $groupdata[] = array(
                        'id' => get_uuid(),
                        'id_group' => $id,
                        'id_permission' => $r
                    );
                }
                $this->groupPermissionModel->insertBatch($groupdata);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/group/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');

                    /*UPDATE SESSION AUTHORIZE PAGES DARI USER YANG SEDANG LOGIN*/
                    $this->authModel = new AuthModel();
                    session()->set('user_permissions', $this->authModel->getUserPermissions(session()->get('id_user')));
                }
            } catch (\Exception $e) {
                return redirect()->to('/group/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/group');
        }
    }

    public function detail($id)
    {
        $permissions = $this->permissionModel->getData();
        foreach ($permissions as $r) {
            $permission_options[$r->id] = $r->uri;
        }

        $data = [
            'title' => 'Group Detail',
            'active' => 'group',
            'data' => $this->groupModel->getDataById($id),
            'permission_options' => $permission_options,
            'permission_selected' => $this->groupPermissionModel->getGroupPermissionsSelected($id),
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data group ' . $id . ' tidak ditemukan.');
        }
        return view('group/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->groupPermissionModel->where('id_group', $id);
            $this->groupPermissionModel->delete();

            $this->groupModel->delete($id);
            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/group/' . $id)->with('messages', 'failed delete data');
            } else {

                session()->setFlashData('messages', 'Data was successfully deleted');

                /*UPDATE SESSION AUTHORIZE PAGES DARI USER YANG SEDANG LOGIN*/
                $this->authModel = new AuthModel();
                session()->set('user_permissions', $this->authModel->getUserPermissions(session()->get('id_user')));
            }
            return redirect()->to('/group');
        } catch (\Exception $e) {
            return redirect()->to('/group/' . $id)->with('messages', $e->getMessage());
        }
    }
}
