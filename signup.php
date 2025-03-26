<?php
include 'newkonek.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Encrypt password

    // Prevent SQL Injection
    $username = mysqli_real_escape_string($con, $username);

    // Check if username exists
    $check_query = "SELECT * FROM login WHERE username='$username'";
    $result = $con->query($check_query);

    if ($result->num_rows > 0) {
        $error_message = "Username already exists!";
    } else {
        // Insert new user
        $query = "INSERT INTO login (username, password) VALUES ('$username', '$hashed_password')";
        if ($con->query($query)) {
            echo "Signup successful! <a href='login.php'>Login here</a>";
            exit();
        } else {
            echo "Error: " . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
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
        .error-message {
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4">
                    <h3 class="text-center">Signup</h3>
                    <form method="POST" action="signup.php">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                            <?php if (!empty($error_message)) { echo "<span class='error-message'>$error_message</span>"; } ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Signup</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="login.php">Already have an account? Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
