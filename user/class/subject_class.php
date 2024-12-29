<?php
class Subject {
    private $conn;
    private $examTable = "exam_subject";  // Table for subjects
    private $courseTable = "exam_course"; // Table for courses
    private $table_question = "exam_question";
    private $table_option = "exam_option";
    private $scoreTable = 'exam_result';

    public function __construct($db) {
        $this->conn = $db;
    }

        public function getSubjects($course_id = null) {
            $query = "SELECT exam_subject.id, exam_subject.subject, exam_course.course, exam_subject.course_id 
                    FROM " . $this->examTable . " 
                    INNER JOIN " . $this->courseTable . " ON exam_subject.course_id = exam_course.id";
            
            if ($course_id) {
                $query .= " WHERE exam_subject.course_id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("i", $course_id);
            } else {
                $stmt = $this->conn->prepare($query);
            }

            $stmt->execute();
            return $stmt->get_result();
        }
    
    public function getLeaderboard($course_id) { 
       
        $query = "SELECT u.first_name, u.last_name, SUM(er.score) AS total_score
                  FROM exam_result er
                  JOIN exam_user u ON er.user_id = u.id
                  WHERE er.course_id = ?
                  GROUP BY u.id
                  ORDER BY total_score DESC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        return $stmt->get_result();
    }


    public function getQuestions($subject_id) {
        $query = "SELECT q.id as question_id, q.question, o.option, o.title 
                  FROM " . $this->table_question . " q 
                  JOIN " . $this->table_option . " o ON q.id = o.question_id 
                  WHERE q.subject_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[$row['question_id']]['question'] = $row['question'];
            $questions[$row['question_id']]['options'][] = [
                'option_id' => $row['option'],
                'title' => $row['title']
            ];
        }
        return $questions;
    }
}
?>
