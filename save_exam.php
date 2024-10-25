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
        $questionType = $_POST['questions'][$i]['type'];
        $options = isset($_POST['questions'][$i]['options']) ? $_POST['questions'][$i]['options'] : [];
        $correctAnswers = isset($_POST['questions'][$i]['correct_answers']) ? $_POST['questions'][$i]['correct_answers'] : [];

        // Insert question with type
        $sql = "INSERT INTO questions (exam_id, question_text, question_type) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $examId, $questionText, $questionType);

        if ($stmt->execute()) {
            $questionId = $stmt->insert_id;

            // Insert options for choice and check questions
            if ($questionType === 'choice' || $questionType === 'check') {
                if (!empty($options)) { // Ensure options are not empty
                    foreach ($options as $key => $optionText) {
                        $isCorrect = in_array($key, $correctAnswers) ? 1 : 0;

                        $sql = "INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("isi", $questionId, $optionText, $isCorrect);
                        if (!$stmt->execute()) {
                            $response['message'] .= 'Error inserting option: ' . $stmt->error . '. ';
                        }
                    }
                } else {
                    $response['message'] .= 'No options provided for question ' . $i . '. ';
                }
            } elseif ($questionType === 'answer') {
                // Store fill in the blank answer
                $answer = $_POST['questions'][$i]['answer'];
                $sql = "INSERT INTO answers (question_id, answer) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $questionId, $answer);
                if (!$stmt->execute()) {
                    $response['message'] .= 'Error inserting answer for question ' . $i . ': ' . $stmt->error . '. ';
                }
            }
        } else {
            $response['message'] .= 'Error inserting question ' . $i . ': ' . $stmt->error . '. ';
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
