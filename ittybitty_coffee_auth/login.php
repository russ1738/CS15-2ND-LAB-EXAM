<?php
session_start();

$dataFile = __DIR__ . '/data/users.json';
$error = '';
$success = '';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if (isset($_GET['registered'])) {
    $success = 'Registration successful. Please log in.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $users = file_exists($dataFile)
            ? json_decode(file_get_contents($dataFile), true)
            : [];

        if (!is_array($users)) $users = [];

        $matchedUser = null;

        foreach ($users as $user) {
            if (strcasecmp($user['email'], $email) === 0) {
                $matchedUser = $user;
                break;
            }
        }

        if (!$matchedUser || !password_verify($password, $matchedUser['password'])) {
            $error = 'Incorrect email or password.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_email'] = $matchedUser['email'];
            $_SESSION['user_phone'] = $matchedUser['phone'];
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ittybitty Coffee</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="page">
    <div class="page-title">Login</div>
    <section class="auth-shell">
        <div class="background-shape"></div>

        <aside class="brand-panel">
            <div class="brand-welcome">Welcome to</div>
            <svg class="coffee-art" viewBox="0 0 170 220" aria-hidden="true">
                <g fill="#000">
                    <path d="M63 15c-5 11-6 19 0 28 6-10 7-18 0-28zm18-7c-5 11-6 19 0 28 6-10 7-18 0-28zm18 7c-5 11-6 19 0 28 6-10 7-18 0-28z"/>
                    <path d="M35 55h83v76c0 17-14 30-31 30H66c-17 0-31-13-31-30V55zm86 17h8c11 0 20 9 20 20v21c0 12-9 21-20 21h-8v-13h6c4 0 8-4 8-8V92c0-4-4-8-8-8h-6V72z"/>
                    <rect x="83" y="68" width="7" height="67" rx="3.5" fill="#dff3d3"/>
                    <path d="M15 166h122c4 0 7 3 7 7s-3 7-7 7H15c-4 0-7-3-7-7s3-7 7-7z"/>
                </g>
            </svg>
            <div class="brand-name">Ittybitty<br>Coffee !</div>
        </aside>

        <div class="form-side">
            <div class="form-card">
                <h1 class="form-title">Login</h1>

                <?php if ($error): ?>
                    <div class="form-message error"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="form-message success"><?= e($success) ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php" novalidate>
                    <div class="field">
                        <span class="left-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M3 5h18a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm0 3.2V17h18V8.2l-9 5.9-9-5.9zm1.8-1.2L12 12l7.2-5.0H4.8z"/>
                            </svg>
                        </span>
                        <input type="email" name="email" placeholder="Email" autocomplete="email" required value="<?= e($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="field">
                        <span class="left-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M7 10V8a5 5 0 0 1 10 0v2h1a2 2 0 0 1 2 2v8H4v-8a2 2 0 0 1 2-2h1zm2 0h6V8a3 3 0 0 0-6 0v2zm-3 2v6h12v-6H6z"/>
                            </svg>
                        </span>
                        <input id="login_password" type="password" name="password" placeholder="Password" autocomplete="current-password" required>
                        <button class="eye-btn" type="button" onclick="togglePassword('login_password', this)" aria-label="Show password">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M2 12s3.7-6 10-6 10 6 10 6-3.7 6-10 6S2 12 2 12zm10 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2.2A1.8 1.8 0 1 1 12 10a1.8 1.8 0 0 1 0 3.8z"/>
                            </svg>
                        </button>
                    </div>

                    <a href="#" class="form-link" onclick="alert('For this laboratory demo, password recovery is not connected.'); return false;">Forgot Password?</a>

                    <div class="form-actions">
                        <button class="primary-btn" type="submit">LOGIN</button>
                    </div>
                </form>

                <div class="divider"><span>or login with</span></div>

                <div class="socials">
                    <button class="social-btn" type="button" onclick="socialDemo('Google')" aria-label="Login with Google">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M21 12.2c0-.8-.1-1.5-.2-2.2H12v4.2h5.1a4.4 4.4 0 0 1-1.9 2.9v2.4h3.1c1.8-1.7 2.7-4.2 2.7-7.3z"/>
                            <path fill="#34A853" d="M12 21c2.6 0 4.8-.9 6.3-2.5l-3.1-2.4c-.9.6-2 1-3.2 1-2.5 0-4.7-1.7-5.5-4h-3.2v2.5A9.5 9.5 0 0 0 12 21z"/>
                            <path fill="#FBBC05" d="M6.5 13.1c-.2-.6-.3-1.2-.3-1.9s.1-1.3.3-1.9V6.8H3.3A9 9 0 0 0 2.5 11.2c0 1.5.4 3 1.2 4.3l2.8-2.4z"/>
                            <path fill="#EA4335" d="M12 5.3c1.4 0 2.6.5 3.6 1.5l2.7-2.7C16.8 2.7 14.6 1.8 12 1.8a9.5 9.5 0 0 0-8.7 5l3.2 2.5c.8-2.3 3-4 5.5-4z"/>
                        </svg>
                    </button>
                    <button class="social-btn" type="button" onclick="socialDemo('Facebook')" aria-label="Login with Facebook">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" fill="#1877F2"/>
                            <path fill="#fff" d="M13.4 20v-7h2.3l.4-2.7h-2.7V8.6c0-.8.2-1.3 1.3-1.3h1.4V4.9c-.2 0-1-.1-1.9-.1-2 0-3.4 1.2-3.4 3.5v2H8.6V13H11v7h2.4z"/>
                        </svg>
                    </button>
                    <button class="social-btn" type="button" onclick="socialDemo('Apple')" aria-label="Login with Apple">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#111" d="M16.8 12.7c0-2 1.6-3 1.7-3.1-.9-1.3-2.3-1.5-2.8-1.5-1.2-.1-2.3.7-2.9.7-.6 0-1.5-.7-2.5-.7-1.3 0-2.5.8-3.2 2-.1.2-1.1 2 .3 4.5.7 1.2 1.5 2.6 2.7 2.5 1.1 0 1.5-.7 2.8-.7s1.7.7 2.8.7c1.2 0 2-1.2 2.6-2.4.5-1 .7-2 .7-2 0-.1-2.2-.8-2.2-3zm-1.8-5.8c.6-.7 1-1.6.9-2.6-.9 0-1.9.6-2.5 1.2-.5.6-1 1.5-.9 2.4.9.1 1.9-.4 2.5-1z"/>
                        </svg>
                    </button>
                </div>

                <div class="bottom-note">
                    Don't have an account?<br>
                    <a href="register.php">SIGN UP</a>
                </div>
            </div>
        </div>
    </section>
</main>
<script src="script.js"></script>
</body>
</html>
