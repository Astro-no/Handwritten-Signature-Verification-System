<?php
require_once("includes/config.php");

if (!empty($_POST["studentid"])) {
    $studentid = strtoupper($_POST["studentid"]);

    $sql = "SELECT FirstName, LastName, ProgramCourse, YearOfStudy FROM Students WHERE StudentID = :studentid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            echo "<b>Student Name:</b> " . htmlentities($result->FirstName . ' ' . $result->LastName) . "<br />";
            echo "<b>Program/Course:</b> " . htmlentities($result->ProgramCourse) . "<br />";
            echo "<b>Year of Study:</b> " . htmlentities($result->YearOfStudy) . "<br />";
            echo "<script>$('#submit').prop('disabled', false);</script>";
        }
    } else {
        echo "<span style='color:red'>Invalid Student Id. Please Enter Valid Student Id.</span>";
        echo "<script>$('#submit').prop('disabled', true);</script>";
    }
}
?>
