<?php 
session_start(); // Start the session
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php'; 
include 'notif.php';
include 'includes/header.php'; 

// Check if the user is logged in and has a dept_id
if (!isset($_SESSION['username']) || !isset($_SESSION['dept_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$dept_id = $_SESSION['dept_id']; // Get the department ID from session
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
        <h3 class="my-4"> <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p></h3>
        <div class="container-fluid">
            <div class="row">
                <?php
                // Create an array of cards for different entities
                $cards = [
                    ['title' => 'Number of Rooms', 'icon' => 'fa-school', 'table' => 'roomlist'],
                    ['title' => 'Number of Instructors', 'icon' => 'fa-user-tie', 'table' => 'faculty'],
                    ['title' => 'Number of Subjects', 'icon' => 'fa-book-open', 'table' => 'subjects'],
                    ['title' => 'Number of Courses', 'icon' => 'fa-graduation-cap', 'table' => 'courses']
                ];
                
                foreach ($cards as $card) {
                    $sql = "SELECT * FROM " . $card['table'] . " WHERE dept_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $dept_id);
                    if ($stmt->execute()) {
                        $query = $stmt->get_result();
                        $num_items = $query->num_rows; // Number of items
                    } else {
                        echo "Error executing query: " . $stmt->error; // Display error if query fails
                        $num_items = 0; // Fallback to 0 if there is an error
                    }
                    ?>
                    <div class="col-lg-3">
                        <div class="card" style="box-shadow: 0 0 5px black;">
                            <div class="card-body">
                                <div class="icon" style="text-align:right;">
                                    <i class="fa fa-4x <?= $card['icon'] ?> text-secondary" aria-hidden="true"></i>
                                </div>
                                <h3><?= $num_items ?></h3>
                                <p><?= $card['title'] ?></p>                
                                <hr>
                                <a class="medium text-secondary stretched-link" href="<?= strtolower(str_replace(' ', '', $card['title'])) ?>.php">View Details</a>
                            </div>
                        </div>              
                    </div>
                    <?php
                }
                ?>
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
                        data: [12, 19, 3, 5, 2, 3, 14, 18, 12], // Replace with actual data if needed
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
