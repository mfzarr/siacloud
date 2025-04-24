@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Jurnal Umum</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item active">Jurnal Umum</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>List Jurnal Umum</h5>
            </div>

            <div class="card-body" style="padding: 20px;">
                <!-- Search Form -->
                <form method="GET" action="{{ route('jurnal-umum.index') }}" class="mb-4">
                    <div class="row align-items-center">
                        <!-- Month Dropdown Form -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="month">Pilih Bulan:</label>
                                <select name="month" id="month" class="form-control">
                                    @foreach($months as $key => $month)
                                    <option value="{{ $key }}" {{ $key == $selectedMonth ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Year Dropdown Form -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="year">Pilih Tahun:</label>
                                <select name="year" id="year" class="form-control">
                                    @for($year = 2020; $year <= \Carbon\Carbon::now()->year; $year++)
                                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                        @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Filter by Nama Akun -->
                        <div class="col-md-4">
                            <select name="filter" class="form-control" onchange="this.form.submit()">
                                <option value="">Filter by Nama Akun</option>
                                @foreach ($filters as $filter)
                                <option value="{{ $filter->nama_akun }}" {{ request('filter') == $filter->nama_akun ? 'selected' : '' }}>
                                    {{ $filter->nama_akun }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="text-center mb-4">
                <h4>{{ $perusahaan->nama }}</h4>
                <h5>Laporan Keuangan {{ $months[$selectedMonth] }}</h5>
                <h5>Jurnal Umum</h5>
            </div>
            <!-- Ledger Table -->
            <div class="table-responsive" style="padding: 20px;">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Tanggal</th>
                            <th>Nama Akun</th>
                            <th>Nomor Akun</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groupedJurnals as $groupKey => $entries)
                        @php
                        $firstEntry = $entries->first();
                        $date = \Carbon\Carbon::parse($firstEntry->tanggal_jurnal)->format('d/m/Y');
                        @endphp

                        @foreach($entries as $index => $entry)
                        <tr>
                            @if($index === 0)
                            <td rowspan="{{ $entries->count() }}" class="text-center align-middle">
                                {{ $date }}
                            </td>
                            @endif
                            <td {!! $entry->credit > 0 ? 'class="pl-4"' : '' !!}>
                                {{ $entry->nama_akun }}
                            </td>
                            <td>{{ $entry->kode_akun }}</td>
                            <td class="text-right">Rp
                                {{ $entry->debit > 0 ? number_format($entry->debit) : '-' }}
                            </td>
                            <td class="text-right">Rp
                                {{ $entry->credit > 0 ? number_format($entry->credit) : '-' }}
                            </td>
                        </tr>
                        @endforeach

                        <!-- Add a separator row -->
                        <tr>
                            <td colspan="5" class="p-0">
                                <hr class="m-0">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum Ada Penjurnalan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td colspan="3" class="text-right">Total:</td>
                            <td class="text-right">Rp
                                {{ number_format($jurnals->sum('debit')) }}
                            </td>
                            <td class="text-right">Rp
                                {{ number_format($jurnals->sum('credit')) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3" style="padding: 20px;">
                <a href="{{ $jurnals->previousPageUrl() }}" class="btn btn-outline-primary btn-sm {{ $jurnals->onFirstPage() ? 'disabled' : '' }}">&laquo; Previous</a>
                <span>Page {{ $jurnals->currentPage() }} of {{ $jurnals->lastPage() }}</span>
                <a href="{{ $jurnals->nextPageUrl() }}" class="btn btn-outline-primary btn-sm {{ $jurnals->hasMorePages() ? '' : 'disabled' }}">Next &raquo;</a>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    td.indent {
        padding-left: 40px;
        /* Indentation for child rows */
    }
</style>

@endsection
