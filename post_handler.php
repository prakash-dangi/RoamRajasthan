<?php
include_once 'includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit_post'])) {
    $post_text = trim($_POST['post_text']);
    $post_image_url = null;

    if (!empty($_FILES['post_image']['name'])) {
        $upload_dir = 'uploads/posts/';
        if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

        $file_name = uniqid() . '-' . basename($_FILES['post_image']['name']);
        $target_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['post_image']['tmp_name'], $target_path)) {
            $post_image_url = $target_path;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO user_posts (user_id, post_text, post_image_url) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $post_text, $post_image_url]);

    header("Location: profile.php");
    exit;
}
?>
