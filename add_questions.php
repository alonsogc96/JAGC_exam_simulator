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
            const typeSelect = document.getElementById(`question-type-${questionNumber}`).value;

            let optionHtml = `
                <div class="flex items-center mt-2" id="option${questionNumber}_${optionCount}-container">
                    <input type="${typeSelect === 'choice' ? 'radio' : 'checkbox'}" id="option${questionNumber}_${optionCount}" name="questions[${questionNumber}][correct_answers][]" value="${optionCount}" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" />
                    <label for="option${questionNumber}_${optionCount}" class="ml-2 text-sm font-medium text-gray-700">Option ${optionCount}</label>
                    <input type="text" id="option${questionNumber}_text_${optionCount}" name="questions[${questionNumber}][options][${optionCount}]" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm ml-2" />
                    <button type="button" onclick="removeOption(${questionNumber}, ${optionCount})" class="ml-2 text-red-500 hover:underline" ${optionCount <= 2 ? 'disabled' : ''}>Remove</button>
                </div>
            `;
            optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
        }

        function removeOption(questionNumber, optionCount) {
            const optionContainer = document.getElementById(`option${questionNumber}_${optionCount}-container`);
            if (optionContainer) {
                optionContainer.remove();
            }
            // Asegurarse de que siempre haya al menos 2 opciones
            const optionsContainer = document.getElementById(`options-container-${questionNumber}`);
            if (optionsContainer.children.length < 2) {
                alert('You must have at least 2 options.');
                addOption(questionNumber); // Añadir una opción de nuevo
            }
        }

        function toggleQuestionType(questionNumber) {
            const typeSelect = document.getElementById(`question-type-${questionNumber}`);
            const optionsContainer = document.getElementById(`options-container-${questionNumber}`);
            const answerContainer = document.getElementById(`answer-container-${questionNumber}`);

            // Ocultar todos los contenedores
            answerContainer.style.display = 'none';

            // Mostrar según el tipo seleccionado
            if (typeSelect.value === 'choice' || typeSelect.value === 'check') {
                optionsContainer.style.display = 'block';
                if (optionsContainer.children.length === 0) {
                    addOption(questionNumber);
                    addOption(questionNumber); // Asegurar que haya al menos 2 opciones
                } else {
                    // Cambiar el tipo de entrada de las opciones existentes
                    Array.from(optionsContainer.children).forEach((option, index) => {
                        const radioInput = option.querySelector('input[type="radio"]');
                        const checkboxInput = option.querySelector('input[type="checkbox"]');
                        const optionTextInput = option.querySelector('input[type="text"]');

                        if (typeSelect.value === 'check') {
                            if (radioInput) {
                                const newCheckbox = document.createElement('input');
                                newCheckbox.type = 'checkbox';
                                newCheckbox.id = `option${questionNumber}_${index + 1}`;
                                newCheckbox.name = `questions[${questionNumber}][correct_answers][]`;
                                newCheckbox.value = index + 1;
                                newCheckbox.classList.add('h-4', 'w-4', 'text-blue-600', 'border-gray-300', 'focus:ring-blue-500');
                                option.replaceChild(newCheckbox, radioInput);
                            }
                        } else if (typeSelect.value === 'choice') {
                            if (checkboxInput) {
                                const newRadio = document.createElement('input');
                                newRadio.type = 'radio';
                                newRadio.id = `option${questionNumber}_${index + 1}`;
                                newRadio.name = `questions[${questionNumber}][correct_answers][]`;
                                newRadio.value = index + 1;
                                newRadio.classList.add('h-4', 'w-4', 'text-blue-600', 'border-gray-300', 'focus:ring-blue-500');
                                option.replaceChild(newRadio, checkboxInput);
                            }
                        }
                    });
                }
            } else if (typeSelect.value === 'answer') {
                answerContainer.style.display = 'block';
                optionsContainer.innerHTML = ''; // Limpiar opciones si hay respuesta
            }
        }

        function validateForm() {
            const questions = document.querySelectorAll('.mb-4');
            for (let questionContainer of questions) {
                const answerContainer = questionContainer.querySelector('#answer-container-' + questionContainer.id.split('-')[1]);
                const answerInput = answerContainer ? answerContainer.querySelector('input[name*="[answer]"]') : null;

                // Solo valida si el contenedor de respuesta es visible
                if (answerContainer && answerContainer.style.display === 'block') {
                    if (answerInput.value.trim() === '') {
                        alert('Please provide an answer for the question.');
                        answerInput.focus();
                        return false; // Evita el envío del formulario
                    }
                }

                const typeSelect = document.getElementById(`question-type-${questionContainer.id.split('-')[1]}`);
                const options = questionContainer.querySelectorAll('[id^="option' + questionContainer.id.split('-')[1] + '_"]');

                if (typeSelect && (typeSelect.value === 'choice' || typeSelect.value === 'check')) {
                    // Verificar que haya al menos 2 opciones
                    const filledOptions = Array.from(options).filter(option => {
                        const optionInput = option.querySelector('input[type="text"]');
                        return optionInput && optionInput.value.trim() !== '';
                    });

                    if (filledOptions.length < 2) {
                        alert('Please provide at least two options for choice/check questions.');
                        return false; // Evita el envío del formulario
                    }

                    // Verificar que al menos una opción esté marcada como correcta
                    const correctAnswers = Array.from(options).some(option => {
                        const checkbox = option.querySelector('input[type="radio"], input[type="checkbox"]');
                        return checkbox && checkbox.checked;
                    });

                    if (!correctAnswers) {
                        alert('Please select at least one correct answer for choice/check questions.');
                        return false; // Evita el envío del formulario
                    }
                }

                // Validar que todas las opciones tengan texto
                for (let option of options) {
                    const optionInput = option.querySelector('input[type="text"]');
                    if (optionInput && optionInput.value.trim() === '') {
                        alert('Please fill in all options.');
                        optionInput.focus();
                        return false; // Evita el envío del formulario
                    }
                }
            }
            return true; // Permite el envío del formulario
        }

        // Función para inicializar el formulario
        function initializeQuestions() {
            const questions = document.querySelectorAll('.mb-4');
            questions.forEach((questionContainer, index) => {
                const questionNumber = index + 1;
                const typeSelect = document.getElementById(`question-type-${questionNumber}`);

                if (typeSelect) { // Verificar que el elemento exista
                    typeSelect.value = 'choice'; // Seleccionar "choice" por defecto
                    toggleQuestionType(questionNumber); // Mostrar las opciones para "choice"
                }
            });
        }
        
        window.onload = initializeQuestions; // Llama a la función al cargar la página
    </script>
</head>
<body>
    <section class="bg-white">
        <div class="max-w-3xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">Add Questions to the Exam</h1> 
            <form action="save_exam.php" method="post" onsubmit="return validateForm();">
                <input type="hidden" name="examName" value="<?php echo htmlspecialchars($_POST['examName']); ?>" />
                <input type="hidden" name="numQuestions" value="<?php echo htmlspecialchars($_POST['numQuestions']); ?>" />
                <input type="hidden" name="passPercentage" value="<?php echo htmlspecialchars($_POST['passPercentage']); ?>" />
                
                <h2 class="text-xl font-semibold mb-4">Exam: <?php echo htmlspecialchars($_POST['examName']); ?> <a href="create_exam.php"><button type="button" class="mt-2 bg-red-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Cancel Exam</button></a></h2>

                <?php
                $numQuestions = intval($_POST['numQuestions']);
                for ($i = 1; $i <= $numQuestions; $i++):
                ?>
                <div class="mb-4" id="question-<?php echo $i; ?>">
                    <label for="question<?php echo $i; ?>" class="block text-sm font-medium text-gray-700">Question <?php echo $i; ?></label>
                    <input type="text" id="question<?php echo $i; ?>" name="questions[<?php echo $i; ?>][question]" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />

                    <label for="question-type-<?php echo $i; ?>" class="block text-sm font-medium text-gray-700 mt-2">Question Type</label>
                    <select id="question-type-<?php echo $i; ?>" name="questions[<?php echo $i; ?>][type]" onchange="toggleQuestionType(<?php echo $i; ?>)" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="choice" selected>Choice</option>
                        <option value="check">Check</option>
                        <option value="answer">Write an answer</option>
                    </select>

                    <div id="options-container-<?php echo $i; ?>" class="mt-2" style="display: none;"></div>

                    <div id="answer-container-<?php echo $i; ?>" class="mt-2" style="display:none;">
                        <label class="text-sm font-medium text-gray-700">Answer:</label>
                        <input type="text" name="questions[<?php echo $i; ?>][answer]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
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
