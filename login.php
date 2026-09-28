<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    // Helper: compares a submitted password against a stored one,
    // supporting both plain-text and MD5-hashed storage.
    function passwordMatches($submitted, $stored) {
        if (empty($stored)) {
            return false;
        }
        $is_md5 = (bool) preg_match('/^[a-f0-9]{32}$/i', $stored);
        return $is_md5 ? (md5($submitted) === $stored) : ($submitted === $stored);
    }

    $logged_in = false;

    // 1. Check the admins table first
    $admin_query = mysqli_query($con, "SELECT * FROM admins WHERE username = '$username'");
    if (mysqli_num_rows($admin_query) > 0) {
        $admin = mysqli_fetch_assoc($admin_query);

        if (passwordMatches($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];

            header('Location: admin/dashboard.php');
            exit();
        } else {
            $error = "Invalid password";
        }
        $logged_in = true;
    }

    // 2. If not an admin, check the faculty table
    if (!$logged_in) {
        $faculty_query = mysqli_query($con, "SELECT * FROM faculty WHERE username = '$username'");
        if (mysqli_num_rows($faculty_query) > 0) {
            $faculty = mysqli_fetch_assoc($faculty_query);

            if (passwordMatches($password, $faculty['password'])) {
                $_SESSION['faculty_logged_in'] = true;
                $_SESSION['faculty_id'] = $faculty['id'];
                $_SESSION['faculty_username'] = $faculty['username'];
                $_SESSION['faculty_name'] = $faculty['name'];

                header('Location: faculty/dashboard.php');
                exit();
            } else {
                $error = "Invalid password";
            }
            $logged_in = true;
        }
    }

    // 3. If not admin or faculty, check the subadmins table
    if (!$logged_in) {
        $subadmin_query = mysqli_query($con, "SELECT * FROM subadmins WHERE username = '$username'");
        if (mysqli_num_rows($subadmin_query) > 0) {
            $subadmin = mysqli_fetch_assoc($subadmin_query);

            if (passwordMatches($password, $subadmin['password'])) {
                $_SESSION['subadmin_logged_in'] = true;
                $_SESSION['subadmin_id'] = $subadmin['id'];
                $_SESSION['subadmin_username'] = $subadmin['username'];
                $_SESSION['subadmin_name'] = $subadmin['name'];
                $_SESSION['subadmin_profile'] = $subadmin['profile'];

                header('Location: subadmin/dashboard.php');
                exit();
            } else {
                $error = "Invalid password";
            }
            $logged_in = true;
        }
    }

    // 4. No matching username found in any table
    if (!$logged_in) {
        $error = "Invalid username";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - College Management System</title>
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

        .login-wrapper {
            display: flex;
            max-width: 800px;
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .info-panel {
            flex: 1;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(34, 211, 238, 0.15);
            border-right: none;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-panel i {
            font-size: 2.5rem;
            color: #22d3ee;
            margin-bottom: 20px;
        }

        .info-panel h2 {
            color: #f1f5f9;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .info-panel p {
            color: #94a3b8;
            line-height: 1.7;
            margin: 0;
        }

        .form-panel {
            flex: 1;
            background: #1e293b;
            border: 1px solid rgba(34, 211, 238, 0.15);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-panel h3 {
            color: #f1f5f9;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .form-panel .subtitle {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 25px;
        }

        .form-panel label {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 6px;
        }

        .form-panel .form-control {
            background: #0f172a;
            border: 1px solid #334155;
            color: #f1f5f9;
            padding: 10px 14px;
        }

        .form-panel .form-control:focus {
            background: #0f172a;
            border-color: #22d3ee;
            box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.15);
            color: #f1f5f9;
        }

        .form-panel .form-control::placeholder {
            color: #64748b;
        }

        /* Force dark theme even when Chrome/Edge autofill turns the field white */
        .form-panel .form-control:-webkit-autofill,
        .form-panel .form-control:-webkit-autofill:hover,
        .form-panel .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: #f1f5f9;
            -webkit-box-shadow: 0 0 0px 1000px #0f172a inset;
            box-shadow: 0 0 0px 1000px #0f172a inset;
            transition: background-color 5000s ease-in-out 0s;
            caret-color: #f1f5f9;
        }

        /* Snap Zoom entrance: punches in from slightly smaller + faded, with a quick snappy ease */
        body {
            opacity: 0;
            transform: scale(0.85);
            animation: snapZoomIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        body.fade-out {
            animation: snapZoomOut 0.25s cubic-bezier(0.55, 0, 0.85, 0.35) forwards;
        }

        @keyframes snapZoomIn {
            from { opacity: 0; transform: scale(0.85); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes snapZoomOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(1.15); }
        }

        .password-field {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            cursor: pointer;
        }

        .toggle-password:hover {
            color: #22d3ee;
        }

        .form-panel a {
            color: #22d3ee;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .form-panel a:hover {
            text-decoration: underline;
        }

        .btn-login {
            background: transparent;
            border: 2px solid #22d3ee;
            color: #22d3ee;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 10px;
            transition: background 0.35s ease, color 0.35s ease, box-shadow 0.35s ease, transform 0.2s ease;
        }

        .btn-login:hover {
            background: #22d3ee;
            color: #0f172a;
            box-shadow: 0 0 12px rgba(34, 211, 238, 0.6), 0 0 30px rgba(34, 211, 238, 0.35);
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 0 8px rgba(34, 211, 238, 0.5);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }

        @media (max-width: 767px) {
            .login-wrapper {
                flex-direction: column;
            }
            .info-panel {
                border-right: 1px solid rgba(34, 211, 238, 0.15);
                border-bottom: none;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="info-panel">
            <i class="fas fa-university"></i>
            <h2>College Management System</h2>
            <p>Sign in with your admin or faculty account. You'll be taken to the right dashboard automatically.</p>
        </div>

        <div class="form-panel">
            <h3>Login</h3>
            <p class="subtitle">For admins, faculty, and sub-admins</p>

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

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-field">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        <span class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </span>
                    </div>
                    <div class="text-end mt-2">
                        <a href="admin/forgot-password.php">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                <a href="index.php"><i class="fas fa-home"></i> Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Fixes a blank/stuck page when the browser restores this page
        // from its back/forward cache mid-way through the snap-zoom animation.
        window.addEventListener('pageshow', function (event) {
            document.body.style.opacity = '1';
            document.body.style.transform = 'scale(1)';
        });
    </script>
</body>
</html>