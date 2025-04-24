<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data {{ $table }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="newCompanyForm">

                    <input type="hidden" id="table-data" name="table" value="perusahaan">

                    <div class="form-group">
                        <label for="nama" class="col-form-label">Nama Perusahaan:</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat" class="col-form-label">Alamat:</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis" class="col-form-label">Jenis Perusahaan:</label>
                        <select class="form-control" id="jenis" name="jenis_perusahaan" required>
                            <option value="">Select Type</option>
                            <option value="jasa">Jasa</option>
                            <option value="dagang">Dagang</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmDataButton">Create Data</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <input type="hidden" id="delete-id" name="id">
                <input type="hidden" id="delete-table" name="table">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle the click event for the update button in the edit modal
    $('#updateDataButton').on('click', function() {
        // Serialize form data and send AJAX request to update
        $.post('/masterdata/update', $('#editCompanyForm').serialize())
            .done(function(response) {
                console.log('Success:', response);
                $('#editModal').modal('hide');
                location.reload();
            })
            .fail(function(xhr) {
                console.log('Error:', xhr.responseText);
            });
    });

    // Handle the click event for the create button in the add modal
    $('#confirmDataButton').on('click', function() {
        $.post('/masterdata/store', $('#newCompanyForm').serialize())
            .done(function(response) {
                console.log('Success:', response);
                $('#exampleModal').modal('hide');
                location.reload();
            })
            .fail(function(xhr) {
                console.log('Error:', xhr.responseText);
            });
    });
});



</script>

