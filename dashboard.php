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
    header("Location: login.php");
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
    <h2>Welcome, <?php echo $name; ?>!</h2>
    <p>Email: <?php echo $email; ?></p>
    <p>This is your dashboard. You can add more content or functionality as needed.</p>
    <a href="logout.php" class="btn">Logout</a>
    <br><br>
    <a href="test.php" class="btn">Test</a>
    <br><br>
    <a href="resumeIntermediate.php" class="btn">Resume</a>
    <br><br>
    <a href="studentProfile.php" class="btn">View Profile</a>
    <br><br>
    <a href="tempIndex.php" class="btn">Home</a>
    <br><br>
    <a href="displayInternships.php" class="btn">View Internships</a>
</div>

</body>
</html>