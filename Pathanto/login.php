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
<div class="auth-container">
    <h1>Login</h1>
    <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Email:<input type="email" name="email" required></label>
        <label>Password:<input type="password" name="password" required></label>
        <button type="submit">Login</button>
    </form>
    <p>Need an account? <a href="/Pathanto/register.php">Register</a></p>
</div>
<?php include __DIR__ . '/footer.php'; ?>
