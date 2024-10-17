<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('config.php');

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $email = $row['email'];
} else {
    echo "User not found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Hello, <?php echo $name; ?>!</h2>
    <p>This is the Test Field. You will be evaluated to check your proficiencies based on a few questions.</p>
    <a href="aiEvaluation.php" class="btn">AI</a>
    <br><br>
    <a href="mlEvaluation.php" class="btn">ML</a>
    <br><br>
    <!-- <a href="ProficiencyEvaluation/dsEvaluation.php" class="btn">DS</a>
    <br><br>
    <a href="ProficiencyEvaluation/app_devEvaluation.php" class="btn">App Development</a>
    <br><br>
    <a href="ProficiencyEvaluation/game_devEvaluation.php" class="btn">Game Development</a>
    <br><br>
    <a href="ProficiencyEvaluation/web_devEvaluation.php" class="btn">Web Development</a>
    <br><br> -->
    <a href="logout.php" class="btn">Logout</a>
    <br><br>
</div>

</body>
</html>
