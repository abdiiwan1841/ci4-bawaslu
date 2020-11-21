<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\BuktiModel;

class Bukti extends BaseController
{

    protected $buktiModel;

    public function __construct()
    {
        $this->buktiModel = new BuktiModel();
    }

    public function index($idTindakLanjut)
    {

        // session()->set('id_wilayah', $idWilayah);
        // session()->set('id_laporan', $idLaporan);
        // session()->set('id_temuan', $idTemuan);
        // session()->set('id_rekomendasi', $idRekomendasi);
        session()->set('id_tindak_lanjut', $idTindakLanjut);
        session()->set('id_bukti', '');

        $data = [
            'title' => 'Bukti',
            'active' => 'bukti',
            'id_tindak_lanjut' => $idTindakLanjut
        ];
        return view('bukti/index', $data);
    }

    public function datatables($idTindakLanjut)
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.no_bukti,
                a.nama_bukti,
                a.nilai_bukti,
                a.id_tindak_lanjut,
                a.lampiran
                FROM bukti a
                WHERE a.deleted_at IS NULL 
                AND a.id_tindak_lanjut='" . $idTindakLanjut . "'
                ORDER BY a.no_bukti ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_bukti', 'dt' => 1),
            array('db' => 'nama_bukti', 'dt' => 2),
            array(
                'db'        => 'nilai_bukti',
                'dt'        => 3,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'lampiran',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = '';
                    if ($i) {
                        $html = '<p class="url">
                                    <a href="' . base_url('/attachments/' . $i) . '" target="_blank" style="color:#3a86c8;">
                                        <span class="fs1 text-info" aria-hidden="true" data-icon="&#xe0c5;"></span>' . $row['nama_bukti'] . '
                                    </a>
                                </p>';
                    }
                    return $html;
                }
            ),
        );

        $primaryKey = 'id';

        $condition = null;

        tarkiman_datatables($table, $columns, $condition, $primaryKey);
    }

    public function create($idTindakLanjut)
    {

        $data = [
            'title' => 'Buat Bukti Baru',
            'active' => 'bukti',
            'id_tindak_lanjut' => $idTindakLanjut,
            'validation' => \Config\Services::validation()
        ];
        return view('bukti/create', $data);
    }

    public function save()
    {
        $idTindakLanjut = $this->request->getVar('id_tindak_lanjut');
        if (!$this->validate([
            'no_bukti' => [
                'rules' => 'required|is_unique[bukti.no_bukti]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'nama_bukti' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/bukti/create/' . $idTindakLanjut)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->buktiModel->insert([
                'id' => get_uuid(),
                'no_bukti' => $this->request->getVar('no_bukti'),
                'nama_bukti' => $this->request->getVar('nama_bukti'),
                'nilai_bukti' => $this->request->getVar('nilai_bukti'),
                'id_tindak_lanjut' => $this->request->getVar('id_tindak_lanjut')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/bukti/create/' . $idTindakLanjut)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/bukti/create/' . $idTindakLanjut)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/bukti/index/' . $idTindakLanjut);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Bukti',
            'active' => 'bukti',
            'data' => $this->buktiModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('bukti/edit', $data);
    }

    public function update($id)
    {

        $idTindakLanjut = $this->request->getVar('id_tindak_lanjut');

        $validation = [
            'no_bukti' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nama_bukti' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/bukti/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'no_bukti' => $this->request->getVar('no_bukti'),
                    'nama_bukti' => $this->request->getVar('nama_bukti'),
                    'nilai_bukti' => $this->request->getVar('nilai_bukti'),
                    'id_tindak_lanjut' => $this->request->getVar('id_tindak_lanjut')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->buktiModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/bukti/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/bukti/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/bukti/index/' . $idTindakLanjut);
        }
    }
}
