<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Models\LaporanModel;
use Fpdf\Fpdf;

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

        session()->set('id_satuan_kerja', '');
        session()->set('id_laporan', '');
        session()->set('id_temuan', '');
        session()->set('id_sebab', '');
        session()->set('id_rekomendasi', '');
        session()->set('id_tindak_lanjut', '');
        session()->set('id_bukti', '');

        $eselon1_options = array();

        $eselon1 = $this->laporanModel->getEselon1();
        foreach ($eselon1 as $r) {
            $eselon1_options[$r->id] = $r->nama;
        }

        $data = [
            'title' => 'Satuan Kerja',
            'active' => 'laporan',
            'eselon1_options' => $eselon1_options,
        ];
        return view('laporan/satuan_kerja', $data);
    }

    public function list()
    {
        if ($this->request->getVar('eselon1') != '' || session()->get('id_satuan_kerja') != '') {

            $eselon1 = $this->request->getVar('eselon1');
            $eselon2 = $this->request->getVar('eselon2');
            $eselon3 = $this->request->getVar('eselon3');

            $idSatuanKerja = $eselon1;

            if ($eselon3) {
                $idSatuanKerja = $eselon3;
            } else {
                if ($eselon2) {
                    $idSatuanKerja = $eselon2;
                } else {
                    $idSatuanKerja = $eselon1;
                }
            }

            if (!session()->get('id_satuan_kerja')) {
                session()->set('id_satuan_kerja', $idSatuanKerja);
            }
            session()->set('id_laporan', '');
            session()->set('id_temuan', '');
            session()->set('id_sebab', '');
            session()->set('id_rekomendasi', '');
            session()->set('id_tindak_lanjut', '');
            session()->set('id_bukti', '');

            $data = [
                'title' => 'Laporan',
                'active' => 'laporan'
            ];
            return view('laporan/index', $data);
        } else {
            return redirect()->to('/laporan')->withInput();
        }
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
                a.id_satuan_kerja='" . session()->get('id_satuan_kerja') . "'
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
            'no_laporan' => $this->laporanModel->counter(),
            'validation' => \Config\Services::validation()
        ];
        return view('laporan/create', $data);
    }

    public function save()
    {

        $_POST['no_laporan'] = $this->laporanModel->counter();

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
            ],
            'nama_laporan' => [
                'rules' => 'required',
                'label' => 'Nama Laporan',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'no_surat_tugas' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'tanggal_surat_tugas' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'unit_pelaksana' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nip_pimpinan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'pimpinan_satuan_kerja' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nama_satuan_kerja' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'tahun_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nilai_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'realisasi_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'audit_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'jenis_anggaran' => [
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
                'id_satuan_kerja' => session()->get('id_satuan_kerja')
            ]);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                return redirect()->to('/laporan/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
                return redirect()->to('/laporan/list/' . session()->get('id_satuan_kerja'));
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
            ],
            'nama_laporan' => [
                'rules' => 'required',
                'label' => 'Nama Laporan',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'no_surat_tugas' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'tanggal_surat_tugas' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'unit_pelaksana' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nip_pimpinan' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'pimpinan_satuan_kerja' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nama_satuan_kerja' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'tahun_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'nilai_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'realisasi_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'audit_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'jenis_anggaran' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/laporan/edit/' . $id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                $db      = \Config\Database::connect();

                $db->transStart();

                $data = [
                    'id' => $id,
                    // 'no_laporan' => $this->request->getVar('no_laporan'),
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

    public function ajaxGetEselon2($idEselon1)
    {
        $response['data'] = $this->laporanModel->getEselon2($idEselon1);
        echo json_encode($response);
    }

    public function ajaxGetEselon3($idEselon2)
    {
        $response['data'] = $this->laporanModel->getEselon3($idEselon2);
        echo json_encode($response);
    }

    public function report()
    {

        // $data = $this->orderModel->getDataById($id);
        // $detail = $this->orderDetailModel->pdfOrderInvoice($id);

        // dd($detail);
        // die();
        $i = array(10, 30, 70, 20, 20, 20, 20, 20, 20, 40, 40, 40, 40, 40, 40, 40, 40, 60, 60);
        $w = array(35, 10, 53, 53, 10, 35);

        $pdf = new Fpdf('L', 'mm', array(700, 297));
        $pdf->SetMargins(10, 10, 10, 10);
        $pdf->SetCompression(true);
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 8);

        $pdf->Image('images/icon.png', 303, 10, 10, 10);
        $pdf->Cell($i[0] + $i[1] + $i[2] + $i[3] + $i[4] + $i[5] + $i[6] + $i[7] + $i[8] + $i[9] + $i[10] + $i[11] + $i[12] + $i[13] + $i[14] + $i[15] + $i[16] + $i[17] + $i[18], 6, 'MATRIKS PEMETAAN TINDAK LANJUT', 0, 0, 'C');
        $pdf->Ln();
        $pdf->Cell($i[0] + $i[1] + $i[2] + $i[3] + $i[4] + $i[5] + $i[6] + $i[7] + $i[8] + $i[9] + $i[10] + $i[11] + $i[12] + $i[13] + $i[14] + $i[15] + $i[16] + $i[17] + $i[18], 6, 'HASIL PEMERIKSAAN', 0, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell($i[0], 6,  '', 1, 0, 'C');
        $pdf->Cell($i[1], 6,  '', 1, 0, 'C');
        $pdf->Cell($i[2], 6,  '', 1, 0, 'C');
        $pdf->Cell($i[3] + $i[4] + $i[5], 6,  '', 1, 0, 'C');
        $pdf->Cell($i[6] + $i[7] + $i[8], 6,  '', 1, 0, 'C');
        $pdf->Cell($i[9] + $i[10] + $i[11] + $i[12] + $i[13] + $i[14] + $i[15] + $i[16], 6,  'Hasil Pemantauan Tindak Lanjut', 1, 0, 'C');

        $pdf->Cell($i[17], 6, '', 1, 0, 'C');
        $pdf->Cell($i[18], 6, "", 1, 0, 'C');
        $pdf->Ln();
        $pdf->MultiCell($i[0], 10,  'No', 1, 0, 'C');
        $pdf->MultiCell($i[1], 10,  'Nomor Laporan', 1, 0, 'C');
        $pdf->MultiCell($i[2], 10,  'Judul Laporan', 1, 0, 'C');
        $pdf->MultiCell($i[3] + $i[4] + $i[5], 10,  'Temuan Pemeriksaan', 1, 0, 'C');
        $pdf->MultiCell($i[6] + $i[7] + $i[8], 10,  'Rekomendasi', 1, 0, 'C');
        $pdf->MultiCell($i[9] + $i[10],  10,  "Sesuai dengan Rekomendasi", 1, 0, 'C');
        $pdf->MultiCell($i[11] + $i[12], 10, "Belum Sesuai dan Dalam\n Proses Tindak Lanjut", 1, 0, 'C');
        $pdf->MultiCell($i[13] + $i[14], 10, "Belum ditindaklanjuti", 1, 0, 'C');
        $pdf->MultiCell($i[15] + $i[16], 10, "Tidak Dapat ditindaklanjuti\n dengan alasan yang sah", 1, 0, 'C');
        $pdf->MultiCell($i[17], 10, "Kesimpulan", 1, 0, 'C');
        $pdf->MultiCell($i[18], 10, "Nilai Penyerahan Aset\n atau Penyetoran Uang\n ke Kas Negara (Rp)", 1, 0, 'C');
        $pdf->Ln();
        $pdf->Cell($i[0], 6,  '', 1, 0, 'C');
        $pdf->Cell($i[1], 6,  '', 1, 0, 'C');
        $pdf->Cell($i[2], 6,  '', 1, 0, 'C');
        $pdf->Cell($i[3], 6,  'Uraian', 1, 0, 'C');
        $pdf->Cell($i[4], 6,  'Jml', 1, 0, 'C');
        $pdf->Cell($i[5], 6,  'Nilai', 1, 0, 'C');
        $pdf->Cell($i[6], 6,  'Uraian', 1, 0, 'C');
        $pdf->Cell($i[7], 6,  'Jml', 1, 0, 'C');
        $pdf->Cell($i[8], 6,  'Nilai', 1, 0, 'C');
        $pdf->Cell($i[9], 6,  'Jml', 1, 0, 'C');
        $pdf->Cell($i[10], 6, 'Nilai (Rp)', 1, 0, 'C');
        $pdf->Cell($i[11], 6, 'Jml', 1, 0, 'C');
        $pdf->Cell($i[12], 6, 'Nilai (Rp)', 1, 0, 'C');
        $pdf->Cell($i[13], 6, 'Jml', 1, 0, 'C');
        $pdf->Cell($i[14], 6, 'Nilai (Rp)', 1, 0, 'C');
        $pdf->Cell($i[15], 6, 'Jml', 1, 0, 'C');
        $pdf->Cell($i[16], 6, 'Nilai (Rp)', 1, 0, 'C');
        $pdf->Cell($i[17], 6, '', 1, 0, 'C');
        $pdf->Cell($i[18], 6, "", 1, 0, 'C');
        $pdf->Ln();

        $pdf->Cell($i[0], 6,  '1', 1, 0, 'C');
        $pdf->Cell($i[1], 6,  '2', 1, 0, 'C');
        $pdf->Cell($i[2], 6,  '3', 1, 0, 'C');
        $pdf->Cell($i[3], 6,  '4', 1, 0, 'C');
        $pdf->Cell($i[4], 6,  '5', 1, 0, 'C');
        $pdf->Cell($i[5], 6,  '6', 1, 0, 'C');
        $pdf->Cell($i[6], 6,  '7', 1, 0, 'C');
        $pdf->Cell($i[7], 6,  '8', 1, 0, 'C');
        $pdf->Cell($i[8], 6,  '9', 1, 0, 'C');
        $pdf->Cell($i[9], 6,  '10', 1, 0, 'C');
        $pdf->Cell($i[10], 6, '11', 1, 0, 'C');
        $pdf->Cell($i[11], 6, '12', 1, 0, 'C');
        $pdf->Cell($i[12], 6, '13', 1, 0, 'C');
        $pdf->Cell($i[13], 6, '14', 1, 0, 'C');
        $pdf->Cell($i[14], 6, '15', 1, 0, 'C');
        $pdf->Cell($i[15], 6, '16', 1, 0, 'C');
        $pdf->Cell($i[16], 6, '17', 1, 0, 'C');
        $pdf->Cell($i[17], 6, '18', 1, 0, 'C');
        $pdf->Cell($i[18], 6, '19', 1, 0, 'C');
        $pdf->Ln();

        $no = 1;
        $totalPiece = 0;
        $totalYard = 0;
        $grandTotal = 0;
        // foreach ($detail as $r) {
        //     $pdf->Cell($i[0], 6, $no++, 1, 0, 'C');
        //     $pdf->Cell($i[1], 6, $r->product_name, 1, 0, 'L');
        //     $pdf->Cell($i[2], 6, $r->sum_qty_piece, 1, 0, 'R');
        //     $pdf->Cell($i[3], 6, format_number($r->sum_qty_yard), 1, 0, 'R');
        //     $pdf->Cell($i[4], 6, format_number($r->price, true), 1, 0, 'R');
        //     $pdf->Cell($i[5], 6, format_number($r->sum_subtotal, true), 1, 0, 'R');
        //     $pdf->Ln();

        //     $qty_detail = $this->orderDetailModel->getPieceYardDetail($r->id_order, $r->id_product_color);
        //     $pdf->Cell($i[0], 6, '', 1, 0, 'C');
        //     $pdf->Cell($i[1], 6, $qty_detail, 1, 0, 'L');
        //     $pdf->Cell($i[2], 6, '', 1, 0, 'R');
        //     $pdf->Cell($i[3], 6, '', 1, 0, 'R');
        //     $pdf->Cell($i[4], 6, '', 1, 0, 'R');
        //     $pdf->Cell($i[5], 6, '', 1, 0, 'R');
        //     $pdf->Ln();

        //     $totalPiece += $r->sum_qty_piece;
        //     $totalYard += $r->sum_qty_yard;
        //     $grandTotal += $r->sum_subtotal;
        // }

        // $pdf->Cell($i[0] + $i[1], 6, 'Total', 1, 0, 'C');
        // $pdf->Cell($i[2], 6, format_number($totalPiece), 1, 0, 'R');
        // $pdf->Cell($i[3], 6, format_number($totalYard), 1, 0, 'R');
        // $pdf->Cell($i[4], 6, '', 1, 0, 'C');
        // $pdf->Cell($i[5], 6, format_number($grandTotal, true), 1, 0, 'R');
        // $pdf->Ln();

        // $pdf->Cell($i[0], 6, '', '', 0, 'L');
        // $pdf->Cell($i[1], 6, '', '', 0, 'C');
        // $pdf->Cell($i[2], 6, '', '', 0, 'C');
        // $pdf->Cell($i[3], 6, '', '', 0, 'C');
        // $pdf->Cell($i[4], 6, '', '', 0, 'C');
        // $pdf->Cell($i[5], 6, '', '', 0, 'C');
        // $pdf->Ln();

        // $pdf->Cell($w[0], 6, 'Hormat Kami', '', 0, 'C');
        // $pdf->Cell($w[1], 6, '', '', 0, 'C');
        // $pdf->Cell($w[2], 6, '', '', 0, 'C');
        // $pdf->Cell($w[3], 6, '', '', 0, 'C');
        // $pdf->Cell($w[4], 6, '', '', 0, 'C');
        // $pdf->Cell($w[5], 6, 'Penerima', '', 0, 'C');
        // $pdf->Ln();

        // $pdf->SetFont('Arial', 'B', 7);
        // $pdf->Cell($w[0], 4, '', '', 0, 'C');
        // $pdf->Cell($w[1], 4, '', '', 0, 'C');
        // $pdf->Cell($w[2] + $w[3], 4, 'Perhatian!!!', 'LRT', 0, 'C');
        // $pdf->Cell($w[4], 4, '', '', 0, 'C');
        // $pdf->Cell($w[5], 4, '', '', 0, 'C');
        // $pdf->Ln();
        // $pdf->SetFont('Arial', '', 7);
        // $pdf->Cell($w[0], 4, '', '', 0, 'C');
        // $pdf->Cell($w[1], 4, '', '', 0, 'C');
        // $pdf->Cell($w[2] + $w[3], 4, '1. Barang diterima dengan baik dan sesuai dengan pembelian.', 'LR', 0, 'L');
        // $pdf->Cell($w[4], 4, '', '', 0, 'C');
        // $pdf->Cell($w[5], 4, '', '', 0, 'C');
        // $pdf->Ln();
        // $pdf->Cell($w[0], 4, '', '', 0, 'C');
        // $pdf->Cell($w[1], 4, '', '', 0, 'C');
        // $pdf->Cell($w[2] + $w[3], 4, '2. Kehilangan / kerusakan diluar Toko, bukan tanggung jawab Kami.', 'LR', 0, 'L');
        // $pdf->Cell($w[4], 4, '', '', 0, 'C');
        // $pdf->Cell($w[5], 4, '', '', 0, 'C');
        // $pdf->Ln();
        // $pdf->Cell($w[0], 4, '', 'B', 0, 'C');
        // $pdf->Cell($w[1], 4, '', '', 0, 'C');
        // $pdf->Cell($w[2] + $w[3], 4, '3. Barang yang sudah dibeli tidak boleh dikembalikan.', 'LRB', 0, 'L');
        // $pdf->Cell($w[4], 4, '', '', 0, 'C');
        // $pdf->Cell($w[5], 4, '', 'B', 0, 'C');
        // $pdf->Ln();

        $namaFilePDF = "MATRIKS_PEMANTAUAN_TINDAK_LANJUT_" . date('Y-m-d_H-i-s') . ".pdf";
        $pdf->Output($namaFilePDF, 'D');

        // I: send the file inline to the browser. The PDF viewer is used if available.
        // D: send to the browser and force a file download with the name given by name.
        // F: save to a local file with the name given by name (may include a path).
        // S: return the document as a string.


    }
}
