<?php
/*
 * ==========================================================
 * Follow Handler - NEW FILE
 * ==========================================================
 * Save as: follow_handler.php (in the root directory)
 * This script processes follow/unfollow requests.
 */
session_start();
include_once 'includes/db.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$follower_id = $_SESSION['user_id'];
$following_id = $_POST['following_id'] ?? null;
$action = $_POST['action'] ?? null;

// Must be a valid request
if (!$following_id || !$action || $follower_id == $following_id) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? 'index.php');
    exit;
}

try {
    if ($action == 'follow') {
        // Insert follow relationship
        $stmt = $pdo->prepare("INSERT IGNORE INTO user_follows (follower_id, following_id) VALUES (?, ?)");
        $stmt->execute([$follower_id, $following_id]);
    } elseif ($action == 'unfollow') {
        // Delete follow relationship
        $stmt = $pdo->prepare("DELETE FROM user_follows WHERE follower_id = ? AND following_id = ?");
        $stmt->execute([$follower_id, $following_id]);
    }
} catch (PDOException $e) {
    // Handle error (e.g., log it)
}

// Redirect back to the user's profile
header('Location: user_profile.php?id=' . $following_id);
exit;
?>
