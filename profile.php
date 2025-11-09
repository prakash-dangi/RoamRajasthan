<?php
/*
 * ==========================================================
 * User Profile (My Profile) - UPGRADED FOR COMPATIBILITY
 * ==========================================================
 */
$page_title = 'My Profile';
$root_path = '';
include_once 'includes/db.php';
include_once 'includes/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Protect this page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// --- Handle New Post Creation ---
if (isset($_POST['create_post'])) {
    $post_text = trim($_POST['post_text']);
    $image_path = null;

    if (!empty($post_text)) {
        // Handle optional image upload
        if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/posts/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['post_image']['type'];

            if (in_array($file_type, $allowed_types)) {
                $file_name = uniqid() . '-' . basename($_FILES['post_image']['name']);
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['post_image']['tmp_name'], $target_path)) {
                    $image_path = $target_path;
                }
            }
        }

        // Insert post into DB
        $stmt = $pdo->prepare("INSERT INTO user_posts (user_id, post_text, post_image_url) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $post_text, $image_path]);

        // Show success message
        $message = 'Post created successfully!';
        $message_type = 'success';
    } else {
        $message = 'Post text cannot be empty.';
        $message_type = 'error';
    }
}


// --- Handle Profile Picture Upload ---
if (isset($_POST['upload_pic'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/profiles/';
        if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['profile_image']['type'];

        if (in_array($file_type, $allowed_types)) {
            $file_name = uniqid() . '-' . basename($_FILES['profile_image']['name']);
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_path)) {
                $stmt = $pdo->prepare("UPDATE users SET profile_image_url = ? WHERE user_id = ?");
                $stmt->execute([$target_path, $user_id]);
                $_SESSION['profile_image_url'] = $target_path;

                $message = 'Profile picture updated successfully!';
                $message_type = 'success';
            } else {
                $message = 'Error moving uploaded file.';
                $message_type = 'error';
            }
        } else {
            $message = 'Invalid file type. Please upload JPG, PNG, or GIF.';
            $message_type = 'error';
        }
    } else {
        $message = 'No file uploaded or an error occurred.';
        $message_type = 'error';
    }
}

// --- Fetch user data fresh ---
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// --- Fetch stats ---
$follower_count = $pdo->prepare("SELECT COUNT(*) FROM user_follows WHERE following_id = ?");
$follower_count->execute([$user_id]);
$follower_count = $follower_count->fetchColumn();

$following_count = $pdo->prepare("SELECT COUNT(*) FROM user_follows WHERE follower_id = ?");
$following_count->execute([$user_id]);
$following_count = $following_count->fetchColumn();

$review_count = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ?");
$review_count->execute([$user_id]);
$review_count = $review_count->fetchColumn();
?>

<link rel="stylesheet" href="css/profile.css">
<link rel="stylesheet" href="css/reviews.css">

<div class="container" style="padding-top: 2rem;">

    <?php if ($message): ?>
        <p class="message <?php echo $message_type; ?>"><?php echo $message; ?></p>
    <?php endif; ?>

    <div class="profile-header">
        <img src="<?php echo htmlspecialchars($user['profile_image_url'] ?: 'images/default_profile.png'); ?>" alt="Profile Picture" class="profile-pic">
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user['username']); ?></h1>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
            <div class="profile-stats">
                <span><strong><?php echo $review_count; ?></strong> Reviews</span>
                <span><strong><?php echo $follower_count; ?></strong> Followers</span>
                <span><strong><?php echo $following_count; ?></strong> Following</span>
            </div>
            <a href="user_profile.php?id=<?php echo $user_id; ?>" class="cta-button">View Public Profile</a>
        </div>
    </div>

    <div class="tabs-container">
        <button class="tab-button active" data-tab-target="#edit-profile">Edit Profile</button>
        <button class="tab-button" data-tab-target="#my-reviews">My Reviews</button>
        <button class="tab-button" data-tab-target="#my-posts">My Posts</button>

    </div>

    <div class="tab-content-container">
        <div id="edit-profile" class="tab-content active">
            <div class="content-section">
                <h2>Edit Profile</h2>
                <form action="profile.php" method="POST" enctype="multipart/form-data" class="profile-pic-form auth-form">
                    <h3>Update Profile Picture</h3>
                    <div class="form-group">
                        <label for="profile_image">Upload new image:</label>
                        <input type="file" name="profile_image" id="profile_image" required>
                    </div>
                    <button type="submit" name="upload_pic" class="cta-button">Upload Picture</button>
                </form>
                <!-- Optional: Bio editing can be added here -->
            </div>
        </div>

        <div id="my-reviews" class="tab-content">
            <div class="content-section">
                <h2>My Reviews</h2>
                <div class="reviews-list">
                    <?php
                    $stmt_reviews = $pdo->prepare("
                        SELECT r.*, p.name AS place_name, p.place_id 
                        FROM reviews r
                        JOIN places p ON r.place_id = p.place_id
                        WHERE r.user_id = ? ORDER BY r.created_at DESC
                    ");
                    $stmt_reviews->execute([$user_id]);

                    if ($stmt_reviews->rowCount() == 0) {
                        echo "<p>You have not written any reviews yet.</p>";
                    }

                    while($review = $stmt_reviews->fetch()):
                    ?>
                    <div class="review-card">
                        <div class="review-author">
                            <img src="<?php echo htmlspecialchars($user['profile_image_url'] ?: 'images/default_profile.png'); ?>" alt="profile" class="review-profile-pic">
                            <div>
                                <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                <span class="review-date"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="review-rating">
                            <?php for ($i = 0; $i < $review['rating']; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                            <?php for ($i = $review['rating']; $i < 5; $i++): ?><i class="far fa-star"></i><?php endfor; ?>
                        </div>
                        <div class="review-text">
                            <p><strong>Review for: <a href="pages/places/place_page.php?id=<?php echo $review['place_id']; ?>"><?php echo htmlspecialchars($review['place_name']); ?></a></strong></p>
                            <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div id="my-posts" class="tab-content">
            <div class="content-section">
                <h2>My Posts</h2>

                <!-- ✅ Add Post Form -->
                <form action="profile.php" method="POST" enctype="multipart/form-data" class="post-form">
                    <textarea name="post_text" placeholder="Write something..." required></textarea>
                    <input type="file" name="post_image" accept="image/*">
                    <button type="submit" name="create_post" class="cta-button">Post</button>
                </form>

                <div class="posts-list">
                    <?php
                    $stmt_posts = $pdo->prepare("SELECT * FROM user_posts WHERE user_id = ? ORDER BY created_at DESC");
                    $stmt_posts->execute([$user_id]);

                    if ($stmt_posts->rowCount() == 0) {
                        echo "<p>You haven't created any posts yet.</p>";
                    }

                    while ($post = $stmt_posts->fetch()):
                    ?>
                    <div class="post-card">
                        <?php if (!empty($post['post_image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($post['post_image_url']); ?>" alt="Post Image" class="post-image">
                        <?php endif; ?>
                        <p><?php echo nl2br(htmlspecialchars($post['post_text'])); ?></p>
                        <small>Posted on <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></small>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.tabTarget);
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(tc => tc.classList.remove('active'));
            this.classList.add('active');
            if (target) target.classList.add('active');
        });
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>
