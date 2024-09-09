<?php
// Include DB connection
include 'db_connect.php';

// Initializing response
$response = array('success' => false, 'message' => '');

// Get exam data
$examName = $_POST['examName'];
$numQuestions = intval($_POST['numQuestions']);
$passPercentage = intval($_POST['passPercentage']);

// Insert the exam
$sql = "INSERT INTO exams (name, num_questions, pass_percentage) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $examName, $numQuestions, $passPercentage);

if ($stmt->execute()) {
    $examId = $stmt->insert_id;

    // Insert questions and options
    for ($i = 1; $i <= $numQuestions; $i++) {
        $questionText = $_POST['questions'][$i]['question'];
        $options = $_POST['questions'][$i]['options'];
        $correctAnswers = isset($_POST['questions'][$i]['correct_answers']) ? $_POST['questions'][$i]['correct_answers'] : [];

        // Insert questions
        $sql = "INSERT INTO questions (exam_id, question_text) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $examId, $questionText);

        if ($stmt->execute()) {
            $questionId = $stmt->insert_id;

            // Insert options
            foreach ($options as $key => $optionText) {
                $isCorrect = in_array($key, $correctAnswers) ? 1 : 0;

                $sql = "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isi", $questionId, $optionText, $isCorrect);
                $stmt->execute();
            }
        }
    }
    $response['success'] = true;
    $response['message'] = 'Exam saved successfully!';
} else {
    $response['message'] = 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirect to exam_list.php with parameters
header('Location: exam_list.php?success=' . ($response['success'] ? 'true' : 'false') . '&message=' . urlencode($response['message']));
exit();
?>
