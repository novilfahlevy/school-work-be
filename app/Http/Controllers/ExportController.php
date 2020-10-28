<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Loan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ExportController extends Controller
{
    public function export($start_date, $end_date, $type)
    {
        if ($type === "1") {
            $this->exportLoans($start_date, $end_date);
        } else {
            $this->exportDeposits($start_date, $end_date);
        }
    }

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
                ->setCellValue('G' . $column, indonesian_currency($loan->total_loan))
                ->setCellValue('H' . $column, indonesian_currency($loan->total_loan_with_interest))
                ->setCellValue('I' . $column, indonesian_currency($loan->total_payment))
                ->setCellValue('J' . $column, indonesian_currency($loan->total_payment_interest))
                ->setCellValue('K' . $column, indonesian_currency($loan->total_payment_with_interest))
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

    public function exportDeposits($start_date, $end_date)
    {

        $deposits = Deposit::whereBetween('created_at', [$start_date, $end_date])->oldest()->get();

        $column_alphanumerics = range('A', 'E');

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->setActiveSheetIndex(0);

        foreach ($column_alphanumerics as $column_alphanumeric) {
            $sheet->setCellValue('A1', 'NO')
                ->setCellValue('B1', 'Pengguna')
                ->setCellValue('C1', 'Total Setoran')
                ->setCellValue('D1', 'Tanggal Setoran')
                ->setCellValue('E1', 'Tipe Peminjaman')
                ->setCellValue('F1', 'Status')
                ->getColumnDimension($column_alphanumeric)->setAutoSize(true);
        }

        $column = 2;
        $number = 1;
        foreach ($deposits as $key => $deposit) {
            $sheet->setCellValue('A' . $column, $number)
                ->setCellValue('B' . $column, $deposit->users->name)
                ->setCellValue('C' . $column, $deposit->total_deposit)
                ->setCellValue('D' . $column, indonesian_date_format($deposit->deposit_date))
                ->setCellValue('E' . $column, get_main_savings_name($deposit))
                ->setCellValue('F' . $column, get_deposit_status($deposit))
                ->getColumnDimension($column_alphanumeric)->setAutoSize(true);
            $column++;
            $number++;
        }

        $file_name = '[SETORAN-KSP]' . date('d-m-Y');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');

        $writer = new Xls($spreadsheet);
        $writer->save('php://output');
    }
}
