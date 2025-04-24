@extends('layouts.frontend')

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h5 class="m-b-10">Add Diskon</h5>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('diskon.index') }}">Diskon</a></li>
                                <li class="breadcrumb-item"><a>Tambah Diskon</a></li>
                            </ul>    
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create Diskon</h5>
                        </div>
                        <div class="card-body">
                            <form id="diskonForm" action="{{ route('diskon.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="min_transaksi">Minimum Transaksi <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('min_transaksi') is-invalid @enderror" id="min_transaksi" name="min_transaksi" value="{{ old('min_transaksi') }}" required>
                                    @error('min_transaksi')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="discount_percentage">Persentase Diskon <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('discount_percentage')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                                    <a href="{{ route('diskon.index') }}" class="btn btn-danger">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("saveButton").addEventListener("click", function() {
            const form = document.getElementById("diskonForm");

            // Perform client-side validation
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    title: "Validation Error",
                    text: "Please fill in all required fields.",
                    icon: "error"
                });
                return;
            }

            // If client-side validation passes, show SweetAlert
            Swal.fire({
                title: "Create Diskon?",
                text: "Are you sure you want to create this Diskon?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form
                    form.submit();
                }
            });
        });

        // Check for success message in session and show SweetAlert
        @if (session('success'))
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                draggable: true
            });
        @endif
    </script>
@endsection
