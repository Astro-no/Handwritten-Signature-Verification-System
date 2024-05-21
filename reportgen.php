<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('library_db.php'); // Include the database connection script

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

// Set document information
$pdf->SetCreator('Your Library System');
$pdf->SetAuthor('Your Library System');
$pdf->SetTitle('Books Borrowed Last Week Report');
$pdf->SetSubject('Books Borrowed Last Week Report');
$pdf->SetKeywords('TCPDF, PDF, PHP, library, report');

// Set the margins
$pdf->SetMargins(15, 25, 15); // left, top, right

// Set the header and footer fonts
$pdf->setHeaderFont(Array('helvetica', '', 12));
$pdf->setFooterFont(Array('helvetica', '', 12));

// Initialize a flag to track the first page
$firstPage = true;

// Fetch the data from the database
$bookData = getBorrowedBooksData();

// Output the fetched data for debugging
var_dump($bookData);

// Initialize a variable to track content height
$contentHeight = 0;

// Define the maximum height for the content on each page
$maxContentHeight = 200;

// Initialize the page count
$pageCount = 1;

// Add a page and set the header content for the first page
$pdf->AddPage();
$pageCount++;

// Set the header content as HTML
$header = '
    <table width="100%">
        <tr>
            <td align="center">
                <img src="assets/img/logo.png" alt="Library Logo" width="100"><br>
                <b>OUR GROUP LIBRARY</b><br>
                Website: www.ourgrouplibrary.com<br>
                Email: info@ourgrouplibrary.com<br>
                Telephone: +254 (020) 123-4567
            </td>
        </tr>
    </table>
';

// Add the header content to the first page
$pdf->writeHTML($header, true, false, true, false, '');

// Set font
$pdf->SetFont('helvetica', '', 12);

// Generate an HTML table for the data with customized styles
$html = '<h1>Books Borrowed Last Week Report</h1>';
$html .= '<table style="border-collapse: collapse; width: 100%;">'; // Apply table styles
$html .= '<tr style="background-color: #333; color: #fff;">'; // Style the header row
//$html .= '<th style="padding: 8px;">Student ID</th>';
$html .= '<th style="padding: 8px;">Student Name</th>';
$html .= '<th style="padding: 8px;">ISBN</th>';
$html .= '<th style="padding: 8px;">Book Name</th>';
$html .= '<th style="padding: 8px;">Issue Date</th>';
$html .= '<th style="padding: 8px;">Return Date</th>';
$html .= '</tr>';

foreach ($bookData as $row) {
    // Check if a new page is needed based on content height
    if ($contentHeight + 40 > $maxContentHeight) {
        $pdf->AddPage();
        $pageCount++;

        // If it's a new page, set the header content
        if ($firstPage) {
            $pdf->writeHTML($header, true, false, true, false, '');
            $firstPage = false;
        }

        // Reset content height
        $contentHeight = 0;
    }

    // Add content to the current page with cell styles
    $html .= '<tr>';
   // $html .= '<td style="padding: 8px;">' . $row['StudentID'] . '</td>';
    $html .= '<td style="padding: 8px;">' . $row['StudentName'] . '</td>';
    $html .= '<td style="padding: 8px;">' . $row['ISBNNumber'] . '</td>';
    $html .= '<td style="padding: 8px;">' . $row['BookName'] . '</td>';
    $html .= '<td style="padding: 8px;">' . $row['IssuesDate'] . '</td>';
    $html .= '<td style="padding: 8px;">' . $row['ReturnDate'] . '</td>';
    $html .= '</tr>';
    
    // Update content height
    $contentHeight += 20;
}

$html .= '</table>';

// Add the content to the current page
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF to the browser
$pdf->Output('books_borrowed_last_week.pdf', 'I');
?>
