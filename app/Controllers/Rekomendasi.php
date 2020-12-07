<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\RekomendasiModel;
use App\Models\PenanggungJawabModel;

class Rekomendasi extends BaseController
{

    protected $penanggungJawabModel;

    public function __construct()
    {
        $this->rekomendasiModel = new RekomendasiModel();
        $this->penanggungJawabModel = new PenanggungJawabModel();
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
                 a.id_jenis_rekomendasi,
                 b.deskripsi AS jenis_rekomendasi,
                 a.memo_rekomendasi,
                 a.nilai_rekomendasi,
                 (SELECT GROUP_CONCAT(c.nama_penanggung_jawab SEPARATOR ', ') FROM penanggung_jawab c WHERE c.id_rekomendasi=a.id) AS nama_penanggung_jawab,
                 a.id_sebab,
                 a.status
                FROM rekomendasi a
                LEFT JOIN jenis_rekomendasi b ON b.id=a.id_jenis_rekomendasi 
                WHERE a.deleted_at IS NULL 
                AND b.deleted_at IS NULL
                AND a.id_sebab='" . $idSebab . "'
                ORDER BY a.no_rekomendasi ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_rekomendasi', 'dt' => 1),
            array('db' => 'jenis_rekomendasi', 'dt' => 2),
            array('db' => 'memo_rekomendasi', 'dt' => 3),
            array(
                'db'        => 'nilai_rekomendasi',
                'dt'        => 4,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array('db' => 'nama_penanggung_jawab', 'dt' => 5),
            array(
                'db'        => 'status',
                'dt'        => 6,
                'formatter' => function ($i, $row) {
                    if ($i == 'BELUM_TL') {
                        $html = 'Belum TL';
                    } elseif ($i == 'BELUM_SESUAI') {
                        $html = '<span style="color:blue;">Belum Sesuai</span>';
                    } elseif ($i == 'SESUAI') {
                        $html = '<span style="color:green;">Sesuai</span>';
                    } elseif ($i == 'TIDAK_DAPAT_DI_TL') {
                        $html = '<span style="color:red;">Tidak Dapat di TL</span>';
                    }
                    return $html;
                }
            ),
            array(
                'db'        => 'id',
                'dt'        => 7,
                'formatter' => function ($i, $row) {
                    $html = '<center>';
                    if (session()->get('ketua_tim') == session()->get('id_pegawai')) {
                        if ($row[6] != 'SESUAI') {
                            $html .= '<a href="' . base_url('rekomendasi/edit/' . $i) . '" class="btn btn-primary btn-small" data-original-title="">Edit</a>';
                        }
                    }
                    if ($row['status'] == 'TIDAK_DAPAT_DI_TL') {
                        $html .= ' <a href="' . base_url('rekomendasi/detailAlasanTidakDiTL/' . $i) . '" class="btn btn-danger btn-small" data-original-title="">
                    Detail Tidak di TL
                    </a>';
                    } else {
                        $html .= ' <a href="' . base_url('tindaklanjut/index/' . $i) . '" class="btn btn-success btn-small" data-original-title="">
                    Tindak Lanjut
                    </a>';
                    }

                    $html .= '
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

        $jenis_rekomendasi_options = [];

        $jenisRekomendasi = $this->rekomendasiModel->getJenisRekomendasi();
        foreach ($jenisRekomendasi as $r) {
            $jenis_rekomendasi_options[$r->id] = $r->nama;
        }

        $data = [
            'title' => 'Buat Rekomendasi Baru',
            'active' => 'rekomendasi',
            'id_sebab' => $idSebab,
            'no_rekomendasi' => $this->rekomendasiModel->counter($idSebab),
            'jenis_rekomendasi_options' => $jenis_rekomendasi_options,
            'validation' => \Config\Services::validation()
        ];
        return view('rekomendasi/create', $data);
    }

    public function save()
    {
        $idSebab = $this->request->getVar('id_sebab');
        $_POST['no_rekomendasi'] = $this->rekomendasiModel->counter($idSebab);

        if (!$this->validate([
            'no_rekomendasi' => [
                'rules' => 'required|is_unique[rekomendasi.no_rekomendasi]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'id_jenis_rekomendasi' => [
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
        ])) {
            return redirect()->to('/rekomendasi/create/' . $idSebab)->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $idRekomendasi = get_uuid();

            $this->rekomendasiModel->insert([
                'id' => $idRekomendasi,
                'no_rekomendasi' => $this->request->getVar('no_rekomendasi'),
                'id_jenis_rekomendasi' => $this->request->getVar('id_jenis_rekomendasi'),
                'memo_rekomendasi' => $this->request->getVar('memo_rekomendasi'),
                'nilai_rekomendasi' => $this->request->getVar('nilai_rekomendasi'),
                'id_sebab' => $this->request->getVar('id_sebab')
            ]);

            foreach ($this->request->getVar('nama_penanggung_jawab[]') as $r) {
                $penanggungJawab[] = array(
                    'id' => get_uuid(),
                    'id_rekomendasi' => $idRekomendasi,
                    'nama_penanggung_jawab' => $r
                );
            }
            $this->penanggungJawabModel->insertBatch($penanggungJawab);

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

        $jenis_rekomendasi_options = [];

        $jenisRekomendasi = $this->rekomendasiModel->getJenisRekomendasi();
        foreach ($jenisRekomendasi as $r) {
            $jenis_rekomendasi_options[$r->id] = $r->nama;
        }

        $optionsTags = $this->rekomendasiModel->getSelected($id);
        $options = [];
        foreach ($optionsTags as $r) {
            $options[$r] = $r;
        }

        $data = [
            'title' => 'Edit Rekomendasi',
            'active' => 'rekomendasi',
            'data' => $this->rekomendasiModel->getDataById($id),
            'options_selected' => $optionsTags,
            'options_tags' => $options,
            'jenis_rekomendasi_options' => $jenis_rekomendasi_options,
            'validation' => \Config\Services::validation()
        ];

        return view('rekomendasi/edit', $data);
    }

    public function update($id)
    {

        // dd($_POST);

        $idSebab = $this->request->getVar('id_sebab');

        $validation = [
            'no_rekomendasi' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'id_jenis_rekomendasi' => [
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
                    // 'no_rekomendasi' => $this->request->getVar('no_rekomendasi'),
                    'id_jenis_rekomendasi' => $this->request->getVar('id_jenis_rekomendasi'),
                    'memo_rekomendasi' => $this->request->getVar('memo_rekomendasi'),
                    'nilai_rekomendasi' => $this->request->getVar('nilai_rekomendasi'),
                    'id_sebab' => $this->request->getVar('id_sebab')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->rekomendasiModel->save($data);

                /*Delete data lama di table Penanggung Jawab berdasarkan ID_LAPORAN */
                $this->penanggungJawabModel->where('id_rekomendasi', $id);
                $this->penanggungJawabModel->delete();

                foreach ($this->request->getVar('nama_penanggung_jawab[]') as $r) {
                    $penanggungJawab[] = array(
                        'id' => get_uuid(),
                        'id_rekomendasi' => $id,
                        'nama_penanggung_jawab' => $r
                    );
                }
                $this->penanggungJawabModel->insertBatch($penanggungJawab);

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

    public function updateStatusRekomendasiSesuai($id)
    {
        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $data = [
                'id' => $id,
                'status' => 'SESUAI'
            ];
            $this->rekomendasiModel->save($data);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/rekomendasi/index/' . session()->get('id_sebab'))->withInput();
            } else {

                session()->setFlashData('messages', 'Data berhasil di updated');
            }
        } catch (\Exception $e) {
            return redirect()->to('/rekomendasi/index/' . session()->get('id_sebab'))->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/rekomendasi/index/' . session()->get('id_sebab'));
    }

    public function tidakDapatDiTL($idRekomendasi)
    {

        $data = [
            'title' => 'Form Keterangan Tidak Dapat Di TL',
            'active' => 'rekomendasi',
            'data' => $this->rekomendasiModel->getDataById($idRekomendasi),
            'id_sebab' => session()->get('id_sebab'),
            'id_rekomendasi' => $idRekomendasi,
            'validation' => \Config\Services::validation()
        ];
        return view('rekomendasi/tidak_dapat_di_tl', $data);
    }

    public function saveTidakDapatDiTL()
    {
        $idRekomendasi = $this->request->getVar('id_rekomendasi');
        if (!$this->validate([
            'alasan_tidak_di_tl' => [
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
            return redirect()->to('/rekomendasi/tidakDapatDiTL/' . $idRekomendasi)->withInput();
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

            $this->rekomendasiModel->save([
                'id' => $idRekomendasi,
                'status' => 'TIDAK_DAPAT_DI_TL',
                'alasan_tidak_di_tl' => $this->request->getVar('alasan_tidak_di_tl'),
                'lampiran_tidak_di_tl' => $namaFile
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/rekomendasi/tidakDapatDiTL/' . $idRekomendasi)->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/rekomendasi/tidakDapatDiTL/' . $idRekomendasi)->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/rekomendasi/index/' . session()->get('id_sebab'));
    }

    public function detailAlasanTidakDiTL($id)
    {
        $data = [
            'title' => 'Detail Asalan Tidak Dapat di TL',
            'active' => 'rekomendasi',
            'data' => $this->rekomendasiModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('rekomendasi/detail_alasan_tidak_di_tl', $data);
    }
}
