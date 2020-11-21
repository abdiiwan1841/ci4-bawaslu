<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\LaporanModel;

class Laporan extends BaseController
{

    protected $laporanModel;
    protected $sessionTrackingModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
    }

    public function index()
    {

        $provinsi_options = array();

        $provinsi = $this->laporanModel->getProvinsi();
        foreach ($provinsi as $r) {
            $provinsi_options[$r->id] = $r->nama_provinsi;
        }

        $data = [
            'title' => 'Satuan Kerja',
            'active' => 'laporan',
            'provinsi_options' => $provinsi_options,
        ];
        return view('laporan/satuan_kerja', $data);
    }

    public function list($idWilayah)
    {

        session()->set('id_wilayah', $idWilayah);
        session()->set('id_laporan', '');
        session()->set('id_temuan', '');
        session()->set('id_rekomendasi', '');
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $data = [
            'title' => 'Laporan',
            'active' => 'laporan'
        ];
        return view('laporan/index', $data);
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
                a.no_surat_tugas,
                a.tanggal_surat_tugas,
                a.unit_pelaksana,
                a.nip_pimpinan,
                a.pimpinan_satuan_kerja,
                a.nama_satuan_kerja,
                a.tahun_anggaran,
                a.nilai_anggaran,
                a.realisasi_anggaran,
                a.audit_anggaran,
                a.jenis_anggaran,
                a.id_auditor,
                a.id_satuan_kerja
                FROM laporan a 
                WHERE
                a.id_satuan_kerja='" . session()->get('id_wilayah') . "'
                AND a.deleted_at IS NULL
                ORDER BY a.nama_laporan ASC
            ) temp
            ";

        $columns = array(
            array('db' => 'id', 'dt' => 0),
            array('db' => 'no_laporan', 'dt' => 1),
            array('db' => 'tanggal_laporan', 'dt' => 2),
            array('db' => 'nama_laporan', 'dt' => 3),
            array('db' => 'no_surat_tugas', 'dt' => 4),
            array('db' => 'tanggal_surat_tugas', 'dt' => 5),
            array('db' => 'unit_pelaksana', 'dt' => 6),
            array('db' => 'nip_pimpinan', 'dt' => 7),
            array('db' => 'pimpinan_satuan_kerja', 'dt' => 8),
            array('db' => 'nama_satuan_kerja', 'dt' => 9),
            array('db' => 'tahun_anggaran', 'dt' => 10),
            array(
                'db'        => 'nilai_anggaran',
                'dt'        => 11,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'realisasi_anggaran',
                'dt'        => 12,
                'formatter' => function ($i, $row) {
                    $html = format_number($i);
                    return $html;
                }
            ),
            array(
                'db'        => 'audit_anggaran',
                'dt'        => 13,
                'formatter' => function ($i, $row) {
                    $html = $i;
                    return $html;
                }
            ),
            array('db' => 'jenis_anggaran', 'dt' => 14),
            array(
                'db'        => 'id',
                'dt'        => 15,
                'formatter' => function ($i, $row) {
                    $html = '
                    <center>
                    <a href="' . base_url('laporan/edit/' . $i) . '" class="btn btn-primary btn-small" data-original-title="Edit">
                    Edit
                    </a>
                    <a href="' . base_url('temuan/index/' . $i) . '" class="btn btn-success btn-small" data-original-title="Edit">
                    Temuan
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

    public function create()
    {

        $data = [
            'title' => 'Buat Laporan Baru',
            'active' => 'Laporan',
            'validation' => \Config\Services::validation()
        ];
        return view('laporan/create', $data);
    }

    public function save()
    {

        if (!$this->validate([
            'no_laporan' => [
                'rules' => 'required|is_unique[laporan.no_laporan]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ],
            'tanggal_laporan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ])) {
            return redirect()->to('/laporan/create')->withInput();
        }

        try {
            $db      = \Config\Database::connect();

            $db->transStart();

            $this->laporanModel->insert([
                'id' => get_uuid(),
                'no_laporan' => $this->request->getVar('no_laporan'),
                'tanggal_laporan' => $this->request->getVar('tanggal_laporan'),
                'nama_laporan' => $this->request->getVar('nama_laporan'),
                'no_surat_tugas' => $this->request->getVar('no_surat_tugas'),
                'tanggal_surat_tugas' => $this->request->getVar('tanggal_surat_tugas'),
                'unit_pelaksana' => $this->request->getVar('unit_pelaksana'),
                'nip_pimpinan' => $this->request->getVar('nip_pimpinan'),
                'pimpinan_satuan_kerja' => $this->request->getVar('pimpinan_satuan_kerja'),
                'nama_satuan_kerja' => $this->request->getVar('nama_satuan_kerja'),
                'tahun_anggaran' => $this->request->getVar('tahun_anggaran'),
                'nilai_anggaran' => $this->request->getVar('nilai_anggaran'),
                'realisasi_anggaran' => $this->request->getVar('realisasi_anggaran'),
                'audit_anggaran' => $this->request->getVar('audit_anggaran'),
                'jenis_anggaran' => $this->request->getVar('jenis_anggaran'),
                'id_auditor' => session()->get('id_user'),
                'id_satuan_kerja' => session()->get('id_wilayah')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/laporan/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception $e) {
            return redirect()->to('/laporan/create')->withInput()->with('messages', $e->getMessage());
        }

        return redirect()->to('/laporan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Laporan',
            'active' => 'Laporan',
            'data' => $this->laporanModel->getDataById($id),
            'validation' => \Config\Services::validation()
        ];

        return view('laporan/edit', $data);
    }

    public function update($id)
    {

        $idLaporan = $this->request->getVar('id_laporan');

        $validation = [
            'no_laporan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'tanggal_laporan' => [
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
                    'no_laporan' => $this->request->getVar('no_laporan'),
                    'tanggal_laporan' => $this->request->getVar('tanggal_laporan'),
                    'nama_laporan' => $this->request->getVar('nama_laporan'),
                    'no_surat_tugas' => $this->request->getVar('no_surat_tugas'),
                    'tanggal_surat_tugas' => $this->request->getVar('tanggal_surat_tugas'),
                    'unit_pelaksana' => $this->request->getVar('unit_pelaksana'),
                    'nip_pimpinan' => $this->request->getVar('nip_pimpinan'),
                    'pimpinan_satuan_kerja' => $this->request->getVar('pimpinan_satuan_kerja'),
                    'nama_satuan_kerja' => $this->request->getVar('nama_satuan_kerja'),
                    'tahun_anggaran' => $this->request->getVar('tahun_anggaran'),
                    'nilai_anggaran' => $this->request->getVar('nilai_anggaran'),
                    'realisasi_anggaran' => $this->request->getVar('realisasi_anggaran'),
                    'audit_anggaran' => $this->request->getVar('audit_anggaran'),
                    'jenis_anggaran' => $this->request->getVar('jenis_anggaran'),
                    'id_auditor' => session()->get('id_user')
                ];

                /*Update data ke table Positions berdasarkan ID */
                $this->laporanModel->save($data);

                $db->transComplete();
                if ($db->transStatus() === FALSE) {
                    return redirect()->to('/laporan/edit/' . $id)->withInput();
                } else {
                    session()->setFlashData('messages', 'Data was successfully updated');
                    return redirect()->to('/laporan/list/' . session()->get('id_wilayah'));
                }
            } catch (\Exception $e) {
                return redirect()->to('/laporan/edit/' . $id)->withInput()->with('messages', $e->getMessage());
            }

            return redirect()->to('/laporan/list/' . session()->get('id_wilayah'));
        }
    }

    public function ajaxGetKabupatenByProvinsiId($idProvinsi)
    {
        $response['data'] = $this->laporanModel->getKabupaten($idProvinsi);
        echo json_encode($response);
    }
}
