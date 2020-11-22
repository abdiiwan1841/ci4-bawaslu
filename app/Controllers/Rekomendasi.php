<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\RekomendasiModel;

class Rekomendasi extends BaseController
{

    protected $rekomendasiModel;

    public function __construct()
    {
        $this->rekomendasiModel = new RekomendasiModel();
    }

    public function index($idSebab)
    {

        // session()->set('id_wilayah', $idWilayah);
        // session()->set('id_laporan', $idLaporan);
        // session()->set('id_temuan', $idTemuan);
        session()->set('id_sebab', $idSebab);
        session()->set('id_rekomendasi', '');
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $data = [
            'title' => 'Rekomendasi',
            'active' => 'rekomendasi',
            'id_sebab' => $idSebab
        ];
        return view('rekomendasi/index', $data);
    }

    public function datatables($idSebab)
    {
        $table =
            "
            (
                SELECT 
                 a.id,
                 a.no_rekomendasi,
                 a.memo_rekomendasi,
                 a.nilai_rekomendasi,
                 a.nama_penanggung_jawab,
                 a.id_sebab 
                FROM rekomendasi a
                WHERE a.deleted_at IS NULL 
                AND a.id_sebab='" . $idSebab . "'
                ORDER BY a.no_rekomendasi ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_rekomendasi', 'dt' => 1),
            array('db' => 'memo_rekomendasi', 'dt' => 2),
            array(
                'db'        => 'nilai_rekomendasi',
                'dt'        => 3,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array('db' => 'nama_penanggung_jawab', 'dt' => 4),
            array(
                'db'        => 'id',
                'dt'        => 5,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <a href="' . base_url('rekomendasi/edit/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">
                    Edit
                    </a>
                    <a href="' . base_url('tindaklanjut/index/' . $i) . '" class="btn btn-success btn-small" data-original-title="Edit">
                    Tindak Lanjut
                    </a>
                    </center>';
                    return $html;
                }
            ),
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }

    public function create($idSebab)
    {

        $data = [
            'title' => 'Buat Rekomendasi Baru',
            'active' => 'rekomendasi',
            'id_sebab' => $idSebab,
            'validation' => \Config\Services::validation()
        ];
        return view('rekomendasi/create', $data);
    }

    public function save()
    {
        $idSebab = $this->request->getVar('id_sebab');
        if (!$this->validate([
            'no_rekomendasi' => [
                'rules' => 'required|is_unique[rekomendasi.no_rekomendasi]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'memo_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/rekomendasi/create/' . $idSebab)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->rekomendasiModel->insert([
                'id' => get_uuid(),
                'no_rekomendasi' => $this->request->getVar('no_rekomendasi'),
                'memo_rekomendasi' => $this->request->getVar('memo_rekomendasi'),
                'nilai_rekomendasi' => $this->request->getVar('nilai_rekomendasi'),
                'nama_penanggung_jawab' => $this->request->getVar('nama_penanggung_jawab'),
                'id_sebab' => $this->request->getVar('id_sebab')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/rekomendasi/create/' . $idSebab)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/rekomendasi/create/' . $idSebab)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/rekomendasi/index/' . $idSebab);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Rekomendasi',
            'active' => 'rekomendasi',
            'data' => $this->rekomendasiModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('rekomendasi/edit', $data);
    }

    public function update($id)
    {

        $idSebab = $this->request->getVar('id_sebab');

        $validation = [
            'no_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'memo_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/rekomendasi/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'no_rekomendasi' => $this->request->getVar('no_rekomendasi'),
                    'memo_rekomendasi' => $this->request->getVar('memo_rekomendasi'),
                    'nilai_rekomendasi' => $this->request->getVar('nilai_rekomendasi'),
                    'nama_penanggung_jawab' => $this->request->getVar('nama_penanggung_jawab'),
                    'id_sebab' => $this->request->getVar('id_sebab')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->rekomendasiModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/rekomendasi/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/rekomendasi/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/rekomendasi/index/' . $idSebab);
        }
    }
}
