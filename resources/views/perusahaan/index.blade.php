@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">List of Your Perusahaan</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard')}}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Perusahaan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Your Perusahaan</h5>
                    </div>
                    <div class="card-body">
                        @if($perusahaan)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama Perusahaan</th>
                                            <th>Alamat</th>
                                            <th>Jenis Perusahaan</th>
                                            <th>Kode Perusahaan</th>
                                            <th>Owner (Username)</th>
                                            <th>Owner (Email)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $perusahaan->nama }}</td>
                                            <td>{{ $perusahaan->alamat }}</td>
                                            <td>{{ $perusahaan->jenis_perusahaan }}</td>
                                            <td>{{ $perusahaan->kode_perusahaan }}</td>
                                            <td>{{ $perusahaan->owner->username }}</td>
                                            <td>{{ $perusahaan->owner->email }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>No company is assigned to your account.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
@endsection
