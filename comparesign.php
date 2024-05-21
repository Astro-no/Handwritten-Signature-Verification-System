<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to validate and sanitize user input
function validateInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set the path to the Python script
    $pythonScript = "signature.py";

    // Retrieve the user input from the form
    $userImagePath = isset($_POST['imagePath']) ? validateInput($_POST['imagePath']) : '';

    // Validate the user input (you may want to add more validation)
    if (empty($userImagePath)) {
        echo "Please enter your signature path";
    } else {
        // Build the command to execute the Python script with the user input
        $command = "python3 $pythonScript \"$userImagePath\" 2>&1";

        // Execute the command and capture the output
        exec($command, $output, $returnCode);

        // Extract the relevant part from the output indicating the signature match
        $matchingOutput = "";
        foreach ($output as $line) {
            if (strpos($line, "matching with the provided signature") !== false) {
                $matchingOutput = $line;
                break;
            }
        }

        // Prepare the output for displaying within the modal with green text color
        $modalOutput = '<span style="color: green;">' . htmlspecialchars($matchingOutput, ENT_QUOTES, 'UTF-8') . '</span>';

        // If no matching output found, display an error message in the modal
        if (empty($matchingOutput)) {
            $modalOutput = '<span style="color: red;">User-provided signature is not matching with the referenced signature.</span>';
        }

        // Print the output and any errors
        echo <<<HTML
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Signature Comparison</title>
                <!-- Add Bootstrap CSS -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
            </head>
            <body>
                <div class="container">
                    <h2 class="mt-4">Signature Verification</h2>
                    <form action="" method="post" class="mt-4">
                        <div class="mb-3">
                            <label for="imagePath" class="form-label">Enter the path of the signature to be compared:</label>
                            <input type="text" class="form-control" name="imagePath" autocomplete="off" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Compare</button>
                    </form>
                </div>

                <!-- Add Bootstrap JS -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var modalOutput = `$modalOutput`;
                        var modalElement = document.getElementById('outputModal');
                        var modalBody = modalElement.querySelector('.modal-body');
                        modalBody.innerHTML = modalOutput;
                        var modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    });
                </script>

                <!-- Modal -->
                <div class="modal fade" id="outputModal" tabindex="-1" aria-labelledby="outputModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="outputModalLabel">Output</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Output will be displayed here -->
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        HTML;

        if ($returnCode !== 0) {
            echo "<p>Error occurred. Return code: $returnCode</p>";
        }
    }
}
?>
