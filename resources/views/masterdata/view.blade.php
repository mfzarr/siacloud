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
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addmodal">Add
                                    Data</button>
                            </div>
                            <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                @if ($items->isNotEmpty())
                                                    @foreach ($items->first() as $key => $value)
                                                        <th>{{ ucfirst($key) }}</th>
                                                    @endforeach
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                                <tr>
                                                    @php
                                                        $first = true;
                                                    @endphp
                                                    @foreach ($item as $value)
                                                        @if ($first)
                                                            @php
                                                                $first = false;
                                                                $ids = $value;
                                                            @endphp
                                                            <td>{{ $value }}</td>
                                                        @else
                                                            <td>{{ $value }}</td>
                                                        @endif
                                                    @endforeach
                                                    <td>
                                                        <!-- Tombol Edit -->
                                                        <button type="button" class="btn btn-warning edit-button"
                                                            data-toggle="modal" data-target="#editmodal"
                                                            data-table="{{ $table }}" data-ids="{{ $ids }}">
                                                            Edit</button>
                                                    </td>
                                                    <td>
                                                        <!-- Tombol Delete -->
                                                        <button type="button" class="btn btn-danger delete-button"
                                                            data-toggle="modal" data-target="#deletemodal"
                                                            data-table="{{ $table }}" data-ids="{{ $ids }}">
                                                            Delete</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        {{-- <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Office</th>
                                        <th>Age</th>
                                        <th>Start date</th>
                                        <th>Salary</th>
                                    </tr>
                                </tfoot> --}}
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

    @include('masterdata/' . $table . '-modal')

    @include('masterdata/' . $table . '-edit')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
