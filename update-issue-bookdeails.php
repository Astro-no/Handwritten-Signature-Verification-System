<?php
session_start();
include('includes/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['return'])) {
        $rid = intval($_GET['rid']);

        // Fetch book details before updating
        $bookDetailsSql = "SELECT BookId, ReturnDate FROM tblissuedbookdetails WHERE id=:rid";
        $bookDetailsQuery = $dbh->prepare($bookDetailsSql);
        $bookDetailsQuery->bindParam(':rid', $rid, PDO::PARAM_STR);
        $bookDetailsQuery->execute();
        $bookDetailsResult = $bookDetailsQuery->fetch(PDO::FETCH_ASSOC);
        $bookId = $bookDetailsResult['BookId'];

         // Calculate fine based on overdue return
        if ($bookDetailsResult['ReturnDate'] == "") {
            $currentTime = date('Y-m-d H:i:s');
            $actualReturnDate = $currentTime;

            // Calculate the difference in minutes
            $diffInMinutes = round((strtotime($currentTime) - strtotime($bookDetailsResult['ReturnDate'])) / 60);

            // Calculate fine (5 ksh for every 2 minutes overdue)
            $fine = max(0, ceil($diffInMinutes / 2) * 5);
        } else {
            $fine = 0; // No fine if the book is returned within the expected time
        }


        // Update issued book status, actual return date, and fine
        $updateIssuedBookSql = "UPDATE tblissuedbookdetails SET fine=:fine, ReturnStatus=1, ReturnDate=:actualReturnDate WHERE id=:rid AND ReturnStatus=0";
        $updateIssuedBookQuery = $dbh->prepare($updateIssuedBookSql);
        $updateIssuedBookQuery->bindParam(':rid', $rid, PDO::PARAM_STR);
        $updateIssuedBookQuery->bindParam(':fine', $fine, PDO::PARAM_INT);
        $updateIssuedBookQuery->bindParam(':actualReturnDate', $actualReturnDate, PDO::PARAM_STR);
        $updateIssuedBookQuery->execute();

        if ($updateIssuedBookQuery->rowCount() > 0) {
            // Increase available copies in tblbooks only if the update was successful
            $increaseAvailableCopiesSql = "UPDATE tblbooks SET AvailableCopies = AvailableCopies + 1 WHERE id=:bookId";
            $increaseAvailableCopiesQuery = $dbh->prepare($increaseAvailableCopiesSql);
            $increaseAvailableCopiesQuery->bindParam(':bookId', $bookId, PDO::PARAM_INT);
            $increaseAvailableCopiesQuery->execute();

            $_SESSION['msg'] = "Book Returned successfully";
        } else {
            $_SESSION['error'] = "Book has already been returned or an error occurred.";
        }

        header('location:manage-issued-books.php');
    }

?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>MERU UNIVERSITY OF SCIENCE AND TECHNOLOGY | Issued Book Details</title>
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
data:'studentid='+$("#studentid").val(),
type: "POST",
success:function(data){
$("#get_student_name").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

//function for book details
function getbook() {
$("#loaderIcon").show();
jQuery.ajax({
url: "get_book.php",
data:'bookid='+$("#bookid").val(),
type: "POST",
success:function(data){
$("#get_book_name").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

</script> 
<style type="text/css">
  .others{
    color:red;
}

</style>


</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wra
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Issued Book Details</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
<div class="panel panel-info">
<div class="panel-heading">
Issued Book Details
</div>
<div class="panel-body">
<form role="form" method="post">
<form role="form" method="post">
        <?php
        $rid = intval($_GET['rid']);
        $sql = "SELECT 
            ib.id as rid,
            CONCAT(s.FirstName, ' ', s.LastName) AS StudentName,
            b.BookName,
            b.ISBNNumber,
            ib.IssuesDate,
            ib.ReturnDate,
            ib.fine
        FROM
            tblissuedbookdetails ib
        JOIN
            Students s ON ib.StudentID = s.StudentID
        JOIN
            tblbooks b ON ib.BookId = b.id
        WHERE
            ib.id = :rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $cnt = 1;
        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                ?>
                    <div class="form-group">
                        <label>Student Name :</label>
                        <?php echo htmlentities($result->StudentName); ?>
                    </div>
                
                    <div class="form-group">
                        <label>Book Name :</label>
                        <?php echo htmlentities($result->BookName); ?>
                    </div>
                
                    <div class="form-group">
                        <label>ISBN :</label>
                        <?php echo htmlentities($result->ISBNNumber); ?>
                    </div>
                
                    <div class="form-group">
                        <label>Book Issued Date :</label>
                        <?php echo htmlentities($result->IssuesDate); ?>
                    </div>
                
                    <div class="form-group">
    <label>Book Returned Date :</label>
    <?php
    if ($result->ReturnDate == "") {
        echo htmlentities("Not Return Yet");
    } else {
        echo htmlentities($result->ReturnDate);
        // If the book has been returned, hide the fine and return button
        continue; // Skip the rest of the loop
    }
    ?>
</div>
<?php
echo "ReturnDate: " . $result->ReturnDate . "<br>";

if (empty($result->ReturnDate)) { ?>
    <form role="form" method="post">
        <div class="form-group">
            <label>Fine (in KSH) :</label>
            <?php
            $fine = 0;
            // Check if the button is clicke
                $issueDate = new DateTime($result->IssuesDate);
                $currentDate = new DateTime();

                // Calculate date difference in minutes
                $interval = $issueDate->diff($currentDate);
                $minutesDiff = $interval->days * 24 * 60;
                $minutesDiff += $interval->h * 60;
                $minutesDiff += $interval->i;

                // Check if within 2 minutes grace period
                if ($minutesDiff > 2) {
                    // Calculate fine after 2 minutes
                    $fineMinutes = $minutesDiff - 2;
                    $fine = ceil($fineMinutes / 2) * 5;
                }
            

            echo htmlentities($fine . ' ksh');
            ?>
        </div>
        <input type="hidden" name="fine" value="<?php echo $fine; ?>">
        <button type="submit" name="return" id="submit" class="btn btn-info">Return Book</button>
    </form>
<?php } else { ?>
    <div class="form-group">
        <label>Fine (in KSH) :</label>
        <?php
        // Display fine details if the book has already been returned
        echo htmlentities($result->fine . ' ksh');
        ?>
    </div>
    <p>The book has already been returned. Fine details are displayed above.</p>
<?php } ?>


                <?php
                }
            }
                ?>
                
</form>
</div>
</div>
</div>
</div>

</div>

</div>
</div>
 <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
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