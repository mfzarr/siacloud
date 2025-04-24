@extends('layout')

@section('content')

    <!-- [ Main Content ] start -->
    <section class="pcoded-main-container">
        <div class="pcoded-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Data from Table: {{ ucfirst($table) }}</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#!">Data Table</a></li>
                                <li class="breadcrumb-item"><a href="#!">Basic Initialization</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- Multi-column table start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Data {{ ucfirst($table) }}</h5>
                            <div class="card-header-right">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addmodal">Tambah Data</button>
                            </div>
                            <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Role</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td>{{ $item->id_role }}</td>
                                                    <td>{{ $item->nama_role }}</td>
                                                    <td>
                                                        <!-- Tombol Edit -->
                                                        <a href="{{ route('user_role.edit', $item->id_role) }}" class="btn btn-warning">
                                                            Edit
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <!-- Tombol Delete -->
                                                        <button type="button" class="btn btn-danger delete-button"
                                                            data-toggle="modal" data-target="#deletemodal"
                                                            data-table="{{ $table }}" data-id="{{ $item->id_role }}">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Multi-column table end -->
                </div>
                <!-- [ Main Content ] end -->
            </div>
    </section>
    <!-- [ Main Content ] end -->

    @include('masterdata/' . $table . '/modal')

    {{-- @include('masterdata/' . $table . '/edit') --}}

    @include('masterdata/' . $table . '/delete')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
