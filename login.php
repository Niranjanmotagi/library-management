<?php
session_start();  // Start the session at the very beginning

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start a session and set user session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the student dashboard
            header("Location: student_dashboard.php");
            exit;  // Make sure no further code is executed
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No account found with this email!";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
  <title>Login Page</title>
  <style>
    /* Reset styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #e587df, #9b4d96);
      overflow: hidden; /* Prevent scrollbars */
    }

    .container {
      display: flex;
      width: 100%;
      height: 100%;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      background: #fff;
      animation: fadeIn 1s ease-in-out;
    }

    .left-section {
      flex: 1;
      padding: 60px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 20px;
      animation: slideInLeft 1s ease-in-out;
    }

    .left-section h1 {
      font-family: 'Arapey', sans-serif;
      font-weight: 300;
      font-size: 3.5rem;
      margin-bottom: 15px;
    }

    .left-section p {
      color: #6f6f6f;
      font-size: 1rem;
    }

    .left-section span{
      color: #9b4d96;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    form label {
      font-size: 0.9rem;
      color: #555;
    }

    form input {
      padding: 12px;
      font-size: 1rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      transition: 0.3s;
    }

    .switch {
      color: #555;
    }

    .switch a {
      color: #9b4d96;
      text-decoration: none;
    }

    .switch a:hover {
      color: #7a3c73;
      text-decoration: underline;
    }

    .btn-primary {
      background: linear-gradient(45deg, #e587df, #9b4d96); /* Purple gradient */
      color: #fff;
      padding: 12px 20px;
      font-size: 1rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-primary:hover {
      background: linear-gradient(45deg, #222831, #000000); /* Purple gradient */
    }

    .right-section {
      flex: 1;
      background: linear-gradient(135deg, #ffffff, rgba(155, 77, 150, 0.3));
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
      animation: slideInRight 1s ease-in-out;
    }

    .right-section::before {
      content: "";
      width: 300px;
      height: 300px;
      background: rgba(229, 135, 223, 0.3);
      position: absolute;
      border-radius: 50%;
      bottom: -50px;
      right: -50px;
      animation: pulse 3s infinite;
    }

    .right-section::after {
      content: "";
      width: 200px;
      height: 200px;
      background: rgba(229, 135, 223, 0.2);
      position: absolute;
      border-radius: 50%;
      top: -50px;
      left: -50px;
      animation: pulse 3s infinite;
    }

    .right-section img {
      max-width: 80%;
      z-index: 1;
      position: relative;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes slideInLeft {
      from {
        transform: translateX(-100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
        opacity: 0.5;
      }
      50% {
        transform: scale(1.2);
        opacity: 0.8;
      }
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .right-section {
        height: 40%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-section">
      <h1><span>&#10042;</span> Welcome back</h1>
      <p>Please enter your details to continue.</p>
      <!-- Update form action to submit to the same page for processing -->
      <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
        
        <button type="submit" class="btn-primary">Sign in</button>
      </form>
      <p class="switch">Don't have an account? <a href="register.php">Sign up</a></p>
    </div>
    <div class="right-section">
      <img src="undraw_studying_re_deca.svg" alt="Illustration">
    </div>
  </div>
</body>
</html>
