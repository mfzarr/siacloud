@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5>Rekap Hutang</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item active">Rekap Hutang</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>List Hutang</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('rekap_hutang') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="month">Filter Tanggal Transaksi:</label>
                                <input type="month" name="month" class="form-control"
                                    value="{{ request('month', now()->format('Y-m')) }}" onchange="this.form.submit()">
                            </div>
                        </div>
                    </form>


                    <div class="table-responsive">
                        <table id="simpletable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No Transaksi</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Supplier</th>
                                    <th>Total Hutang</th>
                                    <th>Total Dibayar</th>
                                    <th>Sisa Hutang</th>
                                    <th>Status</th>
                                    <th>Tenggat Pelunasan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelian as $item)
                                    <tr>
                                        <td>{{ $item->no_transaksi_pembelian }}</td>
                                        <td>{{ $item->tanggal_pembelian }}</td>
                                        <td>{{ $item->supplierRelation->nama ?? 'N/A' }}</td>
                                        <td>Rp{{ number_format($item->rekap->total_hutang) }}</td>
                                        <td>Rp{{ number_format($item->rekap->total_dibayar) }}</td>
                                        <td>Rp{{ number_format($item->rekap->sisa_hutang) }}</td>
                                        <td>
                                            @if ($item->rekap)
                                                @if ($item->rekap->sisa_hutang <= 0)
                                                    <span class="badge badge-success">Lunas</span>
                                                @else
                                                    <span class="badge badge-warning">Belum Lunas</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary">Tidak Ada Data</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->rekap)
                                                {{ $item->rekap->tenggat_pelunasan->format('Y-m-d') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pembelian.detail', $item->id_pembelian) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="feather icon-eye"></i> Detail
                                            </a>                                            {{-- @if ($item->rekap && $item->rekap->sisa_hutang > 0)
                                        <!-- Update the modal trigger to send the row's ID and tenggat -->
                                        <button type="button" class="btn btn-warning btn-sm edit-tenggat" 
                                                data-toggle="modal" 
                                                data-target="#editTenggatModal" 
                                                data-id="{{ $item->id_pembelian }}"
                                                data-tenggat="{{ $item->rekap->tenggat_pelunasan->format('Y-m-d') }}">
                                            Edit Tenggat
                                        </button>
                                    @endif --}}
                                            @if ($item->status === 'Belum Lunas')
                                                <button type="button" class="btn btn-sm btn-warning pelunasan-btn"
                                                    data-id="{{ $item->id_pembelian }}">Pelunasan</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No hutang found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Modal for Editing Tenggat -->
    <div class="modal fade" id="editTenggatModal" tabindex="-1" role="dialog" aria-labelledby="editTenggatLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTenggatLabel">Edit Tenggat Pelunasan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editTenggatForm" action="{{ route('rekap_hutang.update_bulk') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label for="edit_tenggat_pelunasan">Tenggat Pelunasan Baru</label>
                            <input type="date" name="tenggat_pelunasan" id="edit_tenggat_pelunasan" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <!-- Pelunasan Modal -->
    <div class="modal fade" id="pelunasanModal" tabindex="-1" role="dialog" aria-labelledby="pelunasanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pelunasanModalLabel">Pelunasan Pembelian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="pelunasanForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tanggal_pelunasan">Tanggal Pelunasan</label>
                            <input type="date" class="form-control" id="tanggal_pelunasan" name="tanggal_pelunasan"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // $(document).ready(function() {
        //     $('.edit-tenggat').click(function() {
        //         var id = $(this).data('id');
        //         var tenggat = $(this).data('tenggat');
        //         $('#edit_id').val(id);
        //         $('#edit_tenggat_pelunasan').val(tenggat);
        //     });
        // });

        $(document).ready(function() {
            $('.pelunasan-btn').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#pelunasanForm').attr('action', '/pembelian/' + id + '/pelunasan-auto');
                $('#tanggal_pelunasan').val(new Date().toISOString().split('T')[
                0]); // Set default value to today
                $('#pelunasanModal').modal('show');
            });

            $('#pelunasanForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert('Pelunasan berhasil');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
        });

    </script>

@endsection
