<?php
require_once __DIR__ . '/auth.php';

$error = '';
$name = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $dbError = '';
    if (register_user($email, $password, $name, $dbError)) {
        login_user($email, $password);
        header('Location: /Pathanto/dashboard.php');
        exit;
    } else {
        $error = 'Registration failed';
        if ($dbError) {
            $error .= ': ' . $dbError;
        }
    }
}
include __DIR__ . '/header.php';
?>
<main class="auth-page">
    <section class="auth-card" aria-labelledby="register-title">
        <div class="auth-card__header">
            <h1 id="register-title" class="auth-card__title">Create your account</h1>
            <p class="auth-card__subtitle">Join Pathanto to unlock tailored study material and progress tracking.</p>
        </div>
        <?php if ($error): ?>
        <div class="auth-card__message auth-card__message--error">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        <form method="post" class="auth-form" novalidate>
            <div class="auth-form__group">
                <label class="auth-form__label" for="name">Full name</label>
                <input class="auth-form__input" type="text" name="name" id="name" autocomplete="name"
                    placeholder="Your name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="auth-form__group">
                <label class="auth-form__label" for="email">Email address</label>
                <input class="auth-form__input" type="email" name="email" id="email" autocomplete="email"
                    placeholder="you@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="auth-form__group">
                <label class="auth-form__label" for="password">Password</label>
                <input class="auth-form__input" type="password" name="password" id="password"
                    autocomplete="new-password" placeholder="Create a password" required>
            </div>
            <button type="submit" class="auth-form__button">Create account</button>
        </form>
        <p class="auth-card__footer">Already have an account? <a href="/Pathanto/login.php">Sign in</a></p>
    </section>
</main>
<?php include __DIR__ . '/footer.php'; ?>
