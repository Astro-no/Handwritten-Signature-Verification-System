<?php
// Define your database connection parameters
$host = 'localhost';
$dbname = 'library';
$username = 'root';
$password = 'x@9V*Wp$6fK2zA!';

// Create a PDO database connection
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

function getBorrowedBooksData() {
    global $dbh;

    $sql = "SELECT
                b.StudentID,
                s.StudentName,
                b.ISBNNumber,
                b.BookName,
                b.IssuesDate,
                b.ReturnDate
            FROM
                tblissuedbookdetails b
            INNER JOIN tblstudents s ON b.StudentID = s.StudentID
            WHERE
                b.ReturnDate < CURDATE() AND
                b.IssuesDate >= CURDATE() - INTERVAL 7 DAY";

    $query = $dbh->prepare($sql);
    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}
?>
