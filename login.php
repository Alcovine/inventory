<?php
session_start();
include 'newkonek.php';

$usernameError = $passwordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL Injection
    $username = mysqli_real_escape_string($con, $username);

    // Get user from database
    $query = "SELECT * FROM login WHERE username='$username'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: fordisplay.php");
            exit();
        } else {
            $passwordError = "Wrong password!";
        }
    } else {
        $usernameError = "User doesn't exist!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-custom {
            background: #6a11cb;
            color: white;
            border-radius: 10px;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: #2575fc;
        }
        .error-text {
            color: red;
            font-size: 14px;
            margin-left: 10px;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4">
                    <h3 class="text-center">Login</h3>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                            <span class="error-text"><?= $usernameError; ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                            <span class="error-text"><?= $passwordError; ?></span>
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="signup.php">Don't have an account? Sign up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
