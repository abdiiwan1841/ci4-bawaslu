<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\TindaklanjutModel;

class Tindaklanjut extends BaseController
{

    protected $tindaklanjutModel;

    public function __construct()
    {
        $this->tindaklanjutModel = new TindaklanjutModel();
    }

    public function index($idRekomendasi)
    {

        // session()->set('id_satuan_kerja', $idWilayah);
        // session()->set('id_laporan', $idLaporan);
        // session()->set('id_temuan', $idTemuan);
        // session()->set('id_sebab', $idSebab);
        session()->set('id_rekomendasi', $idRekomendasi);
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $r = $this->tindaklanjutModel->showButton($idRekomendasi);

        // $showButtonSesuai = ($r->status_terima > 0) ? true : false;
        $showButtonTidakDapatDiTL = ($r->jumlah_tl > 0) ? false : true;

        $data = [
            'title' => 'Tindak Lanjut',
            'active' => 'tindaklanjut',
            'summary' => $this->tindaklanjutModel->getSisaNilaiRekomendasi($idRekomendasi),
            'id_rekomendasi' => $idRekomendasi,
            // 'show_button_sesuai' => $showButtonSesuai,
            'show_button_tidak_dapat_di_tl' => $showButtonTidakDapatDiTL
        ];
        // dd($data);
        return view('tindak_lanjut/index', $data);
    }

    public function datatables($idRekomendasi)
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.nilai_rekomendasi,
                a.`nilai_tindak_lanjut`,
                a.`nilai_terverifikasi`,
                a.`nilai_sisa_rekomendasi`,
                a.id_rekomendasi,
                a.remark_auditor,
                a.remark_auditee,
                a.status,
                a.read_status,
                a.created_at
                FROM tindak_lanjut a
                WHERE a.deleted_at IS NULL 
                AND a.id_rekomendasi='" . $idRekomendasi . "'
                ORDER BY a.nilai_rekomendasi ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'created_at', 'dt' => 1),
            array(
                'db'        => 'nilai_tindak_lanjut',
                'dt'        => 2,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'nilai_terverifikasi',
                'dt'        => 3,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array('db' => 'remark_auditor', 'dt' => 4),
            array('db' => 'remark_auditee', 'dt' => 5),
            array(
                'db'        => 'id',
                'dt'        => 6,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <a href="' . base_url('tindaklanjut/verifikasi/' . $i) . '" class="btn btn-success btn-small" data-original-title="Verifikasi">
                    Verifikasi
                    </a>
                    <a href="' . base_url('bukti/index/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">
                    Bukti
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

    public function create($idRekomendasi)
    {

        $data = [
            'title' => 'Buat Tindak Lanjut Baru',
            'active' => 'tindaklanjut',
            'id_rekomendasi' => $idRekomendasi,
            'validation' => \Config\Services::validation()
        ];
        return view('tindak_lanjut/create', $data);
    }

    public function save()
    {
        $idRekomendasi = $this->request->getVar('id_rekomendasi');
        if (!$this->validate([
            'nilai_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'nilai_akhir_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/tindaklanjut/create/' . $idRekomendasi)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->tindaklanjutModel->insert([
                'id' => get_uuid(),
                'nilai_rekomendasi' => $this->request->getVar('nilai_rekomendasi'),
                'nilai_sisa_rekomendasi' => $this->request->getVar('nilai_sisa_rekomendasi'),
                'nilai_akhir_rekomendasi' => $this->request->getVar('nilai_akhir_rekomendasi'),
                'id_rekomendasi' => $this->request->getVar('id_rekomendasi')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/tindaklanjut/create/' . $idRekomendasi)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/tindaklanjut/create/' . $idRekomendasi)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/tindaklanjut/index/' . $idRekomendasi);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Tindaklanjut',
            'active' => 'tindaklanjut',
            'data' => $this->tindaklanjutModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('tindak_lanjut/edit', $data);
    }

    public function update($id)
    {

        $idRekomendasi = $this->request->getVar('id_rekomendasi');

        $validation = [
            'nilai_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nilai_akhir_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/tindaklanjut/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'nilai_rekomendasi' => $this->request->getVar('nilai_rekomendasi'),
                    'nilai_sisa_rekomendasi' => $this->request->getVar('nilai_sisa_rekomendasi'),
                    'nilai_akhir_rekomendasi' => $this->request->getVar('nilai_akhir_rekomendasi'),
                    'id_rekomendasi' => $this->request->getVar('id_rekomendasi')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->tindaklanjutModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/tindaklanjut/edit/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/tindaklanjut/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/tindaklanjut/index/' . $idRekomendasi);
        }
    }

    // public function tolak($id)
    // {
    //     try {
    //         $db      = \Config\Database::connect();

    //         $db->transStart();

    //         $data = [
    //             'id' => $id,
    //             'status' => 'TOLAK'
    //         ];
    //         $this->tindaklanjutModel->save($data);

    //         $db->transComplete();
    //         if ($db->transStatus() === FALSE) {
    //             return redirect()->to('/tindaklanjut/index/' . session()->get('id_rekomendasi'))->withInput();
    //         } else {

    //             session()->setFlashData('messages', 'Data berhasil di updated');
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()->to('/tindaklanjut/index/' . session()->get('id_rekomendasi'))->withInput()->with('messages', $e->getMessage());
    //     }

    //     return redirect()->to('/tindaklanjut/index/' . session()->get('id_rekomendasi'));
    // }

    // public function terima($id)
    // {
    //     try {
    //         $db      = \Config\Database::connect();

    //         $db->transStart();

    //         $data = [
    //             'id' => $id,
    //             'status' => 'TERIMA'
    //         ];
    //         $this->tindaklanjutModel->save($data);

    //         $db->transComplete();
    //         if ($db->transStatus() === FALSE) {
    //             return redirect()->to('/tindaklanjut/index/' . session()->get('id_rekomendasi'))->withInput();
    //         } else {

    //             session()->setFlashData('messages', 'Data berhasil di updated');
    //         }
    //     } catch (\Exception $e) {
    //         return redirect()->to('/tindaklanjut/index/' . session()->get('id_rekomendasi'))->withInput()->with('messages', $e->getMessage());
    //     }

    //     return redirect()->to('/tindaklanjut/index/' . session()->get('id_rekomendasi'));
    // }

    public function readed($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $data = [
                'id' => $id,
                'read_status' => '1'
            ];
            $this->tindaklanjutModel->save($data);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
            } else {
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function verifikasi($idTindakLanjut)
    {
        if ($_POST) {
            session()->set('id_satuan_kerja', $this->request->getVar('id_satuan_kerja'));
            session()->set('id_laporan', $this->request->getVar('id_laporan'));
            session()->set('id_temuan', $this->request->getVar('id_temuan'));
            session()->set('id_sebab', $this->request->getVar('id_sebab'));
            session()->set('id_rekomendasi', $this->request->getVar('id_rekomendasi'));
            session()->set('id_tindak_lanjut', $this->request->getVar('id_tindak_lanjut'));
        }

        $this->readed($idTindakLanjut);

        $r = $this->tindaklanjutModel->getDataById($idTindakLanjut);

        $data = [
            'title' => 'Verifikasi Tindak Lanjut',
            'active' => 'tindaklanjut',
            'id_rekomendasi' => $r->id_rekomendasi,
            'total_terverifikasi' => $this->tindaklanjutModel->getTotalNilaiTerverifikasi($r->id_rekomendasi),
            'data' => $r,
            'validation' => \Config\Services::validation()
        ];
        // dd($data['data']);
        return view('tindak_lanjut/verifikasi', $data);
    }

    public function saveVerifikasi($id)
    {
        $idRekomendasi = $this->request->getVar('id_rekomendasi');

        $validation = [
            'nilai_terverifikasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/tindaklanjut/verifikasi/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'nilai_terverifikasi' => $this->request->getVar('nilai_terverifikasi'),
                    'remark_auditor' => $this->request->getVar('remark_auditor')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->tindaklanjutModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/tindaklanjut/verifikasi/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/tindaklanjut/verifikasi/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/tindaklanjut/index/' . $idRekomendasi);
        }
    }
}
