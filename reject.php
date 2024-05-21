<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors',1);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 'Rejected'; // Set the status to 'Rejected' or any other appropriate value

        // Update the status in the database
        $sql = "UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();

        // Redirect back to the registration management page
        header('location:reg-students.php');
    } else {
        // Redirect if 'id' parameter is not set
        header('location:reg-students.php');
    }
}
?>

