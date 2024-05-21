<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Signature</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Add New Signature</h2>
        <form action="" method="post" class="mt-4">
            <div class="mb-3">
                <label for="imagePath" class="form-label">Enter the path to the signature:</label>
                <input type="text" class="form-control" name="imagePath" autocomplete="off" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Signature</button>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Signature updated successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set the path to the Python script
    $pythonScript = "addsign.py";  // Replace with the actual absolute path

    // Retrieve the user input from the form
    $userImagePath = isset($_POST['imagePath']) ? $_POST['imagePath'] : '';

    // Validate the user input (you may want to add more validation)
    if (empty($userImagePath)) {
        echo "Please enter the path to the signature.";
    } else {
        // Build the command to execute the Python script with the user input
        $command = "python3 $pythonScript \"$userImagePath\" 2>&1";  // Capture both stdout and stderr

        // Execute the command and capture the output
        $output = shell_exec($command);

        // Display the output in the modal
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                    myModal.show();
                });
              </script>";
    }
}
?>
