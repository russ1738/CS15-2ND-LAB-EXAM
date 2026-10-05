<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Ittybitty Coffee</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="page">
    <div class="page-title">Home</div>
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
            <div class="form-card dashboard-card">
                <h1 class="form-title">Welcome!</h1>
                <h2><?= e($_SESSION['user_email']) ?></h2>
                <p>You are successfully logged in to Ittybitty Coffee.</p>
                <a class="primary-btn logout-btn" href="logout.php">LOG OUT</a>
            </div>
        </div>
    </section>
</main>
</body>
</html>
