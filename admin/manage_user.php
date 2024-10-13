<?php 
include('db_connect.php');
session_start();

// Fetch user data if ID is set
$meta = [];
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $meta = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Register</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
   
    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.min.css">
</head>
<body>
<div class="container-fluid">
    <div id="msg"></div>
    <form action="" id="manage-user">    
    <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? htmlspecialchars($meta['id']) : ''; ?>">
    
    <!-- Name Field -->
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? htmlspecialchars($meta['name']) : ''; ?>" required>
    </div>

    <!-- Username Field -->
    <div class="form-group">
        <label for="username">Username</label>
        <input type="email" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? htmlspecialchars($meta['username']) : ''; ?>" required autocomplete="off">
    </div>

    <!-- Email Field -->
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? htmlspecialchars($meta['email']) : ''; ?>" required autocomplete="off">
    </div>

  <!-- Course Field -->
<div class="form-group">
    <div class="col-sm-13">
        <label for="course" class="control-label">Course</label>
        <select class="form-control" name="course" id="course" required>
            <option value="0" disabled selected>Select Course</option>
            <?php 
                // Fetch courses filtered by department
                $sql = "SELECT * FROM courses WHERE dept_id = '$dept_id'";
                $query = $conn->query($sql);

                // Loop through each course and populate the dropdown
                while($row = $query->fetch_array()):
                    $course = $row['course'];
            ?>
            <option value="<?php echo htmlspecialchars($course) ?>" 
                <?php echo (isset($meta['course']) && $meta['course'] == $course) ? 'selected' : ''; ?>>
                <?php echo ucwords(htmlspecialchars($course)) ?>
            </option>
            <?php endwhile; ?>
        </select>
    </div>
</div>


    <!-- Password Field -->
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" value="<?php echo !isset($meta['id']) ? 'defaultpassword123' : ''; ?>" autocomplete="off">
        <?php if (isset($meta['id'])): ?>
            <small><i>Leave this blank if you don't want to change the password.</i></small>
        <?php endif; ?>
    </div>

    <!-- Hidden Type Field (only if no meta or user type is being created) -->
    <?php if (isset($meta['type']) && $meta['type'] == 3): ?>
        <input type="hidden" name="type" value="3">
    <?php else: ?>
        <?php if (!isset($_GET['mtype'])): ?>
            <input type="hidden" name="type" value="1">
        <?php endif; ?>
    <?php endif; ?>

    <!-- Submit Buttons -->
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" onclick="window.history.back()">Close</button>
    </div>
</form>

    <!-- Include jQuery and SweetAlert JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#manage-user').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            $.ajax({
                url: 'ajax.php?action=save_user',
                method: 'POST',
                data: $(this).serialize(), // Serialize form data for submission
                success: function(resp) {
                    if (resp == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Data successfully updated!',
                            showConfirmButton: true
                        }).then(function() {
                            location.reload(); // Reload the page after user acknowledges the success message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Failed to update data. Please try again.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to save data. Please try again later.'
                    });
                }
            });
        });
    });
    </script>
</body>
</html>
