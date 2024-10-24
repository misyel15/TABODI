<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM faculty where id=".$_GET['id'])->fetch_array();
    foreach($qry as $k =>$v){
        $$k = $v;
    }
}
?>

<div class="container-fluid">
    <form action="" id="manage-faculty">
        <div id="msg"></div>
        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id']:'' ?>" class="form-control">
        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">ID No.</label>
                <input type="number" name="id_no" class="form-control" value="<?php echo isset($id_no) ? $id_no:'' ?>" min="0" pattern="\d*" maxlength="10" title="ID No should be numeric and up to 10 digits.">
                <small><i>Leave this blank if you want to auto-generate ID no.</i></small>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">Last Name</label>
                <input type="text" name="lastname" class="form-control" value="<?php echo isset($lastname) ? $lastname:'' ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">First Name</label>
                <input type="text" name="firstname" class="form-control" value="<?php echo isset($firstname) ? $firstname:'' ?>" required>
            </div>
            <div class="col-md-4">
                <label class="control-label">Middle Name</label>
                <input type="text" name="middlename" class="form-control" value="<?php echo isset($middlename) ? $middlename:'' ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-4">
                <label class="control-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($email) ? $email:'' ?>" required>
            </div>
            <div class="col-md-4">
    <label class="control-label">Contact #</label>
    <input type="tel" name="contact" class="form-control" value="<?php echo isset($contact) ? $contact : '' ?>" maxlength="11" title="Contact number should be numeric and up to 11 digits." required>
</div>

            <div class="col-md-4">
                <label class="control-label">Gender</label>
                <select name="gender" required class="custom-select">
                    <option value="" disabled selected>Select Gender</option>
                    <option <?php echo isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option <?php echo isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-12">
                <label class="control-label">Address</label>
                <textarea name="address" class="form-control" required><?php echo isset($address) ? $address : '' ?></textarea>
            </div>
        </div>
        
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
        <button type="submit" class="btn btn-primary">Cancel</button>
    </div>
</form>
    </form>
</div>
<script>
$(document).ready(function() {
    // Restrict input to numbers and enforce maxlength
    $('input[name="contact"]').on('input', function() {
        // Remove non-numeric characters
        this.value = this.value.replace(/\D/g, '');

        // Limit input length to 11 digits
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }
    });

    $('#manage-faculty').submit(function(e){
        e.preventDefault();

        // Check if all required fields are filled
        let valid = true;
        $('#manage-faculty [required]').each(function(){
            if ($(this).val() === '') {
                valid = false;
                return false;
            }
        });

        // Validate ID No. and Contact # lengths
        let id_no = $('input[name="id_no"]').val();
        let contact = $('input[name="contact"]').val();

        // Check for negative values
        if (id_no < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'ID No. should be a positive number.',
                showConfirmButton: true
            });
            return;
        }

        if (contact < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'Contact number should be a positive number.',
                showConfirmButton: true
            });
            return;
        }

        // Check if contact contains only numbers and has exactly 11 digits
        if (!/^\d+$/.test(contact) || contact.length !== 11) {
          
        }

        if (!valid) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'Please fill out all required fields.',
                showConfirmButton: true
            });
            return;
        }

        if (id_no.length > 10) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: 'ID No. should be up to 10 digits only.',
                showConfirmButton: true
            });
            return;
        }

        // Submit form via AJAX
        $.ajax({
            url: 'ajax.php?action=save_faculty',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){
                if (resp == 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Faculty data successfully saved.',
                        showConfirmButton: true,
                    }).then(function(){
                        location.reload();
                    });
                } else if (resp == 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: 'ID No already exists.',
                        showConfirmButton: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to save faculty data.',
                        showConfirmButton: true
                    });
                }
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to save faculty data. Please try again later.',
                    showConfirmButton: true
                });
            }
        });
    });
});

</script>
