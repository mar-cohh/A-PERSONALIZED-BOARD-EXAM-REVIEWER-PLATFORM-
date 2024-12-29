<?php
include ("config/dbconfig.php");

$database = new Database();
$db = $database->getConnection();

require_once('../TCPDF-main/tcpdf.php'); // Adjust the path as needed

$sql = " SELECT ua.user_id, u.first_name, u.last_name, u.email, ua.activity_type, ua.activity_time 
            FROM user_activity AS ua 
            JOIN exam_user AS u ON ua.user_id = u.id 
            ORDER BY ua.activity_time DESC";

$result = $db->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
$db->close();

// Create new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('User Activity Report');
$pdf->SetHeaderData('', 0, 'User Activity Report', 'Generated on ' . date('Y-m-d H:i:s'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Create table
$html = '<h1>User Activity</h1>';
$html .= '<table border="1" cellpadding="4"><thead><tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Activity Type</th>
            <th>Activity Time</th>
          </tr></thead><tbody>';

foreach ($data as $activity) {
    $html .= '<tr>
                <td>' . htmlspecialchars($activity['user_id']) . '</td>
                <td>' . htmlspecialchars($activity['first_name'] . ' ' . $activity['last_name']) . '</td>
                <td>' . htmlspecialchars($activity['email']) . '</td>
                <td>' . htmlspecialchars($activity['activity_type']) . '</td>
                <td>' . htmlspecialchars($activity['activity_time']) . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('user_activity_report.pdf', 'D'); // 'D' forces download
