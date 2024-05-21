<?php
session_start();
require_once("includes/config.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$studentName = ""; // Initialize the variable

if (strlen($_SESSION['alogin']) == 0) {
    header('location: index.php');
} else {

    // Check if the issue form is submitted
    if (isset($_POST['issue'])) {
        $studentid = strtoupper($_POST['studentid']);
        $bookid = $_POST['bookdetails'];

        // Check if there are available copies of the book
        $checkAvailableCopiesSql = "SELECT AvailableCopies FROM tblbooks WHERE id=:bookid";
        $checkAvailableCopiesQuery = $dbh->prepare($checkAvailableCopiesSql);
        $checkAvailableCopiesQuery->bindParam(':bookid', $bookid, PDO::PARAM_INT);
        $checkAvailableCopiesQuery->execute();
        $availableCopiesResult = $checkAvailableCopiesQuery->fetch(PDO::FETCH_ASSOC);
        $availableCopies = $availableCopiesResult['AvailableCopies'];

        if ($availableCopies > 0) {
            // If there are available copies, proceed with issuing the book

            // Insert issued book details and deduct available copies
            $sql = "INSERT INTO tblissuedbookdetails(StudentID, BookId, ReturnStatus) VALUES(:studentid, :bookid, 0);
                    UPDATE tblbooks SET AvailableCopies = AvailableCopies - 1 WHERE id = :bookid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $query->bindParam(':bookid', $bookid, PDO::PARAM_INT);
            $query->execute();

            $_SESSION['msg'] = "Book issued successfully";
        } else {
            $_SESSION['error'] = "No available copies of the book";
        }

        header('location: manage-issued-books.php');
    }

    // Fetch student details based on StudentID
    if (!empty($_POST["studentid"])) {
        $studentid = strtoupper($_POST["studentid"]);

        $sql = "SELECT FirstName, LastName, ProgramCourse, YearOfStudy FROM Students WHERE StudentID = :studentid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $studentName = htmlentities($result->FirstName . ' ' . $result->LastName);
                $programCourse = htmlentities($result->ProgramCourse);
                $yearOfStudy = htmlentities($result->YearOfStudy);
            }
        } else {
            $studentName = "";
            $programCourse = "";
            $yearOfStudy = "";
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
    <title>MERU UNIVERSITY OF SCIENCE AND TECHNOLOGY | Issue a new Book</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script>
        // function for get student name
        function getstudent() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_student.php",
                data: 'studentid=' + $("#studentid").val(),
                type: "POST",
                success: function (data) {
                    $("#get_student_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () {}
            });
        }

        // function for book details
        function getbook() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_book.php",
                data: 'bookid=' + $("#bookid").val(),
                type: "POST",
                success: function (data) {
                    $("#get_book_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () {}
            });
        }
    </script>
    <style type="text/css">
        .others {
            color: red;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Issue a New Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Issue a New Book
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <a href="addsignature.php"><button type="submit" class="btn btn-info">Add Signature </button><br><br></a>
                                <a href="comparesign.php"><button type="submit" class="btn btn-info">Verify Signature</button><br><br></a>
                            </div>
                            <!-- Display success message -->
                            <?php
                            if (isset($_SESSION['msg'])) {
                                ?>
                                <div class="alert alert-success">
                                    <?php echo htmlentities($_SESSION['msg']); ?>
                                </div>
                                <?php
                                unset($_SESSION['msg']);
                            }
                            ?>

                            <!-- Display error message -->
                            <?php
                            if (isset($_SESSION['error'])) {
                                ?>
                                <div class="alert alert-danger">
                                    <?php echo htmlentities($_SESSION['error']); ?>
                                </div>
                                <?php
                                unset($_SESSION['error']);
                            }
                            ?>

                            <!-- Display student details -->
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Student id<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="studentid" id="studentid" onBlur="getstudent()" autocomplete="off" required>
                                </div>

                                <div class="form-group">
                                    <span id="get_student_name" style="font-size:16px;"><?php echo $studentName; ?></span>
                                </div>

                                <div class="form-group">
                                    <label>ISBN Number or Book Title<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="bookid" id="bookid" onBlur="getbook()" required="required" autocomplete="off"/>
                                </div>

                                <div class="form-group">
                                    <select class="form-control" name="bookdetails" id="get_book_name" readonly>
                                        <!-- Options for book details -->
                                    </select>
                                </div>
                                <button type="submit" name="issue" id="submit" class="btn btn-info">Issue Book </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>
<?php } ?>
