<?php
include('includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errorMessage = '';

if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    try {
        // Fetch registration details from tblstudents
        $sql = "SELECT * FROM tblstudents WHERE id = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $studentId, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Registration exists, fetch student details from Students table
            $registrationNumber = $result['StudentId'];

            // Check if the registration number exists in Students table
            $studentDetailsSql = "SELECT * FROM Students WHERE StudentID = :regNum";
            $studentDetailsQuery = $dbh->prepare($studentDetailsSql);
            $studentDetailsQuery->bindParam(':regNum', $registrationNumber, PDO::PARAM_STR);
            $studentDetailsQuery->execute();
            $studentDetails = $studentDetailsQuery->fetch(PDO::FETCH_ASSOC);

            if ($studentDetails) {
                // Student details found, update tblstudents status to "Approved" and other details
                $updateSql = "UPDATE tblstudents SET Status = 'Approved' WHERE id = :id";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':id', $studentId, PDO::PARAM_STR);
                $updateQuery->execute();

                // Perform any additional actions if needed

                // Display success message in a custom pop-up modal
                $successMessage = "Student Approved successfully!";
                echo "<!DOCTYPE html>
                      <html lang='en'>
                      <head>
                          <meta charset='UTF-8'>
                          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                          <title>Success</title>
                          <style>
                              /* Add your CSS styles for the modal here */
                              .modal {
                                  display: block;
                                  position: fixed;
                                  top: 50%;
                                  left: 50%;
                                  transform: translate(-50%, -50%);
                                  background: #fff;
                                  padding: 20px;
                                  border: 1px solid #ccc;
                              }
                          </style>
                      </head>
                      <body>
                          <!-- Display the success message in a custom pop-up modal -->
                          <div class='modal'>
                              <h2>Success</h2>
                              <p>$successMessage</p>
                              <a href='reg-students.php'>Go Back</a>
                          </div>
                      </body>
                      </html>";
                exit();
            } else {
                // Registration number not found in Students table
                $errorMessage = "Error: Registration number not found";
            }
        } else {
            // Registration not found in tblstudents
            $errorMessage = "Error: Registration not found in tblstudents";
        }
    } catch (PDOException $e) {
        // Handle database errors
        $errorMessage = "Error: " . $e->getMessage();
    }
} else {
    // Invalid request
    $errorMessage = "Invalid request";
}

// Display error message in a custom pop-up modal
echo "<!DOCTYPE html>
      <html lang='en'>
      <head>
          <meta charset='UTF-8'>
          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
          <title>Error</title>
          <style>
              /* Add your CSS styles for the modal here */
              .modal {
                  display: block;
                  position: fixed;
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                  background: #fff;
                  padding: 20px;
                  border: 1px solid #ccc;
              }
          </style>
      </head>
      <body>
          <!-- Display the error message in a custom pop-up modal -->
          <div class='modal'>
              <h2>Error</h2>
              <p>$errorMessage</p>
              <a href='reg_request.php'>Go Back</a>
          </div>
      </body>
      </html>";
exit();
?>
