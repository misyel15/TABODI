<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session
?>

<!-- Include SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">

<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<div class="container-fluid" style="margin-top:100px;">
    <div class="col-lg-14">
        <div class="row">
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Course List</b>
                        <span>
                            <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#courseModal">
                                <i class="fa fa-user-plus"></i> New Entry
                            </button>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="course-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Course</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    $course = $conn->query("SELECT * FROM courses WHERE dept_id = '$dept_id' ORDER BY id ASC");
                                    while($row = $course->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td>
                                            <p>Course: <b><?php echo $row['course'] ?></b></p>
                                            <p>Description: <small><b><?php echo $row['description'] ?></b></small></p>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary edit_course" type="button" data-id="<?php echo $row['id'] ?>" data-course="<?php echo $row['course'] ?>" data-description="<?php echo $row['description'] ?>" data-toggle="modal" data-target="#courseModal">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger delete_course" type="button" data-id="<?php echo $row['id'] ?>">
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
            <!-- Table Panel -->

            <!-- Modal -->
            <div class="modal fade" id="courseModal" tabindex="-1" role="dialog" aria-labelledby="courseModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="courseModalLabel">Course Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="" id="manage-course">
                            <div class="modal-body">
                                <input type="hidden" name="id">
                                <div class="form-group">
                                    <label class="control-label">Course</label>
                                    <input type="text" class="form-control" name="course" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    <textarea class="form-control" cols="30" rows='3' name="description" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal -->
        </div>
    </div>
</div>

<style>
    td {
        vertical-align: middle !important;
    }
</style>

<script>
    function _reset() {
        $('#manage-course').get(0).reset();
        $('#manage-course input, #manage-course textarea').val('');
    }

    $('#manage-course').submit(function(e) {
        e.preventDefault();

        // Validation: Check if required fields are filled
        let course = $("input[name='course']").val().trim();
        let description = $("textarea[name='description']").val().trim();
        let id = $("input[name='id']").val(); // Get the course ID

        if (course === '' || description === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'Please fill out all required fields.',
                showConfirmButton: true
            });
            return; // Stop the form submission if validation fails
        }

        // Prepare the data to send
        let data = new FormData($(this)[0]);

        $.ajax({
            url: 'ajax.php?action=save_course',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            success: function(resp) {
                console.log("Response:", resp); // Log the response for debugging

                if (resp == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data successfully added!',
                        showConfirmButton: true
                    }).then(function() {
                        location.reload(); // Reload page on success
                    });
                } else if (resp == 2) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data successfully updated!',
                        showConfirmButton: true
                    }).then(function() {
                        // Update the existing row in the DataTable
                        var updatedRow = $('#course-table').find('button[data-id="' + id + '"]').closest('tr');
                        updatedRow.find('td:eq(1)').html(`
                            <p>Course: <b>${course}</b></p>
                            <p>Description: <small><b>${description}</b></small></p>
                        `);
                        $('#courseModal').modal('hide'); // Hide the modal
                    });
                } else if (resp == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Course already exists!',
                        showConfirmButton: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An unexpected error occurred. Please try again.',
                        showConfirmButton: true
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while processing your request. Please try again.',
                    showConfirmButton: true
                });
            }
        });
    });

    $('.edit_course').click(function() {
        var cat = $('#manage-course');
        cat.get(0).reset();
        cat.find("[name='id']").val($(this).attr('data-id'));
        cat.find("[name='course']").val($(this).attr('data-course'));
        cat.find("[name='description']").val($(this).attr('data-description'));
    });

    $('.delete_course').click(function() {
        var id = $(this).attr('data-id');
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
                delete_course(id);
            }
        });
    });

    function delete_course(id) {
        $.ajax({
            url: 'ajax.php?action=delete_course',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The course has been deleted.',
                        showConfirmButton: true
                    }).then(function() {
                        location.reload(); // Reload page after deletion
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while deleting the course. Please try again.',
                        showConfirmButton: true
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while processing your request. Please try again.',
                    showConfirmButton: true
                });
            }
        });
    }

    $(document).ready(function() {
        $('#course-table').DataTable(); // Initialize DataTable
    });
</script>
