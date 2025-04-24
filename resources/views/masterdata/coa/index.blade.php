@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- Breadcrumb Section -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Data COA</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('coa.index') }}">COA</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Breadcrumb Section -->

            <!-- COA Data Display Section -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Data COA</h5>
                            <div class="float-right">
                                <a href="{{ route('coa.create') }}" class="btn btn-success btn-sm btn-round has-ripple"><i
                                        class="feather icon-plus"></i>Tambah COA</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('coa.index') }}" class="mb-4" id="filter-form">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="month" name="month" class="form-control"
                                            value="{{ $selectedMonth ?? \Carbon\Carbon::now()->format('Y-m') }}"
                                            onchange="document.getElementById('filter-form').submit();">
                                    </div>
                                </div>
                            </form>
                            <div class="dt-responsive table-responsive">
                                <table id="simpletable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode Akun</th>
                                            <th>Nama Akun</th>
                                            <th>Nama Kelompok Akun</th>
                                            <th>Posisi</th>
                                            <th>Saldo Awal</th>
                                            <th>Bulan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coas as $coa)
                                            <tr>
                                                <td>{{ $coa->kode_akun }}</td>
                                                <td>{{ $coa->nama_akun }}</td>
                                                <td>{{ $coa->kelompokakun->nama_kelompok_akun }}</td>
                                                <td>{{ $coa->posisi_d_c }}</td>
                                                <td>Rp{{ number_format($coa->saldo_awal) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($coa->tanggal_saldo_awal)->format('F Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('coa.edit', $coa->id_coa) }}"
                                                        class="btn btn-info btn-sm"><i
                                                            class="feather icon-edit"></i>&nbsp;Edit</a>
                                                    @if ($coa->status !== 'seeder' && $coa->status !== 'neraca')
                                                        @if (!$coa->jurnalUmums()->exists())
                                                            <form id="delete-form-{{ $coa->id_coa }}"
                                                                action="{{ route('coa.destroy', $coa->id_coa) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="feather icon-trash-2"></i>&nbsp;Delete
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-danger" disabled><i
                                                                    class="feather icon-trash-2"></i>&nbsp;Delete</button>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of COA Data Display Section -->
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get the month from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const selectedMonth = urlParams.get('month');
            
            // If month parameter exists, set it in the filter
            if (selectedMonth) {
                document.querySelector('input[name="month"]').value = selectedMonth;
            }
            
            // Delete confirmation
            document.querySelectorAll('form[id^="delete-form-"]').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus data ini?',
                        text: "Tindakan ini tidak bisa diubah!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus data ini!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection