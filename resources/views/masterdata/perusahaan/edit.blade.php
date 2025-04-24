<!-- Edit Modal -->
<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Perusahaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="editPerusahaanForm">
                    @method('PUT')
                    @csrf
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
                            <option value="" selected hidden>Select Type</option>
                            <option value="Jasa">Jasa</option>
                            <option value="Dagang">Dagang</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateButton">Update Data</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle edit button click
        $('.edit-button').click(function() {
            var id = $(this).data('id');
            var table = $(this).data('table');

            // Fetch current data and populate the form
            $.ajax({
                url: '/masterdata/perusahaan/' + id + '/edit',
                method: 'GET',
                success: function(data) {
                    $('#edit-id').val(data.id);
                    $('#edit-nama').val(data.nama);
                    $('#edit-alamat').val(data.alamat);
                    $('#edit-jenis').val(data.jenis_perusahaan);
                    $('#editPerusahaanForm').attr('action', '/masterdata/perusahaan/' + id); // Update form action URL
                    $('#editmodal').modal('show');
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });

        // Handle form submission for updating data
        $('#confirmUpdateButton').click(function() {
            // Get form data
            var form = $('#editPerusahaanForm');
            var formData = form.serialize(); // Serialize form data
            
            // Send AJAX request for updating
            $.ajax({
                url: form.attr('action'), // Use the action attribute from the form
                method: 'PUT',
                data: formData + '&_token={{ csrf_token() }}', // Include CSRF token
                success: function(response) {
                    // Handle success
                    $('#editmodal').modal('hide');
                    location.reload(); // Reload the page to see the updated data
                },
                error: function(xhr) {
                    // Handle error
                    alert('Error: ' + xhr.responseText);
                }
            });
        });
    });
</script>
