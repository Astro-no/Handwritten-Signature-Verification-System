<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    $sid = $_SESSION['stdid'];

    $sql = "SELECT t.StudentId AS Identifier,
               CONCAT(s.FirstName, ' ', s.LastName) AS StudentName,
               s.Gender,
               s.ProgramCourse,
               s.YearOfStudy,
               t.Status
            FROM tblstudents t
            JOIN Students s ON t.StudentId = s.StudentID
            WHERE t.id = :sid AND t.Status = 'Approved'";
    

    try {
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>MERU UNIVERSITY OF SCIENCE AND TECHNOLOGY | Student Dashboard</title>
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
                    <h4 class="header-line">My Profile</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            My Profile
                        </div>
                        <div class="panel-body">
                            <form name="profileForm" method="post">

                                <?php if ($result) { ?>
                                <div class="form-group">
                                    <label>Student ID:</label>
                                    <?php echo htmlentities($result['Identifier']); ?>
                                </div>

                                <div class="form-group">
                                    <label>Full Name:</label>
                                    <?php echo htmlentities($result['StudentName']); ?>
                                </div>

                                <div class="form-group">
                                    <label>Gender:</label>
                                    <?php echo htmlentities($result['Gender']); ?>
                                </div>

                                <div class="form-group">
                                    <label>Program Course:</label>
                                    <?php echo htmlentities($result['ProgramCourse']); ?>
                                </div>

                                <div class="form-group">
                                    <label>Year of Study:</label>
                                    <?php echo htmlentities($result['YearOfStudy']); ?>
                                </div>
                                <?php } else { ?>
                                <div class="form-group">
                                    <p>No student data found.</p>
                                </div>
                                <?php } ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php
}
?>
