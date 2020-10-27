<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ExportController extends Controller
{
    public function exportLoans($start_date, $end_date)
    {
        $loans = Loan::whereBetween('created_at', [$start_date, $end_date])->oldest()->get();

        $column_alphanumerics = range('A', 'N');

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        foreach ($column_alphanumerics as $column_alphanumeric) {
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
                ->setCellValue('N1', 'Status Disetujui')->getColumnDimension($column_alphanumeric)->setAutoSize(true);
        }

        $column = 2;
        $number = 1;
        foreach ($loans as $key => $loan) {
            $sheet->setCellValue('A' . $column, $number)
                ->setCellValue('B' . $column, $loan->users()->first()->name)
                ->setCellValue('C' . $column, indonesian_date_format($loan->start_date))
                ->setCellValue('D' . $column, indonesian_date_format($loan->due_date))
                ->setCellValue('E' . $column, $loan->paid_date === null ? '' : indonesian_date_format($loan->paid_date))
                ->setCellValue('F' . $column, "$loan->loan_interest%")
                ->setCellValue('G' . $column, 'Rp. ' . number_format($loan->total_loan, 0, ',', '.'))
                ->setCellValue('H' . $column, 'Rp. ' . number_format($loan->total_loan_with_interest, 0, ',', '.'))
                ->setCellValue('I' . $column, 'Rp. ' . number_format($loan->total_payment, 0, ',', '.'))
                ->setCellValue('J' . $column, 'Rp. ' . number_format($loan->total_payment_interest, 0, ',', '.'))
                ->setCellValue('K' . $column, 'Rp. ' . number_format($loan->total_payment_with_interest, 0, ',', '.'))
                ->setCellValue('L' . $column, $loan->payment_counts)
                ->setCellValue('M' . $column, get_loan_status($loan))
                ->setCellValue('N' . $column, get_loan_approve_status($loan))->getColumnDimension($column_alphanumeric)->setAutoSize(true);
            $column++;
            $number++;
        }

        $file_name = '[PEMINJAMAN-KSP]' . date('d-m-Y');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');

        $writer = new Xls($spreadsheet);
        $writer->save('php://output');
    }
}
