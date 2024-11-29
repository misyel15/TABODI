<!DOCTYPE html>
<html lang="en">
    
<?php
session_start();

// Security headers to protect your page from vulnerabilities
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://trusted-scripts.com;");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none';");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");

// Redirect all HTTP requests to HTTPS if not already using HTTPS
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// Secure session cookie settings
ini_set('session.cookie_secure', '1');    // Enforces HTTPS-only session cookies
ini_set('session.cookie_httponly', '1');  // Prevents JavaScript from accessing session cookies
ini_set('session.cookie_samesite', 'Strict'); // Prevents CSRF by limiting cross-site cookie usage

// Additional security headers
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Anti-XXE: Secure XML parsing
libxml_disable_entity_loader(true); // Disable loading of external entities
libxml_use_internal_errors(true);   // Suppress libxml errors for better handling

// Check for user session and redirect if not logged in
if(!isset($_SESSION['login_id'])) {
    header('location:login.php');
    exit();
}

// Include additional authentication or authorization scripts here if needed
// include('./auth.php');
?>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>School Faculty Scheduling System</title>
    <link rel="icon" href="assets/uploads/back.png" type="image/png">
</head>

<style>
    body{
        background: #80808045;
    }
    .modal-dialog.large {
        width: 80% !important;
        max-width: unset;
    }
    .modal-dialog.mid-large {
        width: 50% !important;
        max-width: unset;
    }
    #viewer_modal .btn-close {
        position: absolute;
        z-index: 999999;
        background: unset;
        color: white;
        border: unset;
        font-size: 27px;
        top: 0;
    }
    #viewer_modal .modal-dialog {
        width: 80%;
        max-width: unset;
        height: calc(90%);
        max-height: unset;
    }
    #viewer_modal .modal-content {
        background: black;
        border: unset;
        height: calc(100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #viewer_modal img, #viewer_modal video {
        max-height: calc(100%);
        max-width: calc(100%);
    }
</style>

<body>
    <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white"></div>
    </div>

    <main id="view-panel">
        <?php
            $page = isset($_GET['page']) ? $_GET['page'] : 'home';
            include $page;  // No need to specify '.php' as .htaccess handles it
        ?>
    </main>

    <div id="preloader"></div>
    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <!-- Modals -->
    <div class="modal fade" id="uni_modal" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm_modal" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewer_modal" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                <img src="" alt="">
            </div>
        </div>
    </div>
</body>

<script>
    window.start_load = function(){
        $('body').prepend('<di id="preloader2"></di>')
    }
    window.end_load = function(){
        $('#preloader2').fadeOut('fast', function() {
            $(this).remove();
        })
    }

    window.viewer_modal = function($src = ''){
        start_load();
        var t = $src.split('.');
        t = t[1];
        if(t =='mp4'){
            var view = $("<video src='"+$src+"' controls autoplay></video>");
        }else{
            var view = $("<img src='"+$src+"' />");
        }
        $('#viewer_modal .modal-content video, #viewer_modal .modal-content img').remove();
        $('#viewer_modal .modal-content').append(view);
        $('#viewer_modal').modal({
            show:true,
            backdrop:'static',
            keyboard:false,
            focus:true
        });
        end_load();
    }

    window.uni_modal = function($title = '' , $url='', $size=""){
        start_load();
        $.ajax({
            url: $url,
            error: err => {
                alert("An error occurred");
            },
            success: function(resp){
                if(resp){
                    $('#uni_modal .modal-title').html($title);
                    $('#uni_modal .modal-body').html(resp);
                    if($size != ''){
                        $('#uni_modal .modal-dialog').addClass($size);
                    } else {
                        $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md");
                    }
                    $('#uni_modal').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false,
                        focus: true
                    });
                    end_load();
                }
            }
        });
    }

    window._conf = function($msg='', $func='', $params = []){
        $('#confirm_modal #confirm').attr('onclick', $func + "('" + $params.join(',') + "')");
        $('#confirm_modal .modal-body').html($msg);
        $('#confirm_modal').modal('show');
    }

    window.del_conf = function($msg='', $func='', $params = []){
        $('#confirm_modal #confirm').attr('onclick', $func + "('" + $params.join(',') + "')");
        $('#confirm_modal .modal-body').html($msg);
        $('#confirm_modal').modal('show');
    }

    window.alert_toast = function($msg = 'TEST', $bg = 'success'){
        $('#alert_toast').removeClass('bg-success bg-danger bg-info bg-warning');
        if($bg == 'success') $('#alert_toast').addClass('bg-success');
        if($bg == 'danger') $('#alert_toast').addClass('bg-danger');
        if($bg == 'info') $('#alert_toast').addClass('bg-info');
        if($bg == 'warning') $('#alert_toast').addClass('bg-warning');
        $('#alert_toast .toast-body').html($msg);
        $('#alert_toast').toast({delay:3000}).toast('show');
    }

    $(document).ready(function(){
        $('#preloader').fadeOut('fast', function() {
            $(this).remove();
        })
    });

    $('.datetimepicker').datetimepicker({
        format:'Y/m/d H:i',
        startDate: '+3d'
    });

    $('.select2').select2({
        placeholder:"Please select here",
        width: "100%"
    });
</script>
</html>
