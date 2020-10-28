<!DOCTYPE html>
<html lang="en">

<head>
    <title>Print Cetak Peminjaman</title>
    <style>
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p {
            margin: 0;
            font-weight: normal;
        }

        p {
            font-size: 14px;
        }

        body {
            max-width: 768px;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        h5 {
            font-size: .9rem;
        }

        h4 {
            font-size: 1.2rem;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: .9rem;
        }
    </style>
</head>

<body>
    <table style="width: 150%;" border="1">
        <tr>
            <td colspan="8" style="text-align: center;">
                <h2>Koperasi Simpan Pinjam</h2>
                <h1>Koperasi Simpan Pinjam KPS</h1>
                <p>Jl. Cendrawasih - Samarinda - Kalimantan Timur</p>
                <p><b>Telp:</b> 0271-xxxx <b>Email:</b> kps@mail.com</p>
            </td>
        </tr>
        <tr style="border: none;">
            <td colspan="2" width="25%" style="border-right: 0; border-bottom: 0;">
                <p>Nama Peminjam</p>
                <p>Tanggal Peminjaman</p>
                <p>Tanggal Jatuh Tempo</p>
                <p>Jangka Lama Angsuran</p>
            </td>
            <td colspan="2" width="25%" style="border-left: 0; border-right: 0; border-bottom: 0;">
                <p>: <b>{{ $loan->users->name }}</b></p>
                <p>: <b>{{ indonesian_date_format($loan->start_date) }}</b></p>
                <p>: <b>{{ indonesian_date_format($loan->due_date) }}</b></p>
                <p>: <b>{{ $diff }}</b></p>
            </td>
            <td colspan="2" width="25%" style="border-left: 0; border-right: 0; border-bottom: 0;">
                <p>Jumlah Angsuran</p>
                <p>Total Pinjaman</p>
                <p>Bunga Pinjaman</p>
                <p>Pegawai Pencatat</p>
            </td>
            <td colspan="2" width="25%" style="border-left: 0; border-bottom: 0;">
                <p>: <b>{{ $loan->payment_counts }} Kali</b></p>
                <p>: <b>{{ indonesian_currency($loan->total_loan) }}</b></p>
                <p>: <b>{{ $loan->loan_interest }}%</b></p>
                <p>: <b>{{ $loan->employees->name }}</b></p>
            </td>
        </tr>
        <tr>
            <table style="width: 150%;">
                <tr>
                    <td colspan="1">Angsuran</td>
                    <td colspan="2">Tanggal</td>
                    <td colspan="2">Angsuran Pokok</td>
                    <td colspan="2">Bunga</td>
                    <td colspan="2">Total Angsuran</td>
                </tr>
                @foreach($loan->payments as $payment)
                <tr style="height: 40px;">
                    <td colspan="1">{{ $loop->iteration }}</td>
                    <td colspan="2">{{ indonesian_date_format($payment->due_date) }}</td>
                    <td colspan="2">{{ indonesian_currency($loan->total_payment) }}</td>
                    <td colspan="2">{{ indonesian_currency($loan->total_payment_interest) }}</td>
                    <td colspan="2">{{ indonesian_currency($loan->total_payment) }}</td>
                </tr>
                @endforeach
                <tr style="height: 40px;">
                    <td colspan="3" style="text-align:right">Total</td>
                    <td colspan="2"><b>{{ indonesian_currency($loan->total_payment * $loan->payments->count()) }}</b></td>
                    <td colspan="2"><b>{{ indonesian_currency($loan->total_payment_interest * $loan->payments->count()) }}</b></td>
                    <td colspan="2"><b>{{ indonesian_currency($loan->total_payment * $loan->payments->count() + $loan->total_payment_interest * $loan->payments->count()) }}</b></td>
                </tr>
            </table>
        </tr>
    </table>
</body>

</html>