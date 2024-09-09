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
        
        // Check correct options for this question
        $sql = "SELECT * FROM options WHERE question_id = ? AND is_correct = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $questionId);
        $stmt->execute();
        $correctOptionsResult = $stmt->get_result();
        
        $correctOptionIds = [];
        while ($correctOption = $correctOptionsResult->fetch_assoc()) {
            $correctOptionIds[] = $correctOption['id'];
        }
        
        // Check if the user's answers are correct
        $userAnswers = isset($submittedAnswers[$questionId]) ? $submittedAnswers[$questionId] : [];
        if (empty(array_diff($correctOptionIds, $userAnswers)) && empty(array_diff($userAnswers, $correctOptionIds))) {
            $correctAnswers++;
        }
    }
    
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

header('Location: result.php?score=' . urlencode($scorePercentage) . '&status=' . urlencode($status));
exit();
?>
