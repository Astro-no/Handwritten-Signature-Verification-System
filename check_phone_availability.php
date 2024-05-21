
<?php
include('includes/config.php'); // Include your database configuration file

if (isset($_POST['phone_number'])) {
    $phone_number = $_POST['phone_number'];

    $sql = "SELECT MobileNumber FROM tblstudents WHERE MobileNumber = :phone_number";
    $query = $dbh->prepare($sql);
    $query->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        echo "<span style='color:red'> Phone Number already exists.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'> Phone number is available for registration.</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }
} else {
    echo "Invalid request.";
}
?>

