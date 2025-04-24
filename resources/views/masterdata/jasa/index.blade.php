@extends('layouts.frontend')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">List of Jasa</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Jasa</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Jasa List</h5>
                        <a href="{{ route('jasa.create') }}" class="btn btn-primary">Add Jasa</a>
                    </div>
                    <div class="card-body">
                        @if($jasas->isEmpty())
                            <p>No jasa found for your perusahaan.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Detail</th>
                                            <th>Harga</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jasas as $jasa)
                                            <tr>
                                                <td>{{ $jasa->nama }}</td>
                                                <td>{{ $jasa->detail }}</td>
                                                <td>{{ number_format($jasa->harga, 0, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('jasa.edit', $jasa->id_jasa) }}" class="btn btn-warning">Edit</a>
                                                    <form action="{{ route('jasa.destroy', $jasa->id_jasa) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
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
@endsection
