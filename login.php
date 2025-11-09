<?php
/*
 * ==========================================================
 * User Login - UPGRADED
 * ==========================================================
 * This REPLACES your old login.php.
 * It now stores the profile image in the session.
 */
$page_title = 'Login';
$root_path = '';
include_once 'includes/db.php';
include_once 'includes/header.php'; // header.php starts the session

if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit;
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Store user data in session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['profile_image_url'] = $user['profile_image_url']; // STORE IMAGE
        
        header("Location: profile.php");
        exit;
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<div class="auth-form">
    <form action="login.php" method="POST">
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="message error"><?php echo $message; ?></p>
        <?php endif; ?>
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
        <p style="text-align: center; margin-top: 1rem;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </form>
</div>

<?php include_once 'includes/footer.php'; ?>

