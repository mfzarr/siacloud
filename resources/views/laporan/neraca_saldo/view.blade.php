@extends('layouts.frontend')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neraca Saldo - {{ $selectedMonth }} {{ $year }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Neraca Saldo - {{ $selectedMonth }} {{ $year }}</h1>
    <table>
        <thead>
            <tr>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo Debit</th>
                <th>Saldo Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($totalBalances as $balance)
            <tr>
                <td>{{ $balance->kode_akun }}</td>
                <td>{{ $balance->nama_akun }}</td>
                <td>Rp{{ number_format($balance->total_debit) }}</td>
                <td>Rp{{ number_format($balance->total_credit) }}</td>
                <td>Rp{{ number_format($balance->saldo_debit) }}</td>
                <td>Rp{{ number_format($balance->saldo_kredit) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th>Rp{{ number_format($grandTotalDebit) }}</th>
                <th>Rp{{ number_format($grandTotalCredit) }}</th>
                <th>Rp{{ number_format($grandTotalSaldoDebit) }}</th>
                <th>Rp{{ number_format($grandTotalSaldoKredit) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>

@endsection