<!-- Edit Modal -->
<div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="editform">
                    @csrf
                    <div class="form-group">
                        <label for="id_barang">Barang :</label>
                        <select class="form-control" id="id_barang" name="id_barang" required>
                            <option value="" selected hidden>Select Barang</option>
                            @foreach ($barang as $option)
                                <option value="{{ $option->id_barang }}">{{ $option->nama }}</option> <!-- Ensure the correct field name here -->
                            @endforeach
                        </select>
                    </div>

                    {{-- Kuantitas --}}
                    <div class="form-group">
                        <label for="kuantitas">Kuantitas :</label>
                        <input type="integer" class="form-control" id="kuantitas" name="kuantitas" required>
                    </div>
                    
                    <input type="id_jasa" value="{{ $jasa->id_jasa }}" id="id_jasa" name="id_jasa" hidden>
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

            // Fetch current data and populate the form
            $.ajax({
                url: '/masterdata/jasa/editdetail/' + id,
                method: 'GET',
                success: function(data) {
                    // Populate the form fields (input)
                    $('input[name="kuantitas"]').val(data.jasa_detail.kuantitas);
                    $('input[name="id_jasa_detail"]').val(data.jasa_detail.id_jasa_detail);

                    // Populate the form fields (dropdown)
                    $('select[name="id_barang"]').val(data.jasa_detail.id_barang);

                    // Update form action URL
                    $('#editform').attr('action', '/masterdata/jasa/updatedetail/' + id);

                    // Show the modal
                    $('#editmodal').modal('show');
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });

        // Handle form submission for updating data
        $('#confirmUpdateButton').click(function() {
            var form = $('#editform');
            var formData = form.serialize(); // Serialize form data

            // Send AJAX request for updating
            $.ajax({
                url: form.attr('action'), // Use the action attribute from the form
                method: 'POST',
                data: formData +
                '&_method=PUT&_token={{ csrf_token() }}', // Include CSRF token and method override
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
