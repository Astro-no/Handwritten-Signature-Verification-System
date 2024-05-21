<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors',1);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

    if (isset($_SESSION['msg'])) {
        echo '<script type="text/javascript">alert("' . $_SESSION['msg'] . '");</script>';
        unset($_SESSION['msg']);
    }

    if (isset($_SESSION['error'])) {
        echo '<script type="text/javascript">alert("' . $_SESSION['error'] . '");</script>';
        unset($_SESSION['error']);
    }

    if (isset($_POST['create'])) {
        $studentID = $_POST['studentID'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $dateOfBirth = $_POST['dateOfBirth'];
        $gender = $_POST['gender'];
        $programCourse = $_POST['programCourse'];
        $yearOfStudy = $_POST['yearOfStudy'];

        // Validate Year of Study
        if ($yearOfStudy < 1 || $yearOfStudy > 4) {
            $_SESSION['error_year_of_study'] = "Year of Study should be between 1 and 4.";
            header('location:available_students.php');
            exit();
        }

        $sql = "INSERT INTO Students (StudentID, FirstName, LastName, DateOfBirth, Gender, ProgramCourse, YearOfStudy) VALUES (:studentID, :firstName, :lastName, :dateOfBirth, :gender, :programCourse, :yearOfStudy)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentID', $studentID, PDO::PARAM_STR);
        $query->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $query->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $query->bindParam(':dateOfBirth', $dateOfBirth, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':programCourse', $programCourse, PDO::PARAM_STR);
        $query->bindParam(':yearOfStudy', $yearOfStudy, PDO::PARAM_INT);
        $query->execute();

        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $_SESSION['msg'] = "Student added successfully";
            header('location:available_students.php');
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again";
            header('location:available_students.php');
        }
    }

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MERU UNIVERSITY OF SCIENCE AND TECHNOLOGY | Add Student</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Add Student</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Student Info
                        </div>
                        <div class="panel-body">
                            <!-- Display general error message here -->
                            <?php
                            if (isset($_SESSION['error'])) {
                                ?>
                                <div class="errorWrap">
                                    <strong>Error</strong>: <?php echo htmlentities($_SESSION['error']); ?>
                                </div>
                            <?php
                                unset($_SESSION['error']);
                            }
                            ?>

                            <form role="form" method="post">
                                <!-- ... (other form fields) ... -->
                                <div class="form-group">
                                    <label>Student ID</label>
                                    <input class="form-control" type="text" name="studentID" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input class="form-control" type="text" name="firstName" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input class="form-control" type="text" name="lastName" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input class="form-control" type="date" name="dateOfBirth" required />
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Program/Course</label>
                                    <input class="form-control" type="text" name="programCourse" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <label>Year of Study</label>
                                    <input class="form-control" type="number" name="yearOfStudy" autocomplete="off" required />
                                </div>

                                <!-- Display error message for Year of Study -->
                                <?php
                                if (isset($_SESSION['error_year_of_study'])) {
                                    ?>
                                    <div class="errorWrap">
                                        <strong>Error</strong>: <?php echo htmlentities($_SESSION['error_year_of_study']); ?>
                                    </div>
                                <?php
                                    unset($_SESSION['error_year_of_study']);
                                }
                                ?>

                                <button type="submit" name="create" class="btn btn-info">Add </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->

    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>

</html>

<?php } ?>
