@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">List of Diskon</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('diskon.index') }}">Diskon</a></li>
                            </ul>    
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Diskon List</h5>
                            <div class="float-right">
                                <a href="{{ route('diskon.create') }}"
                                    class="btn btn-success btn-sm btn-round has-ripple"><i class="feather icon-plus"></i>Add
                                    Diskon</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($discounts->isEmpty())
                                <p>No Diskon found for your perusahaan.</p>
                            @else
                                <div class="table-responsive">
                                    <table id="simpletable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                {{-- <th>no</th> --}}
                                                <th>Minimum Transaksi</th>
                                                <th>Persentasi Diskon</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- <?php
                                            $no = 1;
                                            ?> --}}
                                            @foreach ($discounts as $diskon)
                                                <tr>
                                                    {{-- <td>{{$no++}}</td> --}}
                                                    <td>{{ $diskon->min_transaksi }}</td>
                                                    <td>{{ $diskon->discount_percentage }}%</td>
                                                    <td>
                                                        <a href="{{ route('diskon.edit', $diskon->id_discount) }}"
                                                            class="btn btn-info btn-sm"><i
                                                                class="feather icon-edit"></i>&nbsp;Edit</a>
                                                        <form id="delete-form-{{ $diskon->id_discount }}"
                                                            action="{{ route('diskon.destroy', $diskon->id_discount) }}"
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
