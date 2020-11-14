<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployeeModel;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\StockModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Fpdf\Fpdf;

class Report extends BaseController
{
    protected $employeeModel;
    protected $orderModel;
    protected $orderDetailModel;
    protected $stockModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
        $this->stockModel = new StockModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Report',
            'active' => 'report',
            'data' => null
        ];

        return view('report/index', $data);
    }
    // public function excel()
	// {
	// 	$spreadsheet = new Spreadsheet();
	// 	$sheet = $spreadsheet->getActiveSheet();
	// 	$sheet->setCellValue('A1', 'Hello World !');
	// 	$writer = new Xlsx($spreadsheet);
	// 	//$writer->save('helloworld.xlsx');


	// 	//sesuaikan headernya 
	// 	// header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	// 	// header("Cache-Control: no-store, no-cache, must-revalidate");
	// 	// header("Cache-Control: post-check=0, pre-check=0", false);
	// 	// header("Pragma: no-cache");
	// 	// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// 	// //ubah nama file saat diunduh
	// 	// header('Content-Disposition: attachment;filename="update_shopee_product.xlsx"');

	// 	header('Content-Type: application/vnd.ms-excel');
	// 	header('Content-Disposition: attachment;filename="Laporan_Transaction.xlsx"');
	// 	// header('Cache-Control: max-age=0');

	// 	$writer->save('php://output');
	// }

	public function pdf()
    {
        $id = '04163ca3-d141-f8df-4a2d-474312d15496';

        // $idOrder = '3702d26f-4b2e-24c1-55b4-f57615105398';
        // $idProductColor = 'a7d86c7e-70da-f21f-bd3f-b3e3eb78793f';
        // $data = $this->orderDetailModel->pdfOrderInvoice($idOrder);
        // $data = $this->orderDetailModel->getPieceYardDetail($idOrder, $idProductColor);
        // dd($data);

        $data = $this->orderModel->getDataById($id);
        $detail = $this->orderDetailModel->pdfOrderInvoice($id);

        // dd($detail);
        // die();
        $i = array(10, 76, 20, 20, 30, 40);
        $w = array(35, 10, 53, 53, 10, 35);

        // $pdf = new Fpdf('P', 'mm', array(139.7, 215.9));
        $pdf = new Fpdf('L', 'mm', array(218, 138)); /*CONTINUOUS FORM*/
        $pdf->SetMargins(10, 10, 10, 10);
        $pdf->SetCompression(true);
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 8);

        $pdf->Image('images/logo.png', 5, 5, 50, 22);
        $pdf->Cell($i[0], 6, '', '', 0, 'L');
        $pdf->Cell($i[1], 6, '', '', 0, 'C');
        $pdf->Cell($i[2], 6, '', '', 0, 'C');
        $pdf->Cell($i[3], 6, '', '', 0, 'C');
        $pdf->Cell($i[4] + $i[5], 6, 'Bandung, ' . date_format(date_create($data->order_date), "d F Y"), '', 0, 'L');
        $pdf->Ln();
        $pdf->Cell($i[0], 6, '', '', 0, 'L');
        $pdf->Cell($i[1], 6, '', '', 0, 'C');
        $pdf->Cell($i[2], 6, '', '', 0, 'C');
        $pdf->Cell($i[3], 6, '', '', 0, 'C');
        $pdf->Cell($i[4] + $i[5], 6, 'Kepada Yth.', 'T', 0, 'L');
        $pdf->Ln();
        $pdf->Cell($i[0] + $i[1], 6, 'PT.Intan Triputra Abadi', '', 0, 'L');
        $pdf->Cell($i[2], 6, '', '', 0, 'C');
        $pdf->Cell($i[3], 6, '', '', 0, 'C');
        $pdf->Cell($i[4] + $i[5], 6, $data->customer, '', 0, 'L');
        $pdf->Ln();
        $pdf->Cell($i[0], 6, '', '', 0, 'L');
        $pdf->Cell($i[1], 6, '', '', 0, 'C');
        $pdf->Cell($i[2], 6, '', '', 0, 'C');
        $pdf->Cell($i[3], 6, '', '', 0, 'C');
        $pdf->Cell($i[4], 6, '', '', 0, 'C');
        $pdf->Cell($i[5], 6, '', '', 0, 'C');
        $pdf->Ln();
        $pdf->Cell($i[0] + $i[1], 6, 'No. Faktur : ' . $data->order_number, '', 0, 'L');
        $pdf->Cell($i[2], 6, '', 1, 0, 'C');
        $pdf->Cell($i[3], 6, '', 1, 0, 'C');
        $pdf->Cell($i[4], 6, '', 1, 0, 'C');
        $pdf->Cell($i[5], 6, '', 1, 0, 'C');
        $pdf->Ln();

        $pdf->Cell($i[0], 6, 'No', 1, 0, 'L');
        $pdf->Cell($i[1], 6, 'Nama Barang', 1, 0, 'C');
        $pdf->Cell($i[2], 6, 'Pcs', 1, 0, 'C');
        $pdf->Cell($i[3], 6, 'Yard', 1, 0, 'C');
        $pdf->Cell($i[4], 6, 'Harga', 1, 0, 'C');
        $pdf->Cell($i[5], 6, 'Subtotal', 1, 0, 'C');
        $pdf->Ln();

        $no = 1;
        $totalPiece = 0;
        $totalYard = 0;
        $grandTotal = 0;
        foreach ($detail as $r) {
            $pdf->Cell($i[0], 6, $no++, 1, 0, 'C');
            $pdf->Cell($i[1], 6, $r->product_name, 1, 0, 'L');
            $pdf->Cell($i[2], 6, $r->sum_qty_piece, 1, 0, 'R');
            $pdf->Cell($i[3], 6, format_number($r->sum_qty_yard), 1, 0, 'R');
            $pdf->Cell($i[4], 6, format_number($r->price, true), 1, 0, 'R');
            $pdf->Cell($i[5], 6, format_number($r->sum_subtotal, true), 1, 0, 'R');
            $pdf->Ln();

            $qty_detail = $this->orderDetailModel->getPieceYardDetail($r->id_order, $r->id_product_color);
            $pdf->Cell($i[0], 6, '', 1, 0, 'C');
            $pdf->Cell($i[1], 6, $qty_detail, 1, 0, 'L');
            $pdf->Cell($i[2], 6, '', 1, 0, 'R');
            $pdf->Cell($i[3], 6, '', 1, 0, 'R');
            $pdf->Cell($i[4], 6, '', 1, 0, 'R');
            $pdf->Cell($i[5], 6, '', 1, 0, 'R');
            $pdf->Ln();

            $totalPiece += $r->sum_qty_piece;
            $totalYard += $r->sum_qty_yard;
            $grandTotal += $r->sum_subtotal;
        }

        $pdf->Cell($i[0] + $i[1], 6, 'Total', 1, 0, 'C');
        $pdf->Cell($i[2], 6, format_number($totalPiece), 1, 0, 'R');
        $pdf->Cell($i[3], 6, format_number($totalYard), 1, 0, 'R');
        $pdf->Cell($i[4], 6, '', 1, 0, 'C');
        $pdf->Cell($i[5], 6, format_number($grandTotal, true), 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($i[0], 6, '', '', 0, 'L');
        $pdf->Cell($i[1], 6, '', '', 0, 'C');
        $pdf->Cell($i[2], 6, '', '', 0, 'C');
        $pdf->Cell($i[3], 6, '', '', 0, 'C');
        $pdf->Cell($i[4], 6, '', '', 0, 'C');
        $pdf->Cell($i[5], 6, '', '', 0, 'C');
        $pdf->Ln();

        $pdf->Cell($w[0], 6, 'Hormat Kami', '', 0, 'C');
        $pdf->Cell($w[1], 6, '', '', 0, 'C');
        $pdf->Cell($w[2], 6, '', '', 0, 'C');
        $pdf->Cell($w[3], 6, '', '', 0, 'C');
        $pdf->Cell($w[4], 6, '', '', 0, 'C');
        $pdf->Cell($w[5], 6, 'Penerima', '', 0, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell($w[0], 4, '', '', 0, 'C');
        $pdf->Cell($w[1], 4, '', '', 0, 'C');
        $pdf->Cell($w[2] + $w[3], 4, 'Perhatian!!!', 'LRT', 0, 'C');
        $pdf->Cell($w[4], 4, '', '', 0, 'C');
        $pdf->Cell($w[5], 4, '', '', 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell($w[0], 4, '', '', 0, 'C');
        $pdf->Cell($w[1], 4, '', '', 0, 'C');
        $pdf->Cell($w[2] + $w[3], 4, '1. Barang diterima dengan baik dan sesuai dengan pembelian.', 'LR', 0, 'L');
        $pdf->Cell($w[4], 4, '', '', 0, 'C');
        $pdf->Cell($w[5], 4, '', '', 0, 'C');
        $pdf->Ln();
        $pdf->Cell($w[0], 4, '', '', 0, 'C');
        $pdf->Cell($w[1], 4, '', '', 0, 'C');
        $pdf->Cell($w[2] + $w[3], 4, '2. Kehilangan / kerusakan diluar Toko, bukan tanggung jawab Kami.', 'LR', 0, 'L');
        $pdf->Cell($w[4], 4, '', '', 0, 'C');
        $pdf->Cell($w[5], 4, '', '', 0, 'C');
        $pdf->Ln();
        $pdf->Cell($w[0], 4, '', 'B', 0, 'C');
        $pdf->Cell($w[1], 4, '', '', 0, 'C');
        $pdf->Cell($w[2] + $w[3], 4, '3. Barang yang sudah dibeli tidak boleh dikembalikan.', 'LRB', 0, 'L');
        $pdf->Cell($w[4], 4, '', '', 0, 'C');
        $pdf->Cell($w[5], 4, '', 'B', 0, 'C');
        $pdf->Ln();

        $namaFilePDF = "ORDER_" . date('Y-m-d_H-i-s') . ".pdf";
        $pdf->Output($namaFilePDF, 'D');

        // I: send the file inline to the browser. The PDF viewer is used if available.
        // D: send to the browser and force a file download with the name given by name.
        // F: save to a local file with the name given by name (may include a path).
        // S: return the document as a string.


    }

    public function excel($location = '')
    {
        $keywords = $this->request->getVar('keywords');
        // dd($location);
        // ambil data transaction dari database
        $data = $this->stockModel->getDataByLocation(strtoupper($location), $keywords);
        // panggil class Sreadsheet baru
        $spreadsheet = new Spreadsheet();
        // Buat custom header pada file excel
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Stock Code')
            ->setCellValue('C1', 'Barcode')
            ->setCellValue('D1', 'Location')
            ->setCellValue('E1', 'Price')
            ->setCellValue('F1', 'QTY Yard')
            ->setCellValue('G1', 'Color Name')
            ->setCellValue('H1', 'Product Name')
            ->setCellValue('I1', 'Product Code')
            ->setCellValue('J1', 'Incoming Number')
            ->setCellValue('K1', 'Incoming Date')
            ->setCellValue('L1', 'Transfer Number')
            ->setCellValue('M1', 'Transfer Date')
            ->setCellValue('N1', 'Order Number')
            ->setCellValue('O1', 'Order Date');

        // define kolom dan nomor
        $kolom = 2;
        $nomor = 1;
        // tambahkan data transaction ke dalam file excel
        foreach ($data as $r) {

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $r->stock_code)
                ->setCellValue('C' . $kolom, $r->barcode)
                ->setCellValue('D' . $kolom, $r->location)
                ->setCellValue('E' . $kolom, $r->price)
                ->setCellValue('F' . $kolom, $r->qty_yard)
                ->setCellValue('G' . $kolom, $r->color_name)
                ->setCellValue('H' . $kolom, $r->product_name)
                ->setCellValue('I' . $kolom, $r->product_code)
                ->setCellValue('J' . $kolom, $r->incoming_number)
                ->setCellValue('K' . $kolom, $r->incoming_date)
                ->setCellValue('L' . $kolom, $r->transfer_number)
                ->setCellValue('M' . $kolom, $r->transfer_date)
                ->setCellValue('N' . $kolom, $r->order_number)
                ->setCellValue('O' . $kolom, $r->order_date);
            $kolom++;
            $nomor++;
        }
        // download spreadsheet dalam bentuk excel .xlsx
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="STOCK_' . date('Y-m-d-H-i') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

  
}
