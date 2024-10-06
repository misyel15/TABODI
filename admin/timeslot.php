<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

// Assuming you store the department ID in the session during login
// Example: $_SESSION['dept_id'] = $user['dept_id'];
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session
?>
<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<div class="container-fluid" style="margin-top:100px;">
    <div class="row">
        <!-- FORM Panel -->
        <div class="col-md-4">
            <!-- Modal -->
            <div class="modal fade" id="timeslotModal" tabindex="-1" role="dialog" aria-labelledby="timeslotModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="timeslotModalLabel">Timeslot Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" id="manage-timeslot">
                                <input type="hidden" name="id">
                                <input type="hidden" name="dept_id" value="<?php echo $dept_id; ?>">
                                <div class="form-group">
                                    <label class="control-label">Time ID</label>
                                    <input type="text" class="form-control" name="time_id" id="time_id" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Timeslot</label>
                                    <input type="text" class="form-control" name="timeslot" id="timeslot" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Schedule</label>
                                    <input type="text" class="form-control" name="schedule" id="schedule" required>
                                </div>
                                <div class="form-group">
                                    <label for="specialization" class="control-label">Specialization</label>
                                    <select class="form-control" name="specialization" id="specialization" required>
                                        <option value="" disabled selected>Select Specialization</option>
                                        <option value="Major">Major</option>
                                        <option value="Minor">Minor</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="saveTimeslotBtn">Save</button>
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
                <div class="card-header">
                    <b>Timeslot List</b>
                    <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#timeslotModal"><i class="fa fa-user-plus"></i> New Entry</button>
                </div>
                <div class="card-body">
                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="timeslotTable">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Timeslot and Days</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $timeslot = $conn->query("SELECT * FROM timeslot WHERE dept_id = '$dept_id' ORDER BY time_id ASC");
                                while ($row = $timeslot->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-center"><?php echo $row['time_id']; ?></td>
                                    <td>
                                        <p>Time slot: <b><?php echo $row['timeslot']; ?></b></p>
                                        <p>Day: <b><?php echo $row['schedule']; ?></b></p>
                                        <p>Specialization: <b><?php echo $row['specialization']; ?></b></p>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary edit_timeslot" type="button" data-id="<?php echo $row['id']; ?>" data-timeid="<?php echo $row['time_id']; ?>" data-timeslot="<?php echo $row['timeslot']; ?>" data-schedule="<?php echo $row['schedule']; ?>" data-special="<?php echo $row['specialization']; ?>"><i class="fas fa-edit"></i> Edit</button>
                                        <button class="btn btn-sm btn-danger delete_timeslot" type="button" data-id="<?php echo $row['id']; ?>"><i class="fas fa-trash-alt"></i> Delete</button>
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

<style>
td {
    vertical-align: middle !important;
}
</style>

<script>
function _reset() {
    $('#manage-timeslot').get(0).reset();
    $('#manage-timeslot input').val('');
    $('#manage-timeslot select').val('');
}

$('#saveTimeslotBtn').click(function() {
    $('#manage-timeslot').submit();
});

$('#manage-timeslot').submit(function(e) {
    e.preventDefault();
    
    let isValid = true;
    $('#manage-timeslot').find('[required]').each(function() {
        if (!$(this).val()) {
            isValid = false;
        }
    });

    if (!isValid) {
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: 'Please fill out all required fields!',
            showConfirmButton: true,
        });
        return; 
    }
    
    $.ajax({
        url: 'ajax.php?action=save_timeslot',
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
                    text: 'Data successfully added',
                    showConfirmButton: true,
                });
            } else if (resp == 2) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data successfully updated',
                    showConfirmButton: true,
                });
            }
            setTimeout(function() {
                location.reload();
            }, 1500);
        }
    });
});

$('.edit_timeslot').click(function() {
    _reset();
    var cat = $('#manage-timeslot');
    cat.find("[name='id']").val($(this).data('id'));
    cat.find("[name='time_id']").val($(this).data('timeid'));
    cat.find("[name='timeslot']").val($(this).data('timeslot'));
    cat.find("[name='schedule']").val($(this).data('schedule'));
    cat.find("[name='specialization']").val($(this).data('special'));
    $('#timeslotModal').modal('show');
});

$('.delete_timeslot').click(function() {
    var id = $(this).data('id');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            delete_timeslot(id);
        }
    });
});

function delete_timeslot(id) {
    $.ajax({
        url: 'ajax.php?action=delete_timeslot',
        method: 'POST',
        data: { id: id },
        success: function(resp) {
            if (resp == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data successfully deleted',
                    showConfirmButton: true,
                });
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }
        }
    });
}

$(document).ready(function() {
    $('#timeslotTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true
    });
});
</script>
