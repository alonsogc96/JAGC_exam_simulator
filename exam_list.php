<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exams List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Modal for success/error messages -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-4">Exams List</h1>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number of Questions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pass Percentage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                // Include DB connection
                include 'db_connect.php';

                // Get exams
                $sql = "SELECT * FROM exams";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row["num_questions"]) . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . htmlspecialchars($row["pass_percentage"]) . "%</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'><a href='take_exam.php?exam_id=" . htmlspecialchars($row["id"]) . "' class='text-blue-600 hover:text-blue-900'>Take Exam</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='px-6 py-4 text-center text-gray-500'>No exams found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Show pop-up if there is a message in the URL  Mostrar el popup si hay un mensaje en la URL
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('messageModal');
            var span = document.getElementsByClassName('close')[0];
            var message = document.getElementById('modalMessage');

            // Get URL parameters
            var urlParams = new URLSearchParams(window.location.search);
            var success = urlParams.get('success');
            var msg = urlParams.get('message');

            if (success) {
                message.textContent = msg;
                modal.style.display = 'block';
                
                setTimeout(function () {
                    window.location.href = 'exam_list.php'; // Redirect after displaying the message
                }, 3000);
            }

            // Close pop-up
            span.onclick = function () {
                modal.style.display = 'none';
                window.location.href = 'exam_list.php'; // Redirect to close
            }
            
            window.onclick = function (event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                    window.location.href = 'exam_list.php'; // Redirect on click away
                }
            }
        });
    </script>
</body>
</html>
