<?php
session_start();

$dataFile = __DIR__ . '/data/users.json';
$errors = [];
$success = '';

if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([], JSON_PRETTY_PRINT), LOCK_EX);
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain uppercase, lowercase, and a number.';
    }

    if ($confirmPassword === '') {
        $errors[] = 'Please confirm your password.';
    } elseif ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if ($phone === '') {
        $errors[] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        $errors[] = 'Please enter a valid phone number.';
    }

    $users = json_decode(file_get_contents($dataFile), true);
    if (!is_array($users)) $users = [];

    foreach ($users as $user) {
        if (strcasecmp($user['email'], $email) === 0) {
            $errors[] = 'That email is already registered.';
            break;
        }
    }

    if (!$errors) {
        $users[] = [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'phone' => $phone,
            'created_at' => date('Y-m-d H:i:s')
        ];

        file_put_contents($dataFile, json_encode($users, JSON_PRETTY_PRINT), LOCK_EX);
        $success = 'Registration successful. You can now log in.';
        $_POST = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Ittybitty Coffee</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="page">
    <div class="page-title">Register</div>
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
                <h1 class="form-title">Sign Up</h1>

                <?php if ($errors): ?>
                    <div class="form-message error">
                        <?= implode('<br>', array_map('e', $errors)) ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="form-message success"><?= e($success) ?></div>
                <?php endif; ?>

                <form method="POST" action="register.php" novalidate>
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
                        <input id="password" type="password" name="password" placeholder="Password" autocomplete="new-password" required>
                        <button class="eye-btn" type="button" onclick="togglePassword('password', this)" aria-label="Show password">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M2 12s3.7-6 10-6 10 6 10 6-3.7 6-10 6S2 12 2 12zm10 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2.2A1.8 1.8 0 1 1 12 10a1.8 1.8 0 0 1 0 3.8z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="field">
                        <span class="left-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M7 10V8a5 5 0 0 1 10 0v2h1a2 2 0 0 1 2 2v8H4v-8a2 2 0 0 1 2-2h1zm2 0h6V8a3 3 0 0 0-6 0v2zm-3 2v6h12v-6H6z"/>
                            </svg>
                        </span>
                        <input id="confirm_password" type="password" name="confirm_password" placeholder="Confirm Password" autocomplete="new-password" required>
                        <button class="eye-btn" type="button" onclick="togglePassword('confirm_password', this)" aria-label="Show password">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M2 12s3.7-6 10-6 10 6 10 6-3.7 6-10 6S2 12 2 12zm10 4a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-2.2A1.8 1.8 0 1 1 12 10a1.8 1.8 0 0 1 0 3.8z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="field">
                        <span class="left-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M6.6 2.5 9.2 2a2 2 0 0 1 2.3 1.4l1.1 4a2 2 0 0 1-.7 2.1l-2 1.5a14 14 0 0 0 3.1 3.1l1.5-2a2 2 0 0 1 2.1-.7l4 1.1A2 2 0 0 1 22 15l-.5 2.7a3 3 0 0 1-3.1 2.5C10.2 19.7 4.3 13.8 3.8 5.6A3 3 0 0 1 6.6 2.5z"/>
                            </svg>
                        </span>
                        <input type="tel" name="phone" placeholder="Phone" autocomplete="tel" required value="<?= e($_POST['phone'] ?? '') ?>">
                    </div>

                    <div class="password-rules">
                        Use at least 8 characters with uppercase, lowercase, and a number.
                    </div>

                    <div class="form-actions">
                        <button class="primary-btn" type="submit">SIGN UP</button>
                    </div>
                </form>

                <div class="bottom-note">
                    Already have an account?<br>
                    <a href="login.php">LOG IN</a>
                </div>
            </div>
        </div>
    </section>
</main>
<script src="script.js"></script>
</body>
</html>
