<?php
include ("config/dbconfig.php");

$database = new Database();
$db = $database->getConnection();

require_once('../TCPDF-main/tcpdf.php');


// Fetch user data
$userExamResults = [];
$examResultsQuery = "
    SELECT id, first_name, last_name, email, created, is_approved
    FROM exam_user";

$examResultsResult = mysqli_query($db, $examResultsQuery);
if ($examResultsResult) {
    while ($row = mysqli_fetch_assoc($examResultsResult)) {
        $userExamResults[] = $row;
    }
}
mysqli_close($db); // Close the database connection

// Create new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('User List Report');
$pdf->SetHeaderData('', 0, 'User List Report', 'Generated on ' . date('Y-m-d H:i:s'));
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Create table header
$html = '<h1>User List</h1>';
$html .= '<table border="1" cellpadding="4"><thead><tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Date</th>
              <th>Status</th>
          </tr></thead><tbody>';

// Populate table rows with quiz results
if (empty($userExamResults)) {
    $html .= '<tr><td colspan="6" class="text-center">No quiz results found</td></tr>';
} else {
    foreach ($userExamResults as $result) {
        $html .= '<tr>
                     <td>' . htmlspecialchars($result['id']) . '</td>
                     <td>' . htmlspecialchars($result['first_name'] . ' ' . $result['last_name']) . '</td>
                     <td>' . htmlspecialchars($result['email']) . '</td>
                     <td>' . htmlspecialchars($result['created']) . '</td>
                     <td>' . htmlspecialchars($result['is_approved']  ? 'APPROVED' : 'PENDING' ) . '</td>
                  </tr>';
    }
}

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output the PDF document
$pdf->Output('user_list_report.pdf', 'D'); // 'D' forces download
?>
