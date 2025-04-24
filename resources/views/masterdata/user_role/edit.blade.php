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
                                <h5 class="m-b-10">Edit Data Role</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user_role.index') }}"><i
                                            class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="#!">Edit Role</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- Edit Role Form start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Role: {{ $role->name }}</h5>
                        </div>
                        <div class="card-body">
                            <form id="editRoleForm" action="{{ route('user_role.update', $role->id_role) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Role Name:</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $role->nama_role) }}" required>
                                </div>
                                <!-- Add other role fields here -->
                                <div class="text-right"> <!-- Align "Update Role" button to the right -->
                                    <button type="submit" class="btn btn-primary">Update Role</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Edit Role Form end -->

                <!-- DataTable for User Access Menu start -->
                <div class="col-sm-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>User Access Menu</h5>
                        </div>
                        <div class="card-body">
                            <div class="dt-responsive table-responsive">
                                <table id="userAccessMenuTable" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Urutan Menu</th>
                                            <th>Nama Menu</th>
                                            <!-- Add other relevant columns -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($menus as $menu)
                                            <tr>
                                                <td>{{ $menu->urutan_menu }}</td>
                                                <td>{{ $menu->nama_menu }}</td>
                                                <!-- Add other columns as needed -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-right mt-3"> <!-- Align "Kembali" button to the right -->
                                <a href="{{ route('user_role.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DataTable for User Access Menu end -->
            </div>
        </div>
    </section>
    <!-- [ Main Content ] end -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <script>
        $(document).ready(function() {
            $('#userAccessMenuTable').DataTable();
        });
    </script>
@endsection
