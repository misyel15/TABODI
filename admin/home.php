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
            display: block; /* Cards are initially visible */
        }
        .card-body {
            text-align: center;
        }
        .icon i {
            font-size: 3rem;
            cursor: pointer;
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
        <h3 class="my-4"> <p>Welcome, <?php echo $_SESSION['name']; ?>!</p></h3>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card" id="roomsCard" style="box-shadow: 0 0 5px black;">
                        <div class="card-body">
                            <div class="icon" style="text-align:right;">
                                <i class="fa fa-4x fa-school text-secondary toggle-visibility" data-target="#roomsCard"></i>
                            </div>
                            <?php
                                $sql = "SELECT * FROM roomlist WHERE dept_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $dept_id);
                                $stmt->execute();
                                $query = $stmt->get_result();
                                $num_rooms = $query->num_rows;
                                echo "<h3>".$num_rooms."</h3>";
                            ?> 
                            <p>Number of Rooms</p>                
                            <hr>
                            <a class="medium text-secondary stretched-link" href="room.php">View Details</a>
                        </div>
                    </div>              
                </div>
                <div class="col-lg-3">
                    <div class="card" id="facultyCard" style="box-shadow: 0 0 5px black;">
                        <div class="card-body">
                            <div class="icon" style="text-align:right;">
                                <i class="fa fa-4x fa-user-tie text-secondary toggle-visibility" data-target="#facultyCard"></i>
                            </div>
                            <?php
                                $sql = "SELECT * FROM faculty WHERE dept_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $dept_id);
                                $stmt->execute();
                                $query = $stmt->get_result();
                                $num_instructors = $query->num_rows;
                                echo "<h3>".$num_instructors."</h3>";
                            ?>
                            <p>Number of Instructors</p>  
                            <hr>
                            <a class="medium text-secondary stretched-link" href="faculty.php">View Details</a>
                        </div>
                    </div>              
                </div>
                <div class="col-lg-3">
                    <div class="card" id="subjectsCard" style="box-shadow: 0 0 5px black;">
                        <div class="card-body">
                            <div class="icon" style="text-align:right;">
                                <i class="fa fa-4x fa-book-open text-secondary toggle-visibility" data-target="#subjectsCard"></i>
                            </div>
                            <?php
                                $sql = "SELECT * FROM subjects WHERE dept_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $dept_id);
                                $stmt->execute();
                                $query = $stmt->get_result();
                                $num_subjects = $query->num_rows;
                                echo "<h3>".$num_subjects."</h3>";
                            ?>
                            <p>Number of Subjects</p>  
                            <hr>
                            <a class="medium text-secondary stretched-link" href="subjects.php">View Details</a>
                        </div>
                    </div>              
                </div>
                <div class="col-lg-3">
                    <div class="card" id="coursesCard" style="box-shadow: 0 0 5px black;">
                        <div class="card-body">
                            <div class="icon" style="text-align:right;">
                                <i class="fa fa-4x fa-graduation-cap text-secondary toggle-visibility" data-target="#coursesCard"></i>
                            </div>
                            <?php
                                $sql = "SELECT * FROM courses WHERE dept_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $dept_id);
                                $stmt->execute();
                                $query = $stmt->get_result();
                                $num_courses = $query->num_rows;
                                echo "<h3>".$num_courses."</h3>";
                            ?>
                            <p>Number of Courses</p>  
                            <hr>
                            <a class="medium text-secondary stretched-link" href="courses.php">View Details</a>
                        </div>
                    </div>              
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add event listener to toggle visibility when icons are clicked
        document.querySelectorAll('.toggle-visibility').forEach(icon => {
            icon.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-target'));
                if (target) {
                    target.style.display = target.style.display === 'none' ? 'block' : 'none';
                }
            });
        });
    </script>
</body>
</html>
