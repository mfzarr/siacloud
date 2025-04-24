<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <input type="hidden" id="delete-ids" name="ids">
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
    // Handle the delete confirmation
    $('.delete-button').click(function() {
        var id = $(this).data('id');
        // Set data in the delete confirmation modal
        $('#delete-ids').val(id);
        $('#deleteModal').modal('show');
    });

    $('#confirmDeleteButton').click(function() {
        var id = $('#delete-ids').val();

        $.ajax({
            url: '/masterdata/jasa/destroydetail/' + id, // Ensure the URL matches the route definition
            method: 'DELETE', // Use DELETE method
            data: {
                _token: '{{ csrf_token() }}' // Include CSRF token
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                location.reload(); // Reload the page to see the updated data
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText); // Handle error
            }
        });
    });
</script>
