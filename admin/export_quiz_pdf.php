<?php
include ("config/dbconfig.php");

$database = new Database();
$db = $database->getConnection();

require_once('../TCPDF-main/tcpdf.php');

// Fetch quiz results
$quizResults = [];
$quizResultsQuery = "
    SELECT er.user_id, u.first_name, u.last_name, er.score, er.created_at, c.course AS course, s.subject AS subject 
    FROM exam_result AS er
    JOIN exam_user AS u ON er.user_id = u.id 
    JOIN exam_course AS c ON er.course_id = c.id
    JOIN exam_subject AS s ON er.subject_id = s.id
";
$quizResultsResult = mysqli_query($db, $quizResultsQuery);

if ($quizResultsResult) {
    while ($row = mysqli_fetch_assoc($quizResultsResult)) {
        $quizResults[] = $row;
    }
}
mysqli_close($db); // Close the database connection

// Create new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Quiz Results Report');
$pdf->SetHeaderData('', 0, 'Quiz Results Report', 'Generated on ' . date('Y-m-d H:i:s'));
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Create table header
$html = '<h1>Quiz Results</h1>';
$html .= '<table border="1" cellpadding="4"><thead><tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Course</th>
            <th>Subject</th>
            <th>Score</th>
            <th>Date Taken</th>
          </tr></thead><tbody>';

// Populate table rows with quiz results
if (empty($quizResults)) {
    $html .= '<tr><td colspan="6" class="text-center">No quiz results found</td></tr>';
} else {
    foreach ($quizResults as $result) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($result['user_id']) . '</td>
                    <td>' . htmlspecialchars($result['first_name'] . ' ' . $result['last_name']) . '</td>
                    <td>' . htmlspecialchars($result['course']) . '</td>
                    <td>' . htmlspecialchars($result['subject']) . '</td>
                    <td>' . htmlspecialchars($result['score']) . '</td>
                    <td>' . htmlspecialchars($result['created_at']) . '</td>
                  </tr>';
    }
}

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output the PDF document
$pdf->Output('quiz_results_report.pdf', 'D'); // 'D' forces download
?>
