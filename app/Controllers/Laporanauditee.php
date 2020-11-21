<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\LaporanAuditeeModel;
use App\Models\TindaklanjutModel;
use App\Models\BuktiModel;

class Laporanauditee extends BaseController
{

    protected $laporanAuditeeModel;
    protected $sessionTrackingModel;
    protected $buktiModel;

    public function __construct()
    {
        $this->laporanAuditeeModel = new LaporanAuditeeModel();
        $this->tindaklanjutModel = new TindaklanjutModel();
        $this->buktiModel = new BuktiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan Auditee',
            'active' => 'laporan'
        ];
        return view('laporan_auditee/index', $data);
    }

    public function datatables()
    {
        $table =
            "
            (
                SELECT 
                a.id,
                a.no_laporan,
                a.tanggal_laporan,
                a.nama_laporan,
                '0' AS sesuai,
                '0' AS belum_sesuai,
                '0' AS belum_ditindak_lanjuti,
                '0' AS tidak_dapat_ditindak_lanjuti
                FROM laporan a
                WHERE a.deleted_at IS NULL 
                AND a.id_satuan_kerja='" . session()->get('id_satuan_kerja') . "'
                ORDER BY a.nama_laporan ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_laporan', 'dt' => 1),
            array('db' => 'tanggal_laporan', 'dt' => 2),
            array('db' => 'nama_laporan', 'dt' => 3),
            array('db' => 'sesuai', 'dt' => 4),
            array('db' => 'belum_sesuai', 'dt' => 5),
            array('db' => 'belum_ditindak_lanjuti', 'dt' => 6),
            array('db' => 'tidak_dapat_ditindak_lanjuti', 'dt' => 7),
            array(
                'db'        => 'id',
                'dt'        => 8,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <a href="' . base_url('laporanauditee/detail/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">
                    Detail
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

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Laporan Auditee',
            'active' => 'Laporan Auditee',
            'data' => $this->laporanAuditeeModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);

        return view('laporan_auditee/detail', $data);
    }

    public function tindaklanjut($idRekomendasi)
    {
        $data = [
            'title' => 'Detail Laporan Auditee',
            'active' => 'Laporan Auditee',
            'data' => $this->laporanAuditeeModel->getTindakLanjut($idRekomendasi),
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);

        return view('laporan_auditee/tindaklanjut', $data);
    }

    public function createtindaklanjut($idRekomendasi)
    {
        $data = [
            'title' => 'Buat Tindak Lanjut Baru',
            'active' => 'Laporan Auditee',
            'id_rekomendasi' => $idRekomendasi,
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);

        return view('laporan_auditee/create_tindaklanjut', $data);
    }

    public function savetindaklanjut()
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
            ],
            'nilai_sisa_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/laporanauditee/createtindaklanjut/' . $idRekomendasi)->withInput();
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
                return redirect()->to('/laporanauditee/createtindaklanjut/' . $idRekomendasi)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/laporanauditee/createtindaklanjut/' . $idRekomendasi)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/laporanauditee/tindaklanjut/' . $idRekomendasi);
    }

    public function edittindaklanjut($idTindakLanjut)
    {
        $data = [
            'title' => 'Edit Tindak Lanjut',
            'active' => 'Laporan Auditee',
            'data' => $this->tindaklanjutModel->getDataById($idTindakLanjut),
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);
        return view('laporan_auditee/edit_tindaklanjut', $data);
    }

    public function updatetindaklanjut($id)
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
            ],
            'nilai_sisa_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/laporanauditee/edittindaklanjut/' . $id)->withInput()->with('messages', 'Validation Error');
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
                    return redirect()->to('/laporanauditee/edittindaklanjut/' . $id)->withInput();
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/laporanauditee/edittindaklanjut/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/laporanauditee/tindaklanjut/' . $idRekomendasi);
        }
    }

    public function bukti($idTindakLanjut)
    {
        $data = [
            'title' => 'Detail Laporan Auditee',
            'active' => 'Laporan Auditee',
            'data' => $this->laporanAuditeeModel->getBukti($idTindakLanjut),
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);

        return view('laporan_auditee/bukti', $data);
    }

    public function createbukti($idTindakLanjut)
    {
        $data = [
            'title' => 'Buat Bukti Baru',
            'active' => 'Laporan Auditee',
            'id_tindak_lanjut' => $idTindakLanjut,
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);

        return view('laporan_auditee/create_bukti', $data);
    }

    public function savebukti()
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
            ],
            'lampiran' => [
                'rules' => 'max_size[lampiran,1024]|ext_in[lampiran,jpg,jpeg,png,pdf,xls,xlsx,doc,docx]',
                'errors' => [
                    'max_size' => 'ukuran tidak boleh melebihi 1024 KB',
                    'ext_in' => 'tipe file harus .jpg | .png |.pdf |.xls | .xlsx |.doc | .docx'
                ]
            ]
        ])) {
            return redirect()->to('/laporanauditee/createbukti/' . $idTindakLanjut)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            //ambil gambar
            $file = $this->request->getFile('lampiran');

            if ($file->getError() == 4) { //4 = ga ada file yang di upload
                $namaFile = "default.png";
            } else {

                //ambil nama file;
                // $namaFile = $file->getName();
                $namaFile = $file->getRandomName();

                //pindahkan file ke folder IMAGES
                $file->move('uploads', $namaFile); //kalau di buar random nama file dijadikan parameter
            }

            $this->buktiModel->insert([
                'id' => get_uuid(),
                'no_bukti' => $this->request->getVar('no_bukti'),
                'nama_bukti' => $this->request->getVar('nama_bukti'),
                'nilai_bukti' => $this->request->getVar('nilai_bukti'),
                'id_tindak_lanjut' => $this->request->getVar('id_tindak_lanjut'),
                'lampiran' => $namaFile
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/laporanauditee/createbukti/' . $idTindakLanjut)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/laporanauditee/createbukti/' . $idTindakLanjut)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/laporanauditee/bukti/' . $idTindakLanjut);
    }

    public function editbukti($idBukti)
    {
        $data = [
            'title' => 'Edit Bukti',
            'active' => 'Laporan Auditee',
            'data' => $this->buktiModel->getDataById($idBukti),
            'validation' => \Config\Services::validation()
        ];

        // dd($data['data']);
        return view('laporan_auditee/edit_bukti', $data);
    }

    public function updatebukti($id)
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
            ],
            'lampiran' => [
                'rules' => 'max_size[lampiran,1024]|ext_in[lampiran,jpg,jpeg,png,pdf,xls,xlsx,doc,docx]',
                'errors' => [
                    'max_size' => 'ukuran tidak boleh melebihi 1024 KB',
                    'ext_in' => 'tipe file harus .jpg | .png |.pdf |.xls | .xlsx |.doc | .docx'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/laporanauditee/editbukti/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            //ambil gambar
            $file = $this->request->getFile('lampiran');

            //cek gambar apakah ada perubahan
            if ($file->getError() == 4) { //4 = ga ada file yang di upload
                $namaFile = $this->request->getVar('old_lampiran');
            } else {

                //ambil nama file;
                // $namaFile = $file->getName();
                $namaFile = $file->getRandomName();

                //pindahkan file ke folder IMAGES
                $file->move('uploads', $namaFile); //kalau di buar random nama file dijadikan parameter

            }

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    'no_bukti' => $this->request->getVar('no_bukti'),
                    'nama_bukti' => $this->request->getVar('nama_bukti'),
                    'nilai_bukti' => $this->request->getVar('nilai_bukti'),
                    'id_tindak_lanjut' => $this->request->getVar('id_tindak_lanjut'),
                    'lampiran' => $namaFile
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->buktiModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/laporanauditee/editbukti/' . $id)->withInput();
                } else {
                    //hapus file lama jika bukan file default
                    if ($this->request->getVar('old_lampiran') != 'default.png') {
                        try {
                            unlink('uploads/' . $this->request->getVar('old_lampiran'));
                        } catch (\Exception $e) {
                            $exceptionMessages = '<br/>' . $e->getMessage();
                        }
                    }
                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception $e) {
                return redirect()->to('/laporanauditee/editbukti/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/laporanauditee/bukti/' . $idTindakLanjut);
        }
    }
}
