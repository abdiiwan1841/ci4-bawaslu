<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use App\Models\GroupModel;
use App\Models\GroupPermissionModel;
use App\Models\AuthModel;

class Permission extends BaseController
{
    protected $permissionModel;
    protected $groupModel;
    protected $groupPermissionModel;
    protected $authModel;

    public function __construct()
    {
        $this->permissionModel = new PermissionModel();
        $this->groupModel = new GroupModel();
        $this->groupPermissionModel = new GroupPermissionModel();
    }

    public function index()
    {
        $data = [
            'title' => 'List of Permissions',
            'active' => 'permission',
            'data' => null
        ];

        return view('permission/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.name,
                a.uri
                FROM permissions a
                WHERE a.deleted_at IS NULL
                ORDER BY a.uri ASC
            ) temp
            ";

        $columns = array(
            array(
                'db' => 'id', 'dt' => 'DT_RowId',
                'formatter' => function ($d, $row) {
                    // Technically a DOM id cannot start with an integer, so we prefix
                    // a string. This can also be useful if you have multiple tables
                    // to ensure that the id is unique with a different prefix
                    return 'row_' . $d;
                }
            ),
            array('db' => 'name', 'dt' => 'name'),
            array('db' => 'uri', 'dt' => 'uri'),
            array(
                'db'        => 'id',
                'dt'        => 'id',
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    ' . link_detail('permission/detail', $i) . '
                    ' . link_edit('permission/edit', $i) . '
                    ' . link_delete('permission/delete', $i) . '
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
            'title' => 'Create New Permission',
            'active' => 'user',
            'groups_options' => $groups_options,
            'validation' => \Config\Services::validation()
        ];
        return view('permission/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'name' => [
                'rules' => 'required|is_unique[permissions.name]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'uri' => [
                'rules' => 'required|is_unique[permissions.uri]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/permission/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $idPermission = get_uuid();


            $this->permissionModel->insert([
                'id' => $idPermission,
                'name' => $this->request->getVar('name'),
                'uri' => $this->request->getVar('uri')
            ]);

            /*Insert data baru ke table GROUP_PERMISSIONS berdasarkan ID_GROUP */
            if ($this->request->getVar('groups[]')) {
                foreach ($this->request->getVar('groups[]') as $r) {
                    $groupPermissionsData[] = array(
                        'id' => get_uuid(),
                        'id_group' => $r,
                        'id_permission' => $idPermission
                    );
                }
                $this->groupPermissionModel->insertBatch($groupPermissionsData);
            }

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/permission/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');

                /*update session USER_PERMISSIONS*/
                $this->authModel = new AuthModel();
                session()->set('user_permissions', $this->authModel->getUserPermissions(session()->get('id_user')));
            }
        } catch (\Exception $e) {
            return redirect()->to('/permission/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/permission');
    }

    public function edit($id)
    {
        $groups = $this->groupModel->getData();
        foreach ($groups as $r) {
            $groups_options[$r->id] = $r->name;
        }

        $data = [
            'title' => 'Edit Permission',
            'active' => 'permission',
            'data' => $this->permissionModel->getDataById($id),
            'groups_options' => $groups_options,
            'groups_selected' => $this->groupPermissionModel->getGroupsSelectedByIdPermission($id),
            'validation' => \Config\Services::validation()
        ];
        return view('permission/edit', $data);
    }

    public function update($id)
    {

        $validation = [
            'name' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'uri' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/permission/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'name' => $this->request->getVar('name'),
                    'uri' => $this->request->getVar('uri')
                ];

                /*Update data ke table PERMISSIONS berdasarkan ID */
                $this->permissionModel->save($data);

                /*Delete data lama di table GROUP_PERMISSIONS berdasarkan ID_PERMISSION */
                $this->groupPermissionModel->where('id_permission', $id);
                $this->groupPermissionModel->delete();

                /*Insert data baru ke table GROUP_PERMISSIONS berdasarkan ID_PERMISSION */
                if ($this->request->getVar('groups[]')) {
                    foreach ($this->request->getVar('groups[]') as $r) {
                        $groupPermissionsData[] = array(
                            'id' => get_uuid(),
                            'id_group' => $r,
                            'id_permission' => $id
                        );
                    }
                    $this->groupPermissionModel->insertBatch($groupPermissionsData);
                }

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/permission/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');

                    /*update session USER_PERMISSIONS*/
                    $this->authModel = new AuthModel();
                    session()->set('user_permissions', $this->authModel->getUserPermissions(session()->get('id_user')));
                }
            } catch (\Exception $e) {
                return redirect()->to('/permission/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/permission');
        }
    }

    public function detail($id)
    {
        $groups = $this->groupModel->getData();
        foreach ($groups as $r) {
            $groups_options[$r->id] = $r->name;
        }

        $data = [
            'title' => 'Detail Permission',
            'active' => 'permission',
            'data' => $this->permissionModel->getDataById($id),
            'groups_options' => $groups_options,
            'groups_selected' => $this->groupPermissionModel->getGroupsSelectedByIdPermission($id),
            'validation' => \Config\Services::validation()
        ];

        if (empty($data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Permission Data ' . $id . ' is not found.');
        }
        return view('permission/detail', $data);
    }

    public function delete($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->groupPermissionModel->where('id_permission', $id);
            $this->groupPermissionModel->delete();

            $this->permissionModel->delete($id);
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return redirect()->to('/permission')->with('messages', 'failed delete data');
            } else {

                session()->setFlashData('messages', 'Data was successfully deleted');

                /*update session USER_PERMISSIONS*/
                $this->authModel = new AuthModel();
                session()->set('user_permissions', $this->authModel->getUserPermissions(session()->get('id_user')));
            }
        } catch (\Exception $e) {
            return redirect()->to('/permission')->with('messages', $e->getMessage());
        }

        return redirect()->to('/permission');
    }

    public function deletedSelected()
    {
        $ids = json_decode(stripslashes($_POST['id']));

        $arrayID = array();

        foreach ($ids as $id) {
            $id = str_replace('#row_', '', $id);
            array_push($arrayID, $id);
        }

        if ($arrayID) {


            $response['array_ids'] = $arrayID;

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $this->groupPermissionModel->whereIn('id_permission', $arrayID);
                $this->groupPermissionModel->delete();

                $this->permissionModel->whereIn('id', $arrayID);
                $this->permissionModel->delete();
                $db->transComplete();

                if ($db->transStatus() === FALSE) {
                    $response['status'] = false;
                } else {

                    $response['status'] = true;

                    /*update session USER_PERMISSIONS*/
                    $this->authModel = new AuthModel();
                    session()->set('user_permissions', $this->authModel->getUserPermissions(session()->get('id_user')));
                }
            } catch (\Exception $e) {
                $response['status'] = false;
                $response['messages'] = $e->getMessage();
            }
        } else {
            $response['status'] = false;
            $response['messages'] = 'Please select first';
        }

        echo json_encode($response);
    }

    public function getAllClassMethodInAllController()
    {
        $array_uri = [];
        $method_exception = ['__construct', 'initController', 'forceHTTPS', 'cachePage', 'loadHelpers', 'validate'];

        /* dapatkan lokasi path dari script ini saat di jalankan */
        $src    = dirname(__FILE__);

        /* baca semua folder file yang ada di folder dari path ini*/
        $dir = opendir($src);

        /* di loop sebanyak file yang ada di folder/path ini*/
        while (false !== ($file = readdir($dir))) {

            /* kecualikan (.) dan (..)*/
            if (($file != '.') && ($file != '..')) {

                /* hilangkan nama extention dari file berjenis (.php) / ambil namanya saja*/
                $file = str_replace('.php', '', $file);

                /*ambil namespaces nya*/
                $namespacesClass = 'App\Controllers\\' . $file;

                /*inisialisasi nama/object class*/
                $class = new $namespacesClass();

                /*dapatkan semua method yang ada di class tersebut*/
                $class_methods = get_class_methods($class);

                /*loop sebanyak method yang ada di class tersebut*/
                foreach ($class_methods as $r) {

                    /*kecualikan method yang ada di array method_exception*/
                    if (!in_array($r, $method_exception)) {

                        /*kecualikan method index, cukup ambil nama class nya saja*/
                        $r = ($r == 'index') ? strtolower($file) : strtolower($file) . '/' . $r;

                        /*masukan kedalam array_uri */
                        array_push($array_uri, $r);
                    }
                }
            }
        }

        // dd($array_uri);
        $response['array_uri'] = $array_uri;

        /*masukan kedalam database*/
        try {
            $db      = \Config\Database::connect();

            $db->transStart();
            if ($array_uri) {

                $data = [];
                foreach ($array_uri as $r) {

                    /*insert data hanya untuk yang belum ada */
                    if ($this->permissionModel->isNotExistUri($r)) {
                        $data[] = array(
                            'id' => get_uuid(),
                            'name' => $r,
                            'uri' => $r
                        );
                    }
                }
                if ($data) {
                    $this->permissionModel->insertBatch($data);
                } else {
                    $response['status'] = false;
                    $response['messages'] = 'none new class & method';
                }
            }

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                $response['status'] = false;
                $response['messages'] = 'transaction failed';
            } else {
                $response['status'] = true;
                $response['messages'] = 'transaction success';
            }
        } catch (\Exception $e) {
            $response['status'] = false;
            $response['messages'] = $e->getMessage();
        }
        echo json_encode($response);
    }
}
