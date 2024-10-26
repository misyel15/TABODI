<?php 
include 'db_connect.php'; 
session_start(); // Start the session

include 'includes/header.php';
// Check if the user is logged in and has a dept_id
if (!isset($_SESSION['username']) || !isset($_SESSION['dept_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Define arrays to hold the subject count per semester
$subjects_per_semester = [
    '1st Year - 1st Semester' => 0,
    '1st Year - 2nd Semester' => 0,
    '2nd Year - 1st Semester' => 0,
    '2nd Year - 2nd Semester' => 0,
    '3rd Year - 1st Semester' => 0,
    '3rd Year - 2nd Semester' => 0,
    '3rd Year - Summer' => 0,
    '4th Year - 1st Semester' => 0,
    '4th Year - 2nd Semester' => 0
];

// Get the department ID from session
$dept_id = $_SESSION['dept_id'];
// Query the database to count subjects per semester
$sql = "SELECT year, semester, COUNT(*) as subject_count 
        FROM subjects WHERE dept_id = ? GROUP BY year, semester";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dept_id);
$stmt->execute();
$query = $stmt->get_result();

while ($row = $query->fetch_assoc()) {
    $year = $row['year']; 
    $semester = $row['semester'];
    
    // Map the result to the correct semester
    $key = "{$year} Year - {$semester} Semester";
    if (isset($subjects_per_semester[$key])) {
        $subjects_per_semester[$key] = $row['subject_count'];
    }
}

// Convert PHP array to JSON for JavaScript
$subjects_data = json_encode(array_values($subjects_per_semester));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.5.min.css">
    <script src="js/jquery.mobile-1.4.5.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: lightgray;
        }
        .main-container {
            background-color: white;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 98%;
            margin: 0 auto;
        }
        .card {
            background: lightgray;
            color: #000;
            margin-bottom: 1rem;
            transition: transform 0.2s;
            cursor: pointer;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .card-body {
            text-align: center;
        }
        .icon i {
            font-size: 3rem;
        }
        .chart-container {
            position: relative;
            height: 50vh;
            width: 90%;
            background: white;
        }
    </style>
</head>
<body>
    <div class="container main-container" style="margin-top:100px;">
        <h3>Welcome, <?php echo $_SESSION['name']; ?>!</h3>
        <div class="container-fluid">
            <div class="row">
                <!-- Reusable Function to Render Cards -->
                <?php
                function renderCard($iconClass, $count, $label, $link) {
                    echo "
                    <div class='col-lg-3'>
                        <div class='card' style='box-shadow: 0 0 5px black;'>
                            <div class='card-body'>
                                <a href='$link' class='icon'>
                                    <i class='$iconClass text-secondary'></i>
                                </a>
                                <h3>$count</h3>
                                <p>$label</p>  
                                <hr>
                                <a class='medium text-secondary stretched-link' href='$link'>View Details</a>
                            </div>
                        </div>              
                    </div>";
                }

                // Fetch data and render the cards
                $cardsData = [
                    ['fa-school', 'Number of Rooms', 'room.php', 'roomlist'],
                    ['fa-user-tie', 'Number of Instructors', 'faculty.php', 'faculty'],
                    ['fa-book-open', 'Number of Subjects', 'subjects.php', 'subjects'],
                    ['fa-graduation-cap', 'Number of Courses', 'courses.php', 'courses']
                ];

                foreach ($cardsData as $data) {
                    [$icon, $label, $link, $table] = $data;
                    $sql = "SELECT * FROM $table WHERE dept_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $dept_id);
                    $stmt->execute();
                    $query = $stmt->get_result();
                    $count = $query->num_rows;
                    renderCard("fa-4x $icon", $count, $label, $link);
                }
                ?>
            </div>

            <!-- Bar Chart Section -->
            <div class="row mt-4">
                <div class="col-lg-7">
                    <div class="card chart-container" style="box-shadow: 0 0 5px black;">
                        <div class="card-header">
                            <h3>Number of Subjects Per Semester</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="subjectsBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function(){
            var subjectsData = <?php echo $subjects_data; ?>;

            var ctxSubjects = document.getElementById('subjectsBarChart').getContext('2d');
            new Chart(ctxSubjects, {
                type: 'bar',
                data: {
                    labels: [
                        '1st Year - 1st Semester', '1st Year - 2nd Semester',
                        '2nd Year - 1st Semester', '2nd Year - 2nd Semester',
                        '3rd Year - 1st Semester', '3rd Year - 2nd Semester',
                        '3rd Year - Summer', '4th Year - 1st Semester',
                        '4th Year - 2nd Semester'
                    ],
                    datasets: [{
                        label: 'Number of Subjects',
                        data: subjectsData,
                        backgroundColor: 'skyblue',
                        borderColor: 'rgba(0, 0, 0, 0)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
