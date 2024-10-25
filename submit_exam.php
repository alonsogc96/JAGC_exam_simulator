<?php
// Include DB connection
include 'db_connect.php';

$examId = isset($_POST['exam_id']) ? intval($_POST['exam_id']) : 0;

if ($examId <= 0) {
    echo "Invalid exam ID.";
    exit();
}

$exam = null;
$totalQuestions = 0;
$correctAnswers = 0;
$submittedAnswers = isset($_POST['answers']) ? $_POST['answers'] : [];

try {
    // Execute query to get the exam
    $sql = "SELECT * FROM exams WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $examId);
    $stmt->execute();
    $examResult = $stmt->get_result();
    
    if ($examResult->num_rows <= 0) {
        echo "Exam not found.";
        exit();
    }
    
    $exam = $examResult->fetch_assoc();
    
    // Execute query to get questions
    $sql = "SELECT * FROM questions WHERE exam_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $examId);
    $stmt->execute();
    $questionsResult = $stmt->get_result();
    
    $totalQuestions = $questionsResult->num_rows;
    
    while ($question = $questionsResult->fetch_assoc()) {
        $questionId = $question['id'];
        $correctOptionIds = [];

        // Get the correct answer for "answer" type questions
        $expectedAnswer = null;
        if ($question['question_type'] === 'answer') {
            $sql = "SELECT answer FROM answers WHERE question_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $questionId);
            $stmt->execute();
            $answerResult = $stmt->get_result();
            
            if ($answerRow = $answerResult->fetch_assoc()) {
                $expectedAnswer = $answerRow['answer'];
            }
        } else {
            // Get correct options for "choice" or "check" type questions
            $sql = "SELECT * FROM options WHERE question_id = ? AND is_correct = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $questionId);
            $stmt->execute();
            $correctOptionsResult = $stmt->get_result();
            
            while ($correctOption = $correctOptionsResult->fetch_assoc()) {
                $correctOptionIds[] = $correctOption['id'];
            }
        }

        // Check if the user's answers are correct
        $userAnswers = isset($submittedAnswers[$questionId]) ? $submittedAnswers[$questionId] : [];

        if ($question['question_type'] === 'answer') {
            // For open-ended questions, check if the user's answer matches the expected answer
            if ($expectedAnswer && strtolower(trim($userAnswers)) === strtolower(trim($expectedAnswer))) {
                $correctAnswers++;
            }
        } else {
            // Ensure userAnswers is always treated as an array
            if (!is_array($userAnswers)) {
                $userAnswers = [$userAnswers];
            }

            // For "choice" or "check" questions
            if (empty(array_diff($correctOptionIds, $userAnswers)) && empty(array_diff($userAnswers, $correctOptionIds))) {
                $correctAnswers++;
            }
        }
    }
    
    // Calculate score percentage
    $scorePercentage = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;
    $passPercentage = $exam['pass_percentage'];
    $status = $scorePercentage >= $passPercentage ? 'PASS' : 'FAIL';
    
    $stmt->close();
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit();
} finally {
    $conn->close();
}

// Redirect to results page with score and status
header('Location: result.php?score=' . urlencode($scorePercentage) . '&status=' . urlencode($status));
exit();
?>
