<?php
require_once __DIR__ . '/../config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email    = mysqli_real_escape_string($con, $_POST['email']);

    $result = mysqli_query($con, "SELECT * FROM admins WHERE username = '$username' AND email = '$email'");

    if (mysqli_num_rows($result) > 0) {
        // In a production system, generate a reset token here and email a reset link
        // instead of showing this message directly.
        $success = "If those details match our records, reset instructions have been sent to your email.";
    } else {
        $error = "No admin account found with that username and email combination.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - College Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .reset-card {
            background: #1e293b;
            border: 1px solid rgba(34, 211, 238, 0.15);
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            padding: 50px 40px;
            max-width: 420px;
            width: 100%;
        }

        .reset-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .reset-header i {
            font-size: 2.5rem;
            color: #22d3ee;
            margin-bottom: 15px;
        }

        .reset-header h3 {
            color: #f1f5f9;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .reset-header p {
            color: #94a3b8;
            font-size: 0.9rem;
            margin: 0;
        }

        label {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .form-control {
            background: #0f172a;
            border: 1px solid #334155;
            color: #f1f5f9;
            padding: 10px 14px;
        }

        .form-control:focus {
            background: #0f172a;
            border-color: #22d3ee;
            box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.15);
            color: #f1f5f9;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .btn-reset {
            background: transparent;
            border: 2px solid #22d3ee;
            color: #22d3ee;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 10px;
            transition: background 0.35s ease, color 0.35s ease, box-shadow 0.35s ease, transform 0.2s ease;
        }

        .btn-reset:hover {
            background: #22d3ee;
            color: #0f172a;
            box-shadow: 0 0 12px rgba(34, 211, 238, 0.6), 0 0 30px rgba(34, 211, 238, 0.35);
            transform: translateY(-2px);
        }

        .btn-reset:active {
            transform: translateY(0);
            box-shadow: 0 0 8px rgba(34, 211, 238, 0.5);
        }

        .reset-card a {
            color: #22d3ee;
            text-decoration: none;
            font-size: 0.9rem;
            transition: text-shadow 0.25s ease;
            border-radius: 15px;  
            padding: 50px 40px;   
        }

        .reset-card a:hover {
            text-shadow: 0 0 8px rgba(34, 211, 238, 0.6);
            text-decoration: underline;
        }

        .alert-success {
            background: rgba(34, 211, 238, 0.1);
            border: 1px solid rgba(34, 211, 238, 0.4);
            color: #a5f3fc;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }
    </style>
</head>
<body>

    <div class="reset-card">
        <div class="reset-header">
            <i class="fas fa-key"></i>
            <h3>Forgot Password</h3>
            <p>Reset your admin password</p>
        </div>

        <?php if($success): ?>
            <div class="alert alert-success py-2">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-danger py-2">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>

            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <button type="submit" class="btn btn-reset w-100">
                <i class="fas fa-paper-plane"></i> Send Reset Instructions
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="login.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
        <div class="text-center mt-2">
            <a href="../index.php"><i class="fas fa-home"></i> Back to Home</a>
        </div>
    </div>

</body>
</html>