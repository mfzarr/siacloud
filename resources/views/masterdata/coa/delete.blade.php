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
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes, Delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.delete-button').click(function () {
        var id = $(this).data('id'); // Ambil ID dari tombol yang diklik

        // Tampilkan modal konfirmasi
        $('#deleteModal').modal('show');

        // Ketika tombol konfirmasi delete diklik
        $('#confirmDeleteButton').click(function () {
            $.ajax({
                url: '/masterdata/coas/' + id, // Pastikan URL sesuai dengan route destroy
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function (response) {
                    $('#deleteModal').modal('hide'); // Tutup modal
                    location.reload(); // Reload halaman
                },
                error: function (xhr) {
                    console.error('Error deleting data:', xhr.responseText);
                    alert('Failed to delete the account.');
                }
            });
        });
    });
});


</script>