<?php
session_start(); // Start the session
include('db_connect.php');
include 'includes/header.php';

// Assuming the user department ID is stored in the session after login
$dept_id = isset($_SESSION['dept_id']) ? $_SESSION['dept_id'] : null;
?>

<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Include DataTables CSS (optional) -->
<link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Include DataTables JS (optional) -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>


<div class="container-fluid" style="margin-top:100px;">
    <div class="col-lg-13">
        <div class="row">
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>User List</b>
                        <button class="btn btn-primary btn btn-sm  float-right" id="new_user">
                            <i class="fas fa-user-plus"></i> New user
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" id="userTable">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Department</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $type = array("", "", "Admin", "Staff", "Alumnus/Alumna");
                                    $users = $conn->query("SELECT * FROM users WHERE dept_id = '$dept_id' ORDER BY name ASC");
                                    $i = 1;
                                    while ($row = $users->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td><?php echo ucwords($row['name']) ?></td>
                                    <td><?php echo $row['username'] ?></td>
                                    <td><?php echo $row['email'] ?></td>
                                    <td><?php echo $row['course']?></td>
                                    <td><?php echo $type[$row['type']] ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit_user" data-id="<?php echo $row['id'] ?>">
                                                <i class="fas fa-edit"></i> <!-- Edit icon -->
                                            </button>
                                            <!-- Uncomment if delete functionality is needed -->
                                            <!-- <button type="button" class="btn btn-sm btn-danger delete_user" data-id="<?php echo $row['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button> -->
                                        </div>
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

<!-- Modal HTML (hidden by default) -->
<div class="modal fade" id="uni_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
            </div>
            <div class="modal-body">
                <!-- Content loaded via iframe -->
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#userTable').DataTable();

    // Function to open modals
    function uni_modal(title, url) {
        $('#uni_modal .modal-title').text(title);
        $('#uni_modal .modal-body').html(`<iframe src="${url}" frameborder="0" style="width: 100%; height: 400px;"></iframe>`);
        $('#uni_modal').modal('show');
    }

    // New user modal
    $('#new_user').click(function() {
        uni_modal('New User', 'manage_user.php');
    });

    // Edit user modal
    $(document).on('click', '.edit_user', function() {
        uni_modal('Edit User', 'manage_user.php?id=' + $(this).data('id'));
    });

    // Delete user action
    $(document).on('click', '.delete_user', function() {
        _conf("Are you sure to delete this user?", "delete_user", [$(this).data('id')]);
    });

    // Delete user function
    window.delete_user = function(id) {
        $.ajax({
            url: 'ajax.php?action=delete_user',
            method: 'POST',
            data: {id: id},
            success: function(resp) {
                if (resp == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data successfully deleted',
                        showConfirmButton: true
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            }
        });
    };
});
</script>
