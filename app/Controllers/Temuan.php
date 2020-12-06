<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\TemuanModel;

class Temuan extends BaseController
{

    protected $temuanModel;

    public function __construct()
    {
        $this->temuanModel = new TemuanModel();
    }

    public function index($idLaporan)
    {

        session()->set('ketua_tim', $this->temuanModel->getKetuaTim($idLaporan));
        session()->set('id_laporan', $idLaporan);
        session()->set('id_temuan', '');
        session()->set('id_sebab', '');
        session()->set('id_rekomendasi', '');
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $data = [
            'title' => 'Temuan',
            'active' => 'temuan',
            'id_laporan' => $idLaporan
        ];

        return view('temuan/index', $data);
    }

    public function datatables($idLaporan)
    {
        $table =
            "
            (
                SELECT 
                 a.id,
                 a.no_temuan,
                 a.memo_temuan,
                 a.id_jenis_temuan1,
                 a.id_jenis_temuan2,
                 a.id_jenis_temuan3,
                 b.deskripsi AS jenis_tunjangan,
                 a.nilai_temuan,
                 a.id_laporan
                FROM temuan a 
                LEFT JOIN jenis_temuan b ON b.id=a.id_jenis_temuan3 
                WHERE a.deleted_at IS NULL 
                AND b.deleted_at IS NULL
                AND a.id_laporan='" . $idLaporan . "'
                ORDER BY a.no_temuan ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_temuan', 'dt' => 1),
            array('db' => 'memo_temuan', 'dt' => 2),
            array('db' => 'jenis_tunjangan', 'dt' => 3),
            array(
                'db'        => 'nilai_temuan',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'id',
                'dt'        => 5,
                'formatter' => function ($i, $row) {

                    $html = '<center>';
                    if (session()->get('ketua_tim') == session()->get('id_pegawai')) {
                        $html .= '<a href="' . base_url('temuan/edit/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">Edit</a>';
                    }
                    $html .= '
                    <a href="' . base_url('sebab/index/' . $i) . '" class="btn btn-danger btn-small" data-original-title="Edit">
                    Sebab
                    </a>
                    </center>';
                    return $html;
                }
            )
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }

    public function create($idLaporan)
    {

        $jenis_temuan_options = [];

        $jenisTemuan = $this->temuanModel->getJenisTemuan();
        foreach ($jenisTemuan as $r) {
            $jenis_temuan_options[$r->id] = $r->nama;
        }

        $data = [
            'title' => 'Buat Temuan Baru',
            'active' => 'temuan',
            'id_laporan' => $idLaporan,
            'no_temuan' => $this->temuanModel->counter($idLaporan),
            'jenis_temuan_options' => $jenis_temuan_options,
            'validation' => \Config\Services::validation()
        ];

        // dd($data);
        return view('temuan/create', $data);
    }

    public function save()
    {
        $idLaporan = $this->request->getVar('id_laporan');

        $_POST['no_temuan'] = $this->temuanModel->counter($idLaporan);

        if (!$this->validate([
            'no_temuan' => [
                'rules' => 'required|is_unique[temuan.no_temuan]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'memo_temuan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'id_jenis_temuan3' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/temuan/create/' . $idLaporan)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->temuanModel->insert([
                'id' => get_uuid(),
                'no_temuan' => $this->request->getVar('no_temuan'),
                'memo_temuan' => $this->request->getVar('memo_temuan'),
                'id_jenis_temuan1' => $this->request->getVar('id_jenis_temuan1'),
                'id_jenis_temuan2' => $this->request->getVar('id_jenis_temuan2'),
                'id_jenis_temuan3' => $this->request->getVar('id_jenis_temuan3'),
                'nilai_temuan' => $this->request->getVar('nilai_temuan'),
                'id_laporan' => $this->request->getVar('id_laporan')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/temuan/create/' . $idLaporan)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/temuan/create/' . $idLaporan)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/temuan/index/' . $idLaporan);
    }

    public function edit($id)
    {

        $jenis_temuan_options = [];

        $jenisTemuan = $this->temuanModel->getJenisTemuan();
        foreach ($jenisTemuan as $r) {
            $jenis_temuan_options[$r->id] = $r->nama;
        }
        $data = [
            'title' => 'Edit Temuan',
            'active' => 'temuan',
            'jenis_temuan_options' => $jenis_temuan_options,
            'data' => $this->temuanModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('temuan/edit', $data);
    }

    public function update($id)
    {

        $idLaporan = $this->request->getVar('id_laporan');

        $validation = [
            'no_temuan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'memo_temuan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'id_jenis_temuan3' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/temuan/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    // 'no_temuan' => $this->request->getVar('no_temuan'),
                    'memo_temuan' => $this->request->getVar('memo_temuan'),
                    'id_jenis_temuan1' => $this->request->getVar('id_jenis_temuan1'),
                    'id_jenis_temuan2' => $this->request->getVar('id_jenis_temuan2'),
                    'id_jenis_temuan3' => $this->request->getVar('id_jenis_temuan3'),
                    'nilai_temuan' => $this->request->getVar('nilai_temuan'),
                    'id_laporan' => $this->request->getVar('id_laporan')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->temuanModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/temuan/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/temuan/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/temuan/index/' . $idLaporan);
        }
    }

    public function ajaxGetJenisTemuan($idParent)
    {
        $response['data'] = $this->temuanModel->ajaxGetJenisTemuan($idParent);
        echo json_encode($response);
    }
}
