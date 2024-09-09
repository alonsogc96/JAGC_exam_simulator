<?php
// Include DB connection
include 'db_connect.php';

$examId = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

if ($examId <= 0) {
    echo "Invalid exam ID.";
    exit();
}

// Check the exam and answers
$exam = null;
$questions = [];
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
    
    while ($row = $questionsResult->fetch_assoc()) {
        $questions[] = $row;
    }
    
    //$stmt->close();
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    exit();
} finally {
    //$conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Exam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Exam: <?php echo htmlspecialchars($exam['name']); ?></h1>
        <form action="submit_exam.php" method="post">
            <input type="hidden" name="exam_id" value="<?php echo htmlspecialchars($examId); ?>">
            <?php foreach ($questions as $index => $question): ?>
                <div class="mb-4">
                    <p class="text-lg font-semibold"><?php echo htmlspecialchars($question['question_text']); ?></p>
                    <?php
                    // Check options for this question
                    try {
                        $sql = "SELECT * FROM options WHERE question_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $question['id']);
                        $stmt->execute();
                        $optionsResult = $stmt->get_result();
                        
                        while ($option = $optionsResult->fetch_assoc()): ?>
                            <div class="flex items-center mb-2">
                                <input type="checkbox" name="answers[<?php echo $question['id']; ?>][]" value="<?php echo htmlspecialchars($option['id']); ?>" id="option-<?php echo $option['id']; ?>" class="mr-2">
                                <label for="option-<?php echo $option['id']; ?>" class="text-gray-700"><?php echo htmlspecialchars($option['option_text']); ?></label>
                            </div>
                        <?php endwhile;
                        $stmt->close();
                    } catch (Exception $e) {
                        echo "An error occurred: " . $e->getMessage();
                        exit();
                    }
                    ?>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
        </form>
    </div>
</body>
</html>
