<?php
session_start(); // Start the session
include('db_connect.php');
include 'includes/header.php';

// Assuming the user department ID is stored in the session after login
$dept_id = isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : null;
?>

<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid" style="margin-top:100px;">
    <div class="row">
        <div class="col-md-4">
            <!-- Modal -->
            <div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="roomModalLabel">Room Form</h5>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="manage-room">
                                <input type="hidden" name="id">
                                <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>">
                                <div class="form-group mb-3">
                                    <label class="form-label">Room ID</label>
                                    <input type="text" class="form-control" name="room_id" id="room_id" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Room Name</label>
                                    <input type="text" class="form-control" name="room" id="room" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="saveRoomBtn">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Panel -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <b>Room List</b>
                    <button class="btn btn-primary btn-sm" id="newEntryBtn"><i class="fa fa-user-plus"></i> New Entry</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="roomTable">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Room</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 1;
                                    $rooms = $conn->query("SELECT * FROM roomlist WHERE dept_id = '$dept_id' ORDER BY id ASC");
                                    while ($row = $rooms->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $row['room_id']; ?></td>
                                    <td>
                                        <p>Room name: <b><?php echo $row['room_name']; ?></b></p>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit_room" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-room="<?php echo $row['room_name']; ?>" 
                                                data-room_id="<?php echo $row['room_id']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete_room" 
                                                data-id="<?php echo $row['id']; ?>">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#roomTable').DataTable({
        responsive: true
    });

    // Show the modal when clicking the "New Entry" button
    $('#newEntryBtn').click(function() {
        _reset();
        $('#roomModal').modal('show');
    });

    // Reset form function
    function _reset() {
        $('#manage-room')[0].reset();
        $('#manage-room input[name="id"]').val('');
    }

    // Save Room
    $('#saveRoomBtn').click(function() {
        $('#manage-room').submit();
    });

    $('#manage-room').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'ajax.php?action=save_room',
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function(resp) {
                let message = (resp == 1) ? 'Room data successfully added.' :
                              (resp == 2) ? 'Room data successfully updated.' :
                              'Room name or ID already exists.';
                let icon = (resp == 3) ? 'error' : 'success';

                Swal.fire('Success!', message, icon).then(() => location.reload());
            }
        });
    });

    // Edit Room
    $('.edit_room').click(function() {
        let form = $('#manage-room');
        form[0].reset();
        form.find('[name="id"]').val($(this).data('id'));
        form.find('[name="room"]').val($(this).data('room'));
        form.find('[name="room_id"]').val($(this).data('room_id'));
        $('#roomModal').modal('show');
    });

    // Delete Room
    $('.delete_room').click(function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this data!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'ajax.php?action=delete_room',
                    method: 'POST',
                    data: { id: id },
                    success: function(resp) {
                        if (resp == 1) {
                            Swal.fire('Deleted!', 'Room data successfully deleted.', 'success').then(() => location.reload());
                        }
                    }
                });
            }
        });
    });
});
</script>
