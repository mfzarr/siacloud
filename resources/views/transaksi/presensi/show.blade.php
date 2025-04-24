@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Detail Kehadiran {{ $date }}</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Presensi</a></li>
                                <li class="breadcrumb-item active"><a>Detail Kehadiran</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h1>Detail Kehadiran {{ $date }}</h1>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendance as $record)
                                <tr>
                                    <td>{{ $record->karyawan->nama }}</td>
                                    <td>{{ ucfirst($record->status) }}</td>
                                    <td>{{ $record->jam_masuk ?? '--' }}</td>
                                    <td>{{ $record->jam_keluar ?? '--' }}</td>
                                    <td>
                                        @if (in_array($record->status, ['izin', 'sakit', 'alpha']))
                                            --
                                        @else
                                            @if (!$record->jam_keluar)
                                                <form id="jamkeluarForm"
                                                    action="{{ route('presensi.createExitTime', ['date' => $date, 'id' => $record->id_presensi]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button id="saveButton" type="button" class="btn btn-sm btn-primary">
                                                        <i class="feather icon-clock"></i> Jam Keluar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-success">Jam keluar telah direkam</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        <a href="{{ route('presensi.index') }}" class="btn btn-secondary mt-3">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("saveButton").addEventListener("click", function() {
            Swal.fire({
                title: "Create Jam Keluar success!",
                icon: "success",
                draggable: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("jamkeluarForm").submit();
                }
            });
        });
    </script>
@endsection
