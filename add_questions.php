<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Add Questions</title>
    <script>
        function addOption(questionNumber) {
            const optionsContainer = document.getElementById(`options-container-${questionNumber}`);
            const optionCount = optionsContainer.children.length + 1;
            const optionHtml = `
                <div class="flex items-center mt-2">
                    <input type="checkbox" id="option${questionNumber}_${optionCount}" name="questions[${questionNumber}][correct_answers][]" value="${optionCount}" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                    <label for="option${questionNumber}_${optionCount}" class="ml-2 text-sm font-medium text-gray-700">Option ${optionCount}</label>
                    <input type="text" id="option${questionNumber}_text_${optionCount}" name="questions[${questionNumber}][options][${optionCount}]" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm ml-2" />
                </div>
            `;
            optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
        }
    </script>
</head>
<body>
    <section class="bg-white">
        <div class="max-w-3xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">Add Questions to the Exam</h1> 
            <form action="save_exam.php" method="post">
                <!-- Hidden inputs for exam data -->
                <input type="hidden" name="examName" value="<?php echo htmlspecialchars($_POST['examName']); ?>" />
                <input type="hidden" name="numQuestions" value="<?php echo htmlspecialchars($_POST['numQuestions']); ?>" />
                <input type="hidden" name="passPercentage" value="<?php echo htmlspecialchars($_POST['passPercentage']); ?>" />
                
                <h2 class="text-xl font-semibold mb-4">Exam: <?php echo htmlspecialchars($_POST['examName']); ?> <a href="create_exam.php"><button type="button" class="mt-2 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Cancel Exam</button></a></h2>

                <?php
                $numQuestions = intval($_POST['numQuestions']);
                for ($i = 1; $i <= $numQuestions; $i++):
                ?>
                <div class="mb-4">
                    <label for="question<?php echo $i; ?>" class="block text-sm font-medium text-gray-700">Question <?php echo $i; ?></label>
                    <input type="text" id="question<?php echo $i; ?>" name="questions[<?php echo $i; ?>][question]" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />

                    <div id="options-container-<?php echo $i; ?>" class="mt-2">
                        <!-- Initially one option -->
                        <div class="flex items-center">
                            <input type="checkbox" id="option<?php echo $i; ?>_1" name="questions[<?php echo $i; ?>][correct_answers][]" value="1" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                            <label for="option<?php echo $i; ?>_1" class="ml-2 text-sm font-medium text-gray-700">Option 1</label>
                            <input type="text" id="option<?php echo $i; ?>_text_1" name="questions[<?php echo $i; ?>][options][1]" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm ml-2" />
                        </div>
                    </div>
                    <button type="button" onclick="addOption(<?php echo $i; ?>)" class="mt-2 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Add Option</button>
                </div>
                <?php endfor; ?>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Save Exam</button>
            </form>
        </div>
    </section>
</body>
</html>
