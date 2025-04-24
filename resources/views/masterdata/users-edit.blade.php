<!-- Edit Modal -->
<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Company</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="editCompanyForm">
                    <div class="form-group" hidden>
                        <label for="edit-table" class="col-form-label">Table</label>
                        <input type="text" class="form-control" id="edit-table" name="table" value="table" required>
                    </div>
                    <div class="form-group" hidden>
                        <label for="edit-ids" class="col-form-label">Id Perusahaan</label>
                        <input type="text" class="form-control" id="edit-ids" name="id_perusahaan" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-nama" class="col-form-label">Nama Perusahaan:</label>
                        <input type="text" class="form-control" id="edit-nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-alamat" class="col-form-label">Alamat:</label>
                        <input type="text" class="form-control" id="edit-alamat" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-jenis" class="col-form-label">Jenis Perusahaan:</label>
                        <select class="form-control" id="edit-jenis" name="jenis_perusahaan" required>
                            <option value="">Select Type</option>
                            <option value="jasa">Jasa</option>
                            <option value="dagang">Dagang</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateDataButton">Update Data</button>
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

    // Handle the click event for the edit button
    $('.edit-button').on('click', function() {
        var ids = $(this).data('ids'); // Get the ID from the button's data attribute
        var table = $(this).data('table'); // Get the table name from the button's data attribute

        // Fetch the existing data for the specified ID
        $.get('/masterdata/perusahaan/show', { ids: ids, table: table })
            .done(function(response) {
                // Populate the edit form with existing data
                $('#edit-ids').val(response.id_perusahaan);
                $('#edit-nama').val(response.nama);
                $('#edit-alamat').val(response.alamat);
                $('#edit-jenis').val(response.jenis_perusahaan);
                $('#edit-table').val(table);
                
                // Show the edit modal
                $('#editModal' + ids).modal('show');
            })
            .fail(function(xhr) {
                console.log('Error:', xhr.responseText);
            });
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