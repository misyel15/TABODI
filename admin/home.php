<?php 
include 'db_connect.php'; 
 include 'notif.php';
include 'includes/header.php';
session_start(); // Start the session

// Check if the user is logged in and has a dept_id
if (!isset($_SESSION['username']) || !isset($_SESSION['dept_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
        @media (max-width: 1200px) {
            .main-container {
                padding: 1rem;
            }
        }
        @media (max-width: 992px) {
            .card {
                margin-bottom: 0.5rem;
            }
        }
        @media (max-width: 768px) {
            .col-lg-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .container-fluid {
                padding: 0;
            }
            .card-body {
                padding: 1rem;
            }
        }
        @media (max-width: 576px) {
            .icon i {
                font-size: 2rem;
            }
            .card-body h3 {
                font-size: 1.5rem;
            }
        }
    </style>
    
</head>
<body>
    <div class="container main-container" style="margin-top:100px;">
        <h3 class="my-4"> <p>Welcome, <?php echo $_SESSION['name']; ?>!</p></h3>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card" style="box-shadow: 0 0 5px black;">
                        <div class="card-body">
                            <div class="icon" style="text-align:right;">
                                <i class="fa fa-4x fa-school text-secondary" aria-hidden="true"></i>
                            </div>
                            <?php
                                $dept_id = $_SESSION['dept_id']; // Get the department ID from session
                                $sql = "SELECT * FROM roomlist WHERE dept_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $dept_id); // Bind the dept_id parameter
                                $stmt->execute();
                                $query = $stmt->get_result();
                                $num_rooms = $query->num_rows; // Number of rooms
                                echo "<h3>".$num_rooms."</h3>";
                            ?> 
                            <p>Number of Rooms</p>                
                            <hr>
                            <a class="medium text-secondary stretched-link" href="room.php">View Details</a>
                        </div>
                    </div>              
                </div>
                <div class="col-lg-3">
                    <div class="card" style="box-shadow: 0 0 5px black;">
                        <div class="card-body">
                            <div class="icon" style="text-align:right;">
                                <i class="fa fa-4x fa-user-tie text-secondary" aria-hidden="true"></i>
                            </div>
                            <?php
                                $sql = "SELECT * FROM faculty WHERE dept_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $dept_id);
                                $stmt->execute();
                                $query = $stmt->get_result();
                                $num_instructors = $query->num_rows; // Number of instructors
                                echo "<h3>".$num_instructors."</h3>";
                            ?>
                            <p>Number of Instructors</p>  
                            <hr>
                            <a class="medium text-secondary stretched-link" href="faculty.php">View Details</a>
                        </div>
                    </div>              
                </div>
                <div class="col-lg-3">
                    <div class="card" style="box-shadow: 0 0 5px black;">
                        <div class="card-body">
                            <div class="icon" style="text-align:right;">
                                <i class="fa fa-4x fa-book-open text-secondary" aria-hidden="true"></i>
                            </div>
                            <?php
                                $sql = "SELECT * FROM subjects WHERE dept_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $dept_id);
                                $stmt->execute();
                                $query = $stmt->get_result();
                                $num_subjects = $query->num_rows; // Number of subjects
                                echo "<h3>".$num_subjects."</h3>";
                            ?>
                            <p>Number of Subjects</p>  
                            <hr>
                            <a class="medium text-secondary stretched-link" href="subjects.php">View Details</a>
                        </div>
                    </div>              
                </div>
                <div class="col-lg-3">
                    <div class="card" style="box-shadow: 0 0 5px black;">
                        <div class="card-body">
                            <div class="icon" style="text-align:right;">
                                <i class="fa fa-4x fa-graduation-cap text-secondary" aria-hidden="true"></i>
                            </div>
                            <?php
                                $sql = "SELECT * FROM courses WHERE dept_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $dept_id);
                                $stmt->execute();
                                $query = $stmt->get_result();
                                $num_courses = $query->num_rows; // Number of courses
                                echo "<h3>".$num_courses."</h3>";
                            ?>
                            <p>Number of Courses</p>  
                            <hr>
                            <a class="medium text-secondary stretched-link" href="courses.php">View Details</a>
                        </div>
                    </div>              
                </div>
            </div>
            <!-- Bar Chart Container -->
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
            var ctxSubjects = document.getElementById('subjectsBarChart').getContext('2d');
            var subjectsBarChart = new Chart(ctxSubjects, {
                type: 'bar',
                data: {
                    labels: [
                        '1st Year - 1st Semester', '1st Year - 2nd Semester', 
                        '2nd Year - 1st Semester', '2nd Year - 2nd Semester', 
                        '3rd Year - 1st Semester', '3rd Year - 2nd Semester', '3rd Year - Summer',
                        '4th Year - 1st Semester', '4th Year - 2nd Semester'
                    ],
                    datasets: [{
                        label: 'Number of Subjects',
                        data: [12, 19, 3, 5, 2, 3, 14, 18, 12, 15, 13, 20], // Replace with actual data
                        backgroundColor: 'skyblue',
                        borderColor: 'rgba(0, 0, 0, 0)',
                        borderWidth: 1,
                        fill: true
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
