<?php include('admin/db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">

<?php session_start(); ?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>School Faculty Scheduling System</title>

  <?php
  if (!isset($_SESSION['login_id']))
    header('location:login.php');
  include('./header.php'); 
  ?>
<style>
  body {
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

  #viewer_modal img,
  #viewer_modal video {
    max-height: calc(100%);
    max-width: calc(100%);
  }

  /* Responsive Styles */
  @media (max-width: 767.98px) {
    .table {
      display: block;
      overflow-x: auto;
      white-space: nowrap;
    }

    .modal-dialog {
      width: 90%;
      max-width: unset;
    }

    .modal-content {
      height: auto;
      max-height: unset;
    }
  }

  @media (orientation: landscape) {
    /* Styles for landscape view */
    .table {
      font-size: 14px; /* Adjust font size for better readability in landscape mode */
    }

    .modal-dialog {
      width: 70%;
      max-width: unset;
    }

    .modal-content {
      padding: 1rem;
    }
  }

</style>

</head>

<body>
  <?php include 'topbar.php' ?>
  <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body text-white"></div>
  </div>
  <main id="" style="margin-top: 3.5rem;" class="bg-dark">
    <div class="container pt-4 pb-4">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
              <h2 class="text-center mb-4">Instructor Load's</h2>
            <table class="table table-bordered table-condensed table-hover" id="insloadtable">
              <thead>
                <tr>
                  <th class="text-center">Code</th>
                  <th class="text-center">Descriptive Title</th>
                  <th class="text-center">Day</th>
                  <th class="text-center">Time</th>
                  <th class="text-center">Room</th>
                  <th class="text-center">Section</th>
                  <th class="text-center">Units (lec)</th>
                  <th class="text-center">Units (lab)</th>
                  <th class="text-center">Total Units</th>
                  <th class="text-center">Total Hours</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                $sumtu = 0;
                $sumh = 0;
                $faculty_id = $_SESSION['login_id'];
                $loads = $conn->query("SELECT * FROM loading where faculty='$faculty_id'");
                while ($lrow = $loads->fetch_assoc()) {
                  $days = $lrow['days'];
                  $timeslot = $lrow['timeslot'];
                  $course = $lrow['course'];
                  $subject_code = $lrow['subjects'];
                  $room_id = $lrow['rooms'];
                  $fid = $lrow['faculty'];

                  $faculty = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name from faculty Where id =" . $fid);
                  while ($frow = $faculty->fetch_assoc()) {
                    $instname = $frow['name'];
                  }

                  $subjects = $conn->query("SELECT * FROM subjects WHERE subject = '$subject_code'");

                  while ($srow = $subjects->fetch_assoc()) {
                    $description = $srow['description'];
                    $units = $srow['total_units'];
                    $lec_units = $srow['Lec_Units'];
                    $lab_units = $srow['Lab_Units'];
                    $hours = $srow['hours'];
                    $sumtu += $units;
                    $sumh += $hours;
                  }

                  $rooms = $conn->query("SELECT * FROM roomlist WHERE room_id = " . $room_id);
                  while ($roomrow = $rooms->fetch_assoc()) {
                    $room_name = $roomrow['room_name'];
                  }

                  echo '<tr>
                    <td class="text-center" data-label="Code">' . $subject_code . '</td>
                    <td class="text-center" style="width: 150px" data-label="Descriptive Title">' . $description . '</td>
                    <td class="text-center" data-label="Day">' . $days . '</td>
                    <td class="text-center" data-label="Time">' . $timeslot . '</td>
                    <td class="text-center" data-label="Room">' . $room_name . '</td>
                    <td class="text-center" data-label="Section">' . $course . '</td>
                    <td class="text-center" data-label="Units (lec)">' . $lec_units . '</td>
                    <td class="text-center" data-label="Units (lab)">' . $lab_units . '</td>
                    <td class="text-center" data-label="Total Units">' . $units . '</td>
                    <td class="text-center" data-label="Total Hours">' . $hours . '</td>
                  </tr>';
                }
                echo '<tfoot><tr style="height: 20px">
                  <td class="s4"></td>
                  <td class="s3"></td>
                  <td class="s3"></td>
                  <td class="s3"></td>
                  <td class="s3"></td>
                  <td class="s3"></td>
                  <td class="s10 softmerge">
                    <div class="text-center" style="width:150px;left:-1px">
                      <span style="font-weight:bold;">Total Number of Units/Hours(Basic)</span>
                    </div>
                  </td>
                  <td class="s11"></td>
                  <td class="text-center">' . $sumtu . '</td>
                  <td class="text-center">' . $sumh . '</td>
                </tr></tfoot>';
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>

  <div id="preloader"></div>
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <div class="modal fade" id="uni_modal" role='dialog'>
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
  <div class="modal fade" id="confirm_modal" role='dialog'>
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
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
        <img src="" alt="">
      </div>
    </div>
  </div>

  <script>
    window.start_load = function() {
      $('body').prepend('<di id="preloader2"></di>')
    }
    window.end_load = function() {
      $('#preloader2').fadeOut('fast', function() {
        $(this).remove();
      })
    }
    window.viewer_modal = function($src = '') {
      start_load()
      var t = $src.split('.')
      t = t[1]
      if (t == 'mp4') {
        var view = $("<video src='" + $src + "' controls autoplay></video>")
      } else {
        var view = $("<img src='" + $src + "' />")
      }
      $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
      $('#viewer_modal .modal-content').append(view)
      $('#viewer_modal').modal({
        show: true,
        backdrop: 'static',
        keyboard: false,
        focus: true
      })
      end_load()
    }
    window.uni_modal = function($title = '', $url = '', $size = "") {
      start_load()
      $.ajax({
        url: $url,
        error: err => {
          console.log()
          alert("An error occurred")
        },
        success: function(resp) {
          if (resp) {
            $('#uni_modal .modal-title').html($title)
            $('#uni_modal .modal-body').html(resp)
            if ($size != '') {
              $('#uni_modal .modal-dialog').addClass($size)
            } else {
              $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
            }
            $('#uni_modal').modal({
              show: true,
              backdrop: 'static',
              keyboard: false,
              focus: true
            })
            end_load()
          }
        }
      })
    }
    window._conf = function($msg = '', $func = '', $params = []) {
      $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")")
      $('#confirm_modal .modal-body').html($msg)
      $('#confirm_modal').modal('show')
    }
    window.alert_toast = function($msg = 'TEST', $bg = 'success') {
      $('#alert_toast').removeClass('bg-success bg-danger bg-info bg-warning')
      $('#alert_toast').addClass('bg-' + $bg)
      $('#alert_toast .toast-body').html($msg)
      $('#alert_toast').toast({ delay: 3000 }).toast('show');
    }
    $(document).ready(function() {
      $('#preloader').fadeOut('fast', function() {
        $(this).remove();
      })
    })
    $('.datetimepicker').datetimepicker({
      format: 'Y/m/d H:i',
      startDate: '+3d'
    })
    $('.select2').select2({
      placeholder: "Please select here",
      width: "100%"
    })
  </script>
</body>

</html>
