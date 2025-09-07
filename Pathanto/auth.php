<?php
// Basic authentication utilities: user registration, login, and session helpers.
require_once __DIR__ . '/config.php';

// Start a session for tracking logged in users.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the users table exists so registration does not fail on new setups.
$createUsersSql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($createUsersSql)) {
    error_log('Failed creating users table: ' . $conn->error);
}

/**
 * Register a new user with email, password, and optional name. Returns true on
 * success; otherwise false and populates $error with the database error.
 */
function register_user($email, $password, $name = '', &$error = '')
{
    global $conn;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (email, password_hash, name) VALUES (?, ?, ?)');
    if (!$stmt) {
        $error = $conn->error;
        return false;
    }
    $stmt->bind_param('sss', $email, $hash, $name);
    $ok = $stmt->execute();
    if (!$ok) {
        $error = $stmt->error;
    }
    $stmt->close();
    return $ok;
}

/**
 * Attempt to log in a user by email/password.
 */
function login_user($email, $password)
{
    global $conn;
    $stmt = $conn->prepare('SELECT id, password_hash FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        $stmt->close();
        return true;
    }
    $stmt->close();
    return false;
}

/**
 * Destroy the active session.
 */
function logout_user()
{
    $_SESSION = [];
    session_destroy();
}

/**
 * Get the currently logged in user's id, or null.
 */
function current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Require that a user is logged in; otherwise redirect to login page.
 */
function require_login()
{
    if (!current_user_id()) {
        header('Location: /Pathanto/login.php');
        exit;
    }
}
?>
