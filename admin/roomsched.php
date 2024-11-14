<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

// Get the department ID from the session
$dept_id = $_SESSION['dept_id'];

// Get the selected room from the POST request or default to none
$selected_room = isset($_POST['selected_room']) ? $_POST['selected_room'] : '';
?>

<div class="container-fluid" style="margin-top:100px; margin-left:-15px;">
    <div class="container-fluid mt-5">


        <div class="card mb-4">
            <div class="card-header text-center">
                <h3>Room Schedule</h3>
                <form method="POST" class="form-inline mt-2" id="filterForm" action="">
                    <select name="selected_room" class="form-control mr-2">
                        <option value="">Select Room</option>
                        <?php
                        // Fetch the list of rooms to populate the dropdown
                        $roomsdata = $conn->prepare("SELECT * FROM roomlist WHERE dept_id = ? ORDER BY room_id");
                        $roomsdata->bind_param('i', $dept_id);
                        $roomsdata->execute();
                        $result = $roomsdata->get_result();
                        while ($r = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($r['room_name']) . '"' . 
                                 ($selected_room === htmlspecialchars($r['room_name']) ? ' selected' : '') . '>' . 
                                 htmlspecialchars($r['room_name']) . '</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <button type="reset" class="btn btn-secondary ml-2" onclick="document.getElementById('filterForm').reset();">Reset</button>
                    <a href="print_schedule.php?selected_room=<?php echo urlencode($selected_room); ?>" class="btn btn-secondary ml-2">Print Schedule</a>
                </form>
            </div>

            <?php
            // Helper function to display schedules by day type
            function display_schedule($day_type, $selected_room, $dept_id, $conn) {
                echo "<div class='card-body'><h4>$day_type</h4>";
                echo "<table class='table table-bordered waffle no-grid'>";
                echo "<thead><tr><th class='text-center'>Time</th><th class='text-center'>$selected_room</th></tr></thead><tbody>";

                // Fetch time slots for the specific day type
                $times = [];
                $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='$day_type' AND dept_id = '$dept_id' ORDER BY time_id");
                while ($t = $timesdata->fetch_assoc()) {
                    $times[] = $t['timeslot'];
                }

                // Display each time slot
                foreach ($times as $time) {
                    echo "<tr><td>" . htmlspecialchars($time) . "</td>";

                    // Prepare statement to fetch loading data
                    $query = "SELECT * FROM loading WHERE timeslot=? AND room_name=? AND days=?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('sss', $time, $selected_room, $day_type);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $course = htmlspecialchars($row['course']);
                            $subject = htmlspecialchars($row['subjects']);
                            $faculty_id = htmlspecialchars($row['faculty']);

                            // Fetch faculty name
                            $faculty_stmt = $conn->prepare("SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id=?");
                            $faculty_stmt->bind_param('i', $faculty_id);
                            $faculty_stmt->execute();
                            $faculty_name_result = $faculty_stmt->get_result();
                            $faculty_name = $faculty_name_result->fetch_assoc()['name'] ?? 'Unknown Faculty';

                            echo "<td class='text-center'>" . htmlspecialchars("$subject $course $faculty_name") . "</td>";
                        }
                    } else {
                        echo "<td></td>"; // No data for this time slot
                    }
                    echo "</tr>";
                }
                echo "</tbody></table></div>";
            }

            // Display schedules by day type
            display_schedule("MW", $selected_room, $dept_id, $conn);
            display_schedule("TTH", $selected_room, $dept_id, $conn);
            display_schedule("FS", $selected_room, $dept_id, $conn);
            ?>
        </div>
    </div>
</div>
