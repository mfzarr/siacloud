<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data {{ $table }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="newCompanyForm" method="POST" action="/masterdata/roles">
                    @csrf
                    <div class="form-group">
                        <label for="nama_role" class="col-form-label">Nama Role:</label>
                        <input type="text" class="form-control" id="nama_role" name="nama_role" required>
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




<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).ready(function() {
        // Handle form submission for creating new data
        $('#confirmDataButton').click(function() {
            // Get form data
            var form = $('#newCompanyForm');
            var formData = form.serialize(); // Serialize form data
            
            // Send AJAX request
            $.ajax({
                url: 'role', // Adjust the URL if necessary
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Handle success
                    $('#addmodal').modal('hide');
                    location.reload(); // Reload the page to see the new data
                },
                error: function(xhr) {
                    // Handle error
                    alert('Error: ' + xhr.responseText);
                }
            });
        });
    });
</script>

