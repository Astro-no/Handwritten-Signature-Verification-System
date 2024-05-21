<?php
session_start();
include('includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['signup'])) {
    // Check if registrationNumber is set and not empty
    if (!isset($_POST['registrationNumber']) || empty($_POST['registrationNumber'])) {
        echo "<script>alert('Registration Number is required');</script>";
    } else {
        // Sanitize and validate user inputs
        $registrationNumber = $_POST['registrationNumber'];

        // Additional validation for the registration number format
        if (!preg_match('/^[A-Za-z]{2,3}\d{3}\/\d{6}\/\d{2}$/', $registrationNumber)) {
            echo "<script>alert('Invalid Registration Number format. Please use a valid format.');</script>";
        } else {
            // Check for duplicate registration number
            $checkDuplicateSql = "SELECT * FROM tblstudents WHERE StudentId = :registrationNumber";
            $checkDuplicateQuery = $dbh->prepare($checkDuplicateSql);
            $checkDuplicateQuery->bindParam(':registrationNumber', $registrationNumber, PDO::PARAM_STR);
            $checkDuplicateQuery->execute();
            $duplicateResult = $checkDuplicateQuery->fetch(PDO::FETCH_ASSOC);

            if ($duplicateResult) {
                echo "<script>alert('Registration Number already exists. Please choose a different registration number.');</script>";
            } else {
                // Continue with the registration process
                $mobileno = filter_var($_POST['mobileno'], FILTER_SANITIZE_NUMBER_INT);
                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'];

                // Generate a secure password hash
                $password = password_hash($password, PASSWORD_DEFAULT);

                if (!is_numeric($mobileno) || !preg_match('/^\d+$/', $mobileno)) {
                    echo "<script>alert('Phone number can only contain numbers.');</script>";
                } else {
                    // Insert user data with 'Pending' approval status
                    $status = 'Pending'; // Change to 'Approved' after admin approval
                    $sql = "INSERT INTO tblstudents(StudentId, MobileNumber, EmailId, Password, Status) VALUES(:registrationNumber, :mobileno, :email, :password, :status)";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':registrationNumber', $registrationNumber, PDO::PARAM_STR);
                    $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
                    $query->bindParam(':email', $email, PDO::PARAM_STR);
                    $query->bindParam(':password', $password, PDO::PARAM_STR);
                    $query->bindParam(':status', $status, PDO::PARAM_STR);
                    $query->execute();
                    $lastInsertId = $dbh->lastInsertId();

                    if ($lastInsertId) {
                        echo '<script>alert("Your Registration was successful. Please wait for admin approval.");</script>';
                    } else {
                        echo "<script>alert('Something went wrong. Please try again');</script>";
                    }
                }
            }
        }
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
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>MERU UNIVERSITY OF SCIENCE AND TECHNOLOGY | Student Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet" />

    <script type="text/javascript">
        function valid() {
            if (document.signup.password.value != document.signup.confirmpassword.value) {
                alert("Password and Confirm Password Field do not match  !!");
                document.signup.confirmpassword.focus();
                return false;
            }
            return true;
        }
    </script>

    <script>
        function checkAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: 'emailid=' + $("#emailid").val(),
                type: "POST",
                success: function (data) {
                    $("#user-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () { }
            });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#mobileno").blur(function () {
                var phone_number = $(this).val();
                if (phone_number !== "") {
                    $.ajax({
                        type: "POST",
                        url: "check_phone_availability.php",
                        data: {
                            phone_number: phone_number
                        },
                        success: function (response) {
                            $("#phone-availability-status").html(response);
                        }
                    });
                }
            });
        });
    </script>
</head>

<body>
    <!-- MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Student Signup</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            SINGUP FORM
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();">
                                <div class="form-group">
                                    <label>Registration Number</label>
                                    <input class="form-control" type="text" name="registrationNumber" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <label>Mobile Number :</label>
                                    <input class="form-control" type="text" name="mobileno" maxlength="10" id="mobileno" autocomplete="off" required onblur="validatePhoneNumber()" />
                                    <span id="phone-availability-status" style="font-size: 12px;"></span>
                                </div>

                                <div class="form-group">
                                    <label>Enter Email</label>
                                    <input class="form-control" type="email" name="email" id="emailid" onBlur="checkAvailability()" autocomplete="off" required />
                                    <span id="user-availability-status" style="font-size:12px;"></span>
                                </div>

                                <div class="form-group">
                                    <label>Enter Password</label>
                                    <div class="input-group">
                                        <input class="form-control" type="password" id="password" name="password" autocomplete="off" title="Password must be at least 8 characters and contain letters and numbers." required pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=!_])[A-Za-z\d@#$%^&+=!_]{8,}$" />
                                        <span class="input-group-addon" id="password-toggle">
                                            <i id="password-icon" class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <div class="input-group">
                                        <input class="form-control" type="password" id="confirm-password" name="confirmpassword" autocomplete="off" required />
                                        <span class="input-group-addon" id="confirm-password-toggle">
                                            <i id="confirm-password-icon" class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <button type="submit" name="signup" class="btn btn-danger" id="submit">Register Now </button>
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
    <script>
        document.getElementById("password-toggle").addEventListener("click", function () {
            togglePasswordVisibility("password", "password-icon");
        });

        document.getElementById("confirm-password-toggle").addEventListener("click", function () {
            togglePasswordVisibility("confirm-password", "confirm-password-icon");
        });

        function togglePasswordVisibility(inputId, iconId) {
            var inputField = document.getElementById(inputId);
            var passwordIcon = document.getElementById(iconId);

            if (inputField.type === "password") {
                inputField.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                inputField.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        }
    </script>
    <script>
        function validatePhoneNumber() {
            var phoneInput = document.getElementById("mobileno");
            var phoneNumber = phoneInput.value;

            if (!/^\d+$/.test(phoneNumber)) {
                alert("Phone number can only contain numbers.");
                phoneInput.value = ''; // Clear the input field
            }
        }
    </script>
</body>
</html>
