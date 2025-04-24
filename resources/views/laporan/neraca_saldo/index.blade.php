@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Neraca Saldo</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Laporan</a></li>
                            <li class="breadcrumb-item"><a href="#!">Neraca Saldo</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Neraca Saldo</h5>
                    </div>
                    <div class="card-body">
                        <form id="filter-form" method="GET" action="{{ route('neraca-saldo') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="month">Bulan</label>
                                        <select name="month" id="month" class="form-control" onchange="document.getElementById('filter-form').submit();">
                                            @foreach($months as $key => $value)
                                                <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="year">Tahun</label>
                                        <input type="number" name="year" id="year" class="form-control" value="{{ $selectedYear }}" onchange="document.getElementById('filter-form').submit();">
                                    </div>
                                </div>
                            </div>
                        </form>

<div class="text-right">
    <form method="POST" action="{{ route('create-coa-from-neraca-saldo') }}" id="create-coa-form">
        @csrf
        <input type="hidden" name="month" value="{{ $selectedMonth }}">
        <input type="hidden" name="year" value="{{ $selectedYear }}">
        <button type="submit" class="btn btn-success">
            {{ $nextMonthBalanceExists ? 'Update Saldo Awal' : 'Buat Saldo Awal' }}
        </button>
    </form>
</div>                 
                        <div class="table-responsive">
                            <table id="simpletable" class="table table-striped table-bordered nowrap">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
document.getElementById('create-coa-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var form = this;
    var button = form.querySelector('button[type="submit"]');
    
    Swal.fire({
        title: '{{ $nextMonthBalanceExists ? "Update" : "Buat" }} Saldo Awal?',
        text: "Saldo awal bulan selanjutnya akan {{ $nextMonthBalanceExists ? "diperbarui" : "dibuat" }} otomatis berdasarkan saldo saat ini",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, {{ $nextMonthBalanceExists ? "update" : "buat" }}!'
    }).then((result) => {
        if (result.isConfirmed) {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
            form.submit();
        }
    });
});

    @if(session('success'))
        Swal.fire({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            draggable: true
        });
    @endif
</script>

@endsection
