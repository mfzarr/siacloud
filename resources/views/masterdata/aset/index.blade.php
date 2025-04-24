@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Asset</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('aset.index') }}">Aset</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Asset List</h5>
                            <div class="float-right">
                                <a href="{{ route('aset.create') }}" class="btn btn-success btn-sm btn-round has-ripple"><i
                                        class="feather icon-plus"></i>Add Aset</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($assets->isEmpty())
                                <p>No assets found for your perusahaan.</p>
                            @else
                                <div class="table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama Aset</th>
                                                <th>Harga Perolehan</th>
                                                <th>Nilai Sisa</th>
                                                <th>Masa Manfaat (Tahun)</th>
                                                <th>Bulan Perolehan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($assets as $asset)
                                                <tr>
                                                    <td>{{ $asset->nama_asset }}</td>
                                                    <td>Rp{{ number_format($asset->harga_perolehan, 0, ',', '.') }}
                                                    </td>
                                                    <td>Rp{{ number_format($asset->nilai_sisa, 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ $asset->masa_manfaat }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('F Y') }}</td>
                                                    <td>
                                                        <!-- Kirimkan ID aset sebagai parameter -->
                                                        <a href="{{ route('aset.edit', $asset->id_assets) }}"
                                                            class="btn btn-info btn-sm">
                                                            <i class="feather icon-edit"></i>&nbsp;Edit
                                                        </a>
                                                        <a href="{{ route('aset.depreciation', $asset->id_assets) }}"
                                                            class="btn btn-success btn-sm">
                                                            <i class="feather icon-clipboard"></i>
                                                            Depreciation
                                                        </a>
                                                        <form id="delete-form-{{ $asset->id_assets }}"
                                                            action="{{ route('aset.destroy', $asset->id_assets) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="feather icon-trash-2"></i>&nbsp;Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
