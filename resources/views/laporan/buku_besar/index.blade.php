@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Buku Besar</h5>
                </div>

                <div class="card-body">
                    <!-- View 1: Buku Besar per Akun -->
                    <div id="view1" class="view-section" style="display: block;">
                        <!-- Form to Select Account -->
                        <!-- Form to Select Account -->
                        <form id="bukuBesarForm" method="GET" action="{{ route('buku-besar') }}" class="mb-4">
                            <div class="form-row">
                                <div class="col-md-3 mb-3">
                                    <label for="month">Bulan</label>
                                    <select name="month" id="month" class="form-control" onchange="filterAccounts()">
                                        <option value="" disabled selected>Pilih Bulan</option>
                                        @foreach ($months as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ $selectedMonth == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="year">Tahun</label>
                                    <input type="number" name="year" id="year" class="form-control"
                                        value="{{ $selectedYear }}" onchange="filterAccounts()">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="account">Akun</label>
                                    <select name="account" id="account" class="form-control">
                                        <option value="">Pilih Akun</option>
                                        @php
                                            $sortedCoas = $coas->sortBy('kode_akun')->groupBy(function ($item) {
                                                return \Carbon\Carbon::parse($item->tanggal_saldo_awal)->format('Y-m');
                                            });
                                        @endphp
                                        @foreach ($sortedCoas as $yearMonth => $akuns)
                                            @php
                                                $date = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth);
                                                $optgroupLabel = $date->translatedFormat('F Y');
                                            @endphp
                                            <optgroup label="{{ $optgroupLabel }}" data-year="{{ $date->year }}"
                                                data-month="{{ $date->month }}">
                                                @foreach ($akuns as $akun)
                                                    <option value="{{ $akun->id_coa }}" data-year="{{ $date->year }}"
                                                        data-month="{{ $date->month }}">
                                                        {{ $akun->nama_akun }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                            </div>
                        </form>

                        <div class="text-center mb-4">
                            <!-- Display Transactions -->
                            @if ($selectedAccount)
                                @php
                                    // Find the selected Coa by ID
                                    $selectedCoa = $coas->firstWhere('id_coa', $selectedAccount);
                                @endphp

                                <h4>Buku Besar</h4>
                                @if ($selectedCoa)
                                    <h6>Akun {{ $selectedCoa->kode_akun }} - {{ $selectedCoa->nama_akun }}</h6>
                                @else
                                    <h6>Akun tidak ditemukan</h6>
                                @endif
                                <h6>{{ \Carbon\Carbon::create($selectedYear, $selectedMonth)->translatedFormat('F Y') }}
                                </h6>
                        </div>

                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- New row to display saldo_awal -->
                                    <tr>
                                        <td colspan="2"><strong>Saldo Awal</strong></td>
                                        <td colspan="2">Rp{{ number_format($saldoAwal) }}</td>
                                    </tr>

                                    <!-- Transactions -->
                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        $runningBalance = $saldoAwal; // Start with saldo_awal
                                    @endphp
                                    @foreach ($transactions as $transaction)
                                        @php
                                            // Update the running balance
                                            if ($transaction->debit) {
                                                $runningBalance += $transaction->debit;
                                            }
                                            if ($transaction->credit) {
                                                $runningBalance -= $transaction->credit;
                                            }
                                            $totalDebit += $transaction->debit ?? 0;
                                            $totalCredit += $transaction->credit ?? 0;
                                        @endphp
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($transaction->tanggal_jurnal)->format('d M') }}
                                            </td>
                                            <td>{{ $transaction->nama_akun }}</td>
                                            <td>Rp{{ $transaction->debit ? number_format($transaction->debit) : '0' }}</td>
                                            <td>Rp{{ $transaction->credit ? number_format($transaction->credit) : '0' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td><strong>Rp{{ number_format($totalDebit) }}</strong></td>
                                        <td><strong>Rp{{ number_format($totalCredit) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Saldo Akhir</strong></td>
                                        <td colspan="2"><strong>Rp{{ number_format($runningBalance) }}</strong></td>
                                        <!-- Display running balance -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Silakan pilih akun untuk melihat Buku Besar.</p>
                        @endif
                    </div>

                    <!-- View 2: Total Saldo Semua Akun -->
                    <div id="view2" class="view-section" style="display: none;">
                        <h6>Total Saldo Semua Akun</h6>

                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Akun</th>
                                        <th>Total Debit</th>
                                        <th>Total Kredit</th>
                                        <th>Saldo Awal</th> <!-- Added Saldo Awal column -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                    @endphp
                                    @foreach ($totalBalances as $coa)
                                        @php
                                            // Use the total debit and credit values directly
                                            $coaDebit = $coa->total_debit;
                                            $coaCredit = $coa->total_credit;
                                            $totalDebit += $coaDebit;
                                            $totalCredit += $coaCredit;
                                        @endphp
                                        <tr>
                                            <td>{{ $coa->kode_akun }} - {{ $coa->nama_akun }}</td>
                                            <td>Rp{{ number_format($coaDebit) }}</td>
                                            <td>Rp{{ number_format($coaCredit) }}</td>
                                            <td>Rp{{ number_format($coa->saldo_awal) }}</td>
                                            <!-- Display Saldo Awal here -->
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="1"><strong>Total Keseluruhan</strong></td>
                                        <td><strong>Rp{{ number_format($totalDebit) }}</strong></td>
                                        <td><strong>Rp{{ number_format($totalCredit) }}</strong></td>
                                        <td><strong>Rp{{ number_format($totalDebit - $totalCredit) }}</strong></td>
                                        <!-- Total Saldo Awal here -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- View 3: Semua Transaksi -->
                    <div id="view3" class="view-section" style="display: none;">
                        <h6>Semua Transaksi</h6>

                        <div class="table-responsive mt-4">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Akun</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allTransactions as $transaction)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($transaction->tanggal_jurnal)->format('d M') }}
                                            </td>
                                            <td>{{ $transaction->coa->nama_akun }}</td>
                                            <td>Rp{{ number_format($transaction->debit) }}</td>
                                            <td>Rp{{ number_format($transaction->credit) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><strong>Total Keseluruhan</strong></td>
                                        <td><strong>Rp{{ number_format($grandTotalDebit) }}</strong></td>
                                        <td><strong>Rp{{ number_format($grandTotalCredit) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterAccounts() {
            const selectedMonth = document.getElementById('month').value;
            const selectedYear = document.getElementById('year').value;
            const accountSelect = document.getElementById('account');
            const optgroups = accountSelect.querySelectorAll('optgroup');
        
            optgroups.forEach(optgroup => {
                const optgroupYear = optgroup.getAttribute('data-year');
                const optgroupMonth = optgroup.getAttribute('data-month');
                const options = optgroup.querySelectorAll('option');
        
                if (optgroupYear === selectedYear && optgroupMonth === selectedMonth) {
                    optgroup.style.display = 'block';
                    options.forEach(option => option.style.display = 'block');
                } else {
                    optgroup.style.display = 'none';
                    options.forEach(option => option.style.display = 'none');
                }
            });
        
            // Reset selected option if it's not visible
            if (accountSelect.selectedOptions[0].style.display === 'none') {
                accountSelect.value = '';
            }
        }
        
        // Initial filter on page load
        document.addEventListener('DOMContentLoaded', filterAccounts);
        </script>
@endsection
