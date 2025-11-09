<?php
include_once 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit_comment'])) {
    $post_id = intval($_POST['post_id']);
    $comment_text = trim($_POST['comment_text']);

    if ($comment_text !== '') {
        $stmt = $pdo->prepare("INSERT INTO post_comments (post_id, user_id, comment_text) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $user_id, $comment_text]);
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
