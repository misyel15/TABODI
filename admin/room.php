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
        <!-- FORM Panel -->
        <div class="col-md-4">
        <!-- Modal -->
<div class="modal fade" id="roomModal" tabindex="-1" aria-labelledby="roomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomModalLabel">Room Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="manage-room">
                    <input type="hidden" name="id">
                    <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>">
                    <div class="form-group mb-3">
                        <label class="form-label">Room ID</label>
                        <input type="text" class="form-control" name="room_id" id="room_id">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Room</label>
                        <input type="text" class="form-control" name="room" id="room">
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

            <!-- Modal -->
        </div>
        <!-- FORM Panel -->

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
                                    $course = $conn->query("SELECT * FROM roomlist WHERE dept_id = '$dept_id' ORDER BY id ASC");
                                    while($row = $course->fetch_assoc()):
                                    ?>
                                <tr>
                                    <td class="text-center"><?php echo $row['room_id']; ?></td>
                                    <td>
                                        <p>Room name: <b><?php echo $row['room_name']; ?></b></p>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit_room" type="button" data-id="<?php echo $row['id']; ?>" data-room="<?php echo $row['room_name']; ?>" data-room_id="<?php echo $row['room_id']; ?>"><i class="fas fa-edit"></i> Edit</button>
                                        <button class="btn btn-sm btn-danger delete_room" type="button" data-id="<?php echo $row['id']; ?>"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table Panel -->
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
        $('#roomModal').modal('show');
    });

    // Reset form function
    function _reset() {
        $('#manage-room').get(0).reset();
        $('#manage-room input').val('');
    }

    // Save Room
    $('#saveRoomBtn').click(function() {
        $('#manage-room').submit();
    });

    $('#manage-room').submit(function(e) {
        e.preventDefault();
        let room = $("input[name='room']").val().trim();
        let room_id = $("input[name='room_id']").val().trim();

        if (room === '' || room_id === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'Please fill out all required fields.',
                showConfirmButton: true
            });
            return;
        }

        $.ajax({
            url: 'ajax.php?action=save_room',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Room data successfully added.',
                        showConfirmButton: true
                    }).then(function() {
                        location.reload();
                    });
                } else if (resp == 2) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Room data successfully updated.',
                        showConfirmButton: true
                    }).then(function() {
                        location.reload();
                    });
                } else if (resp == 3) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Room name or ID already exists.',
                        showConfirmButton: true
                    });
                }
            }
        });
    });

    // Edit Room
    $('.edit_room').click(function() {
        var cat = $('#manage-room');
        cat.get(0).reset();
        cat.find("[name='id']").val($(this).attr('data-id'));
        cat.find("[name='room']").val($(this).attr('data-room'));
        cat.find("[name='room_id']").val($(this).attr('data-room_id'));
        $('#roomModal').modal('show');
    });

    // Delete Room
    $('.delete_room').click(function() {
        var id = $(this).attr('data-id');
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
                delete_room(id);
            }
        });
    });

    function delete_room(id) {
        $.ajax({
            url: 'ajax.php?action=delete_room',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Room data successfully deleted.',
                        showConfirmButton: true
                    }).then(function() {
                        location.reload();
                    });
                }
            }
        });
    }
});
</script>
