<?php
session_start();
include('includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}

if (isset($_POST['login'])) {
    $email = $_POST['emailid'];
    $password = $_POST['password'];

    $sql = "SELECT EmailId, Password, id, Status FROM tblstudents WHERE EmailId = :email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (password_verify($password, $result['Password'])) {
            $_SESSION['stdid'] = $result['id']; // Use 'id' instead of 'StudentId'

            if ($result['Status'] == 'Approved') { // Note the change in the condition
                $_SESSION['login'] = $email;
                header('Location: issued-books.php');
                exit();
            } elseif ($result['Status'] == 'Pending') {
                echo "<script>alert('Your Account is pending approval. Please wait for admin approval');</script>";
            } else {
                echo "<script>alert('Your Account has been rejected. Please contact admin for more information');</script>";
            }
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('User not found. Please check your email');</script>";
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
    <title>Meru University of Science and Technology | </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet" />

</head>
<body>
    <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="content-wrapper">
<div class="container">
<div class="row pad-botm">
<div class="col-md-12">
<h4 class="header-line">STUDENT LOGIN</h4>
</div>
</div>

<!--LOGIN PANEL START-->           
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
<div class="panel panel-info">
<div class="panel-heading">
 LOGIN FORM
</div>
<div class="panel-body">
<form role="form" method="post">

<div class="form-group">
<label>Enter Email</label>
<input class="form-control" type="text" name="emailid" required autocomplete="off" />
</div>
<div class="form-group">
    <label>Password</label>
    <div class="input-group">
        <input class="form-control" type="password" id="password" name="password" required autocomplete="off" />
        <span class="input-group-addon" id="password-toggle">
            <i id="password-icon" class="fa fa-eye"></i>
        </span>
    </div>
    <p class="help-block"><a href="user-forgot-password.php">Forgot Password</a></p>
</div>

<button type="submit" name="login" class="btn btn-info">LOGIN </button> | <a href="signup.php">Not Registered Yet</a>
</form>
</div>
</div>
</div>
</div>  
<!---LOGIN PANEL END-->            

</div>
</div>
<!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
<!-- FOOTER SECTION END-->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS  -->
<script src="assets/js/bootstrap.js"></script>
<!-- CUSTOM SCRIPTS  -->
<script src="assets/js/custom.js"></script>

<script>
document.getElementById("password-toggle").addEventListener("click", function () {
    var passwordInput = document.getElementById("password");
    var passwordIcon = document.getElementById("password-icon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordIcon.classList.remove("fa-eye");
        passwordIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        passwordIcon.classList.remove("fa-eye-slash");
        passwordIcon.classList.add("fa-eye");
    }
});
</script>
</body>
</html>
