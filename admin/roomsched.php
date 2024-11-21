

<?php
session_start();
include('db_connect.php');
include 'includes/header.php';

// Assuming you store the department ID in the session during login
$dept_id = $_SESSION['dept_id']; // Get the department ID from the session

// Get the selected room from the POST request
$selected_room = isset($_POST['selected_room']) ? $_POST['selected_room'] : '';
?>
<div class="container-fluid" style="margin-top:100px; margin-left:-15px;">
    <div class="container-fluid mt-5">
        <!-- Table Panel for Monday/Wednesday -->
        <div class="card mb-4">
            <div class="card-header text-center">
                <h3>Room Schedule</h3>
                <div class="d-flex justify-content-end">
                    <!-- Print Button -->
                    <a href="print_schedule.php?selected_room=<?php echo urlencode($selected_room); ?>" class="btn btn-secondary" target="_blank">Print Schedule</a>
                </div>
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
                            echo '<option value="' . htmlspecialchars($r['room_name']) . '"' . ($selected_room === htmlspecialchars($r['room_name']) ? ' selected' : '') . '>' . htmlspecialchars($r['room_name']) . '</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <button type="reset" class="btn btn-secondary ml-2" onclick="document.getElementById('filterForm').reset();">Reset</button>
                </form>
            </div>
            <div class="card-body">
            <h4>Monday/Wednesday</h4>
                <table class="table table-bordered waffle no-grid" id="insloadtable">
                    <thead>
                        <tr>
                            <th class="text-center">Time</th>
                            <?php
                            // Fetching room names for headers
                            $rooms = [];
                            $roomsdata = $conn->prepare("SELECT * FROM roomlist WHERE dept_id = ? ORDER BY room_id");
                            $roomsdata->bind_param('i', $dept_id);
                            $roomsdata->execute();
                            $result = $roomsdata->get_result();
                            while ($r = $result->fetch_assoc()) {
                                $rooms[] = $r['room_name'];
                            }
                            foreach ($rooms as $room) {
                                echo '<th class="text-center">' . htmlspecialchars($room) . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $times = array();
                        $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='MW' AND dept_id = '$dept_id' order by time_id;");
                        while ($t = $timesdata->fetch_assoc()) {
                            $times[] = $t['timeslot'];
                        }

                        foreach ($times as $time) {
                            echo "<tr><td>" . htmlspecialchars($time) . "</td>"; // Escape time
                            foreach ($rooms as $room) {
                                // Check if a specific room is selected for filtering
                                if ($selected_room && $room !== $selected_room) {
                                    echo "<td></td>"; // Skip displaying for non-selected rooms
                                    continue;
                                }

                                // Prepare statement to fetch loading data
                                $query = "SELECT * FROM loading WHERE timeslot='" . mysqli_real_escape_string($conn, $time) . "' AND room_name='" . mysqli_real_escape_string($conn, $room) . "' AND days='MW'";
                                $result = mysqli_query($conn, $query);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $course = htmlspecialchars($row['course']);
                                        $subject = htmlspecialchars($row['subjects']);
                                        $faculty = htmlspecialchars($row['faculty']);
                                        $load_id = $row['id'];
                                        $scheds = "$subject $course";
                                        
                                        // Fetching faculty name
                                        $faculty_stmt = $conn->prepare("SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id=?");
                                        $faculty_stmt->bind_param('i', $faculty);
                                        $faculty_stmt->execute();
                                        $faculty_name_result = $faculty_stmt->get_result();
                                        $faculty_name = $faculty_name_result->fetch_assoc()['name'] ?? 'Unknown Faculty'; // Fallback if no faculty found
                                        
                                        $newSched = htmlspecialchars($scheds . " " . $faculty_name);
                                        echo '<td class="text-center content" data-id="' . htmlspecialchars($load_id) . '" data-scode="' . htmlspecialchars($subject) . '">' 
                                            . $newSched 
                                            . '<br>' 
                                            . '</td>';
                                    }
                                } else {
                                    echo "<td></td>"; // No data found for this room and time
                                }
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="card-body">
            <h4>Tuesday/Thursday</h4>
                <table class="table table-bordered waffle no-grid" id="insloadtable">
                    <thead>
                        <tr>
                            <th class="text-center">Time</th>
                            <?php
                            // Fetching room names for headers
                            $rooms = [];
                            $roomsdata = $conn->prepare("SELECT * FROM roomlist WHERE dept_id = ? ORDER BY room_id");
                            $roomsdata->bind_param('i', $dept_id);
                            $roomsdata->execute();
                            $result = $roomsdata->get_result();
                            while ($r = $result->fetch_assoc()) {
                                $rooms[] = $r['room_name'];
                            }
                            foreach ($rooms as $room) {
                                echo '<th class="text-center">' . htmlspecialchars($room) . '</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $times = array();
                        $timesdata = $conn->query("SELECT * FROM timeslot WHERE schedule='TTH' AND dept_id = '$dept_id' order by time_id;");
                        while ($t = $timesdata->fetch_assoc()) {
                            $times[] = $t['timeslot'];
                        }

                        foreach ($times as $time) {
                            echo "<tr><td>" . htmlspecialchars($time) . "</td>"; // Escape time
                            foreach ($rooms as $room) {
                                // Check if a specific room is selected for filtering
                                if ($selected_room && $room !== $selected_room) {
                                    echo "<td></td>"; // Skip displaying for non-selected rooms
                                    continue;
                                }

                                // Prepare statement to fetch loading data
                                $query = "SELECT * FROM loading WHERE timeslot='" . mysqli_real_escape_string($conn, $time) . "' AND room_name='" . mysqli_real_escape_string($conn, $room) . "' AND days='TTH'";
                                $result = mysqli_query($conn, $query);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $course = htmlspecialchars($row['course']);
                                        $subject = htmlspecialchars($row['subjects']);
                                        $faculty = htmlspecialchars($row['faculty']);
                                        $load_id = $row['id'];
                                        $scheds = "$subject $course";
                                        
                                        // Fetching faculty name
                                        $faculty_stmt = $conn->prepare("SELECT CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM faculty WHERE id=?");
                                        $faculty_stmt->bind_param('i', $faculty);
                                        $faculty_stmt->execute();
                                        $faculty_name_result = $faculty_stmt->get_result();
                                        $faculty_name = $faculty_name_result->fetch_assoc()['name'] ?? 'Unknown Faculty'; // Fallback if no faculty found
                                        
                                        $newSched = htmlspecialchars($scheds . " " . $faculty_name);
                                        echo '<td class="text-center content" data-id="' . htmlspecialchars($load_id) . '" data-scode="' . htmlspecialchars($subject) . '">' 
                                            . $newSched 
                                            . '<br>' 
                                            . '</td>';
                                    }
                                } else {
                                    echo "<td></td>"; // No data found for this room and time
                                }
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
