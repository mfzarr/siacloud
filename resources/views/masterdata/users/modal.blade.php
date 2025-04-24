<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="newUserForm" method="POST" action="/masterdata/users">
                    @csrf
                    <!-- Select Company -->
                    <div class="form-group">
                        <label for="perusahaan" class="col-form-label">Select Perusahaan:</label>
                        <select class="form-control" id="perusahaan" name="id_perusahaan" required>
                            <option value="" selected hidden>Select Company</option>
                            @foreach($perusahaan as $p)
                                <option value="{{ $p->id_perusahaan }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username" class="col-form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="col-form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="col-form-label">Confirm Password:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <!-- Select Role -->
                    <div class="form-group">
                        <label for="role" class="col-form-label">Role:</label>
                        <select class="form-control" id="role" name="id_role" required>
                            <option value="" selected hidden>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id_role }}">{{ $role->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Status -->
                    <div class="form-group">
                        <label for="status" class="col-form-label">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <!-- Detail -->
                    <div class="form-group">
                        <label for="detail" class="col-form-label">Detail:</label>
                        <textarea class="form-control" id="detail" name="detail"></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmUserButton">Create User</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle form submission for creating new user
        $('#confirmUserButton').click(function() {
            var form = $('#newUserForm');
            var formData = form.serialize(); // Serialize form data

            // Send AJAX request
            $.ajax({
                url: '/masterdata/users', // Adjust the URL if necessary
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Handle success
                    $('#addmodal').modal('hide');
                    location.reload(); // Reload the page to see the new user
                },
                error: function(xhr) {
                    // Handle error
                    alert('Error: ' + xhr.responseText);
                }
            });
        });
    });
</script>
