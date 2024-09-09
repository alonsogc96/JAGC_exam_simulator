<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Create Exam</title>
</head>
<body>
    <section class="bg-white">
        <div class="max-w-3xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">Create a New Exam</h1>
            <form action="add_questions.php" method="post" class="space-y-4">
                <div>
                    <label for="examName" class="block text-sm font-medium text-gray-700">Exam Name</label>
                    <input type="text" id="examName" name="examName" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>
                <div>
                    <label for="numQuestions" class="block text-sm font-medium text-gray-700">Number of Questions</label>
                    <input type="number" id="numQuestions" name="numQuestions" required min="1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>
                <div>
                    <label for="passPercentage" class="block text-sm font-medium text-gray-700">Passing Percentage</label>
                    <input type="number" id="passPercentage" name="passPercentage" required min="0" max="100" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Next</button>
            </form>
        </div>
    </section>
</body>
</html>
