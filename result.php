<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Exam Results</h1>
        <p class="text-lg font-semibold">Score: <?php echo htmlspecialchars($_GET['score']); ?>%</p>
        <p class="text-lg font-semibold">Status: <?php echo htmlspecialchars($_GET['status']); ?></p>
        <a href="exam_list.php" class="bg-blue-600 text-white px-4 py-2 rounded mt-4 inline-block">Back to Exam List</a>
    </div>
</body>
</html>
