<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .question {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .options {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Quiz</h1>

    <?php
    session_start();
    echo "User ID: " . $_SESSION['user_id'];

    if (isset($_SESSION['user_id'])) {
        $json = file_get_contents('ai.json');
        $quiz_data = json_decode($json, true);

        function displayQuestions($questions, $section) {
            foreach ($questions as $index => $question) {
                echo '<div class="question">';
                echo ($index + 1) . '. ' . $question['question'];
                echo '</div>';
                echo '<div class="options">';
                foreach ($question['options'] as $option) {
                    echo '<label><input type="radio" name="question_' . $index . '_' . $section . '" value="' . $option . '">' . $option . '</label><br>';
                }
                echo '</div>';
            }
        }

        $total_questions = 0;
        $total_score = 0;

        echo '<h2>Hard</h2>';
        displayQuestions($quiz_data['hard'], 'hard');
        $total_questions += count($quiz_data['hard']);
        $total_score += count($quiz_data['hard']) * 4;

        echo '<h2>Medium-Hard</h2>';
        displayQuestions($quiz_data['medium_hard'], 'medium_hard');
        $total_questions += count($quiz_data['medium_hard']);
        $total_score += count($quiz_data['medium_hard']) * 3;

        echo '<h2>Medium</h2>';
        displayQuestions($quiz_data['medium'], 'medium');
        $total_questions += count($quiz_data['medium']);
        $total_score += count($quiz_data['medium']) * 2;

        echo '<h2>Easy</h2>';
        displayQuestions($quiz_data['easy'], 'easy');
        $total_questions += count($quiz_data['easy']);
        $total_score += count($quiz_data['easy']);

        echo '<button onclick="submitQuiz()">Submit</button>';

    } else {
        header("Location: ../cat1/login.php");
        exit();
    }
    ?>

    <?php
    if (isset($_POST['proficiencyLevel'])) {
        try {
            $proficiencyLevel = $_POST['proficiencyLevel'];
            $scorePercentage = $_POST["scorePercentage"];
            try {
                $pdo = new PDO('mysql:host=localhost:3307;dbname=aicp', 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Error connecting to the database: " . $e->getMessage();
                exit();
            }

            session_start();
            $user_id = $_SESSION['user_id'];

            $stmt = $pdo->prepare("INSERT INTO user_proficiency (id, qs) VALUES (:user_id, :qs) ON DUPLICATE KEY UPDATE qs = VALUES(qs)");
            $stmt2 = $pdo->prepare("INSERT INTO scores (id, qs) VALUES (:user_id, :qsScore) ON DUPLICATE KEY UPDATE qs = VALUES(qs)");

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':qs', $proficiencyLevel);
            $stmt->execute();

            $stmt2->bindParam(':user_id', $user_id);
            $stmt2->bindParam(':qsScore', $scorePercentage);
            $stmt2->execute();

            echo "Data stored successfully";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>

</div>

<script>
    var quizData = <?php echo json_encode($quiz_data); ?>;
    var totalScore = <?php echo $total_score; ?>;

    function submitQuiz() {
        let answers = [];

        Object.keys(quizData).forEach(function(section) {
            quizData[section].forEach(function(question, index) {
                var selectedOption = document.querySelector('input[name="question_' + index + '_' + section + '"]:checked');
                if (selectedOption) {
                    answers.push({
                        "question": question['question'],
                        "selected_answer": selectedOption.value,
                        "correct_answer": question['answer'],
                        "section": section
                    });
                }
            });
        });

        var score = 0;
        answers.forEach(function(answer) {
            if (answer.selected_answer === answer.correct_answer) {
                switch (answer.section) {
                    case 'hard':
                        score += 4;
                        break;
                    case 'medium_hard':
                        score += 3;
                        break;
                    case 'medium':
                        score += 2;
                        break;
                    case 'easy':
                        score += 1;
                        break;
                    default:
                        score += 1;
                }
            }
        });

        let proficiencyLevel;
        if (score > 90) {
            proficiencyLevel = 9;
        } else if (score > 70) {
            proficiencyLevel = 7;
        } else if (score > 50) {
            proficiencyLevel = 5;
        } else if (score > 25) {
            proficiencyLevel = 3;
        } else if (score > 0) {
            proficiencyLevel = 1;
        } else {
            proficiencyLevel = 0;
        }

        var scorePercentage = (score / totalScore) * 100;
        alert('You scored ' + score + ' out of ' + totalScore + '. Your score: ' + scorePercentage.toFixed(2) + '%\nYour proficiency level: ' + proficiencyLevel);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "aiEvaluation.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
            }
        };
        xhr.send("proficiencyLevel=" + proficiencyLevel + "&scorePercentage=" + scorePercentage.toFixed(2));

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    window.location.href = "../cat1/test.php";
                } else {
                    console.error("Error occurred during evaluation:", xhr.status);
                }
            }
        };

    }
</script>
</body>
</html>
