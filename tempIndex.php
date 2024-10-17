<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <style>
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="dropdown">
        <a href="#" class="dropbtn">Login</a>
        <div class="dropdown-content">
            <a href="login.php">Candidate</a>
            <a href="companyLogin.php">Company</a>
        </div>
    </div>
    <div class="dropdown">
        <a href="#" class="dropbtn">Register</a>
        <div class="dropdown-content">
            <a href="register.php">Candidate</a>
            <a href="companyRegister.php">Company</a>
        </div>
    </div>
    <div class="dropdown">
        <a href="#" class="dropbtn">Dashboard</a>
        <div class="dropdown-content">
            <a href="dashboard.php">Candidate</a>
            <a href="companyDashboard.php">Company</a>
        </div>
    </div>
</div>

</body>
</html>
