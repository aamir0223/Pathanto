<?php
require_once __DIR__ . '/auth.php';

$error = '';
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
<div class="auth-container">
    <h1>Register</h1>
    <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Name:<input type="text" name="name"></label>
        <label>Email:<input type="email" name="email" required></label>
        <label>Password:<input type="password" name="password" required></label>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="/Pathanto/login.php">Login</a></p>
</div>
<?php include __DIR__ . '/footer.php'; ?>
