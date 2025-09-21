<?php
require_once __DIR__ . '/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (login_user($email, $password)) {
        header('Location: /Pathanto/dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
include __DIR__ . '/header.php';
?>
<main class="auth-page">
    <section class="auth-card" aria-labelledby="login-title">
        <div class="auth-card__header">
            <h1 id="login-title" class="auth-card__title">Welcome back</h1>
            <p class="auth-card__subtitle">Sign in to continue your personalised learning journey.</p>
        </div>
        <?php if ($error): ?>
        <div class="auth-card__message auth-card__message--error">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        <form method="post" class="auth-form" novalidate>
            <div class="auth-form__group">
                <label class="auth-form__label" for="email">Email address</label>
                <input class="auth-form__input" type="email" name="email" id="email" autocomplete="email"
                    placeholder="you@example.com" required>
            </div>
            <div class="auth-form__group">
                <label class="auth-form__label" for="password">Password</label>
                <input class="auth-form__input" type="password" name="password" id="password"
                    autocomplete="current-password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="auth-form__button">Sign in</button>
        </form>
        <p class="auth-card__footer">Need an account? <a href="/Pathanto/register.php">Create one</a></p>
    </section>
</main>
<?php include __DIR__ . '/footer.php'; ?>
