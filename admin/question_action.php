<?php
include 'config/dbconfig.php';
include_once 'class/class_question.php';

$database = new Database();
$db = $database->getConnection();

$questions = new Questions ($db);

if (!empty($_POST['action']) && $_POST['action'] == 'listQuestions') {
    $questions->subjectid = $_POST['subject_id']; // This now matches the class property
    $questions->listQuestions();
}

if (!empty($_POST['action']) && $_POST['action'] == 'getQuestion') {
    $questions->question_id = $_POST["question_id"];
    $questions->getQuestion();
}

if (!empty($_POST['action']) && $_POST['action'] == 'importCSV') {
    if (!empty($_FILES['csv_file']['name'])) {
        $filename = $_FILES['csv_file']['tmp_name'];
        if (($handle = fopen($filename, "r")) !== FALSE) {
            $row = 0; // Track the row number
            $hasError = false; // Flag to track if any errors occurred
            $dataToInsert = []; // Array to hold valid data for insertion

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                // Skip the header row
                if ($row == 1) continue;

                // Check if the expected number of columns is present
                if (count($data) < 6) {
                    echo "Error: Invalid CSV format on row $row!\n";
                    $hasError = true; // Set error flag
                    continue;
                }

                // Map the CSV data to variables
                $subject_id = intval($data[0]); // Read subject_id from the first column
                $question_title = htmlspecialchars(strip_tags($data[1]));
                $answer_option = intval($data[2]); // The index of the correct option
                $options = array_slice($data, 3, 4); // Extract options 1-4

                if($subject_id != $_POST['subject_id']) {
                    echo "ROW #".$row." Error: Invalid Subject ID on CSV File!\n";
                    $hasError = true; // Set error flag
                    continue;
                }

                // Check if the question already exists
                if ($questions->questionExists($subject_id, $question_title)) {
                    echo "ROW #".$row." Error: \nQuestion '$question_title' for subject ID '$subject_id' ALREADY EXISTS!\n";
                    $hasError = true; // Set error flag
                    continue; // Skip to the next row
                }

                // Store valid data for insertion
                $dataToInsert[] = [
                    'subject_id' => $subject_id,
                    'question_title' => $question_title,
                    'answer_option' => $answer_option,
                    'options' => array_combine(range(1, 4), $options)
                ];
            }
            fclose($handle);

            // Only proceed with insertion if no errors occurred
            if (!$hasError) {
                foreach ($dataToInsert as $data) {
                    $questions->subject_id = $data['subject_id']; // Set subject_id
                    $questions->question_title = $data['question_title'];
                    $questions->answer_option = $data['answer_option'];
                    $questions->option = $data['options'];

                    // Insert the question
                    if (!$questions->insert()) {
                        echo "Error inserting question for '{$data['question_title']}'!\n";
                        $hasError = true; // Set error flag
                    }
                }

                // Only echo success message if no errors occurred during insertion
                if (!$hasError) {
                    echo "CSV imported successfully!\n";
                }
            }
        } else {
            echo "Error opening CSV file!";
        }
    } else {
        echo "No file uploaded!";
    }
}


if (!empty($_POST['action']) && $_POST['action'] == 'addQuestions') {
    $questions->subject_id = $_POST["subject_id"];
    $questions->question_title = $_POST["question_title"];
    $options = array();
    for ($count = 1; $count <= 4; $count++) {
        $options[$count] = $_POST['option_title_' . $count];
    }
    $questions->option = $options;
    $questions->answer_option = $_POST["answer_option"];
    echo $questions->insert();
}

if (!empty($_POST['action']) && $_POST['action'] == 'updateQuestions') {
    $questions->question_id = $_POST["id"];
    $questions->question_title = $_POST["question_title"];
    $options = array();
    for ($count = 1; $count <= 4; $count++) {
        $options[$count] = $_POST['option_title_' . $count];
    }
    $questions->option = $options;
    $questions->answer_option = $_POST["answer_option"];
    echo $questions->update();
}


 if (!empty($_POST['action']) && $_POST['action'] == 'deleteQuestions') {
    $questions->question_id = $_POST["id"];
    if ($questions->delete()) {
        echo json_encode(["status" => "success", "message" => "Record deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting record!"]);
    }
} 

?>