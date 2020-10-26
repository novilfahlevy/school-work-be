<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ExportController extends Controller
{
    public function exportLoans(Request $request)
    {

        $loans = Loan::all();

        $column_alphanumerics = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'
        ];

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        for ($i = 0; $i < count($column_alphanumerics); $i++) {
            $sheet->setCellValue('A1', 'NO')
                ->setCellValue('B1', 'Pengguna')
                ->setCellValue('C1', 'Tanggal Mulai')
                ->setCellValue('D1', 'Tanggal Selesai')
                ->setCellValue('E1', 'Tanggal Dibayar')
                ->setCellValue('F1', 'Bunga Peminjaman')
                ->setCellValue('G1', 'Total Peminjaman')
                ->setCellValue('H1', 'Total Peminjaman Dengan Bunga')
                ->setCellValue('I1', 'Total Angsuran')
                ->setCellValue('J1', 'Bunga Angsuran')
                ->setcellvalue('K1', 'Total Angsuran Dengan Bunga')
                ->setCellValue('L1', 'Angsuran Ke-')
                ->setCellValue('M1', 'Status')
                ->setCellValue('N1', 'Disetujui')->getColumnDimension($column_alphanumerics[$i])->setAutoSize(true);
        }

        $column = 2;
        $number = 1;
        foreach ($loans as $key => $loan) {
            $sheet->setCellValue('A' . $column, $number)
                ->setCellValue('B' . $column, $loan->users()->first()->name)
                ->setCellValue('C' . $column, $loan->start_date)
                ->setCellValue('D' . $column, $loan->due_date)
                ->setCellValue('E' . $column, $loan->paid_date)
                ->setCellValue('F' . $column, $loan->loan_interest)
                ->setCellValue('G' . $column, $loan->total_loan)
                ->setCellValue('H' . $column, $loan->total_loan_with_interest)
                ->setCellValue('I' . $column, $loan->total_payment)
                ->setCellValue('J' . $column, $loan->total_payment_interest)
                ->setCellValue('K' . $column, $loan->total_payment_with_interest)
                ->setCellValue('L' . $column, $loan->payment_counts)
                ->setCellValue('M' . $column, get_loan_status($loan))
                ->setCellValue('N' . $column, get_loan_approve_status($loan))->getColumnDimension($column_alphanumerics[$key])->setAutoSize(true);
            $column++;
            $number++;
        }

        $file_name = '[PEMINJAMAN-KSP]' . date('d-m-Y');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');

        $writer = new Xls($spreadsheet);
        $writer->save('php://output');
    }
}
