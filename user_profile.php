<?php
/*
 * ==========================================================
 * Public User Profile Page - NEW FILE
 * ==========================================================
 */
$root_path = '';
include_once $root_path . 'includes/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Get logged-in user ID
$logged_in_user_id = $_SESSION['user_id'] ?? null;

// 1. Get User ID from URL
$user_id_to_view = $_GET['id'] ?? null;

if (!$user_id_to_view) {
    include_once 'includes/header.php';
    echo "<div class='container'><p>No user specified.</p></div>";
    include_once 'includes/footer.php';
    exit;
}

if (!$user_id_to_view == $logged_in_user_id) {
    header('Location: profile.php');
    exit;
}

// 2. Fetch User Data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id_to_view]);
    $user = $stmt->fetch();
    if (!$user) throw new Exception("User not found.");

    $page_title = htmlspecialchars($user['username']) . "'s Profile";

    // 3. Fetch stats
    $follower_count = $pdo->prepare("SELECT COUNT(*) FROM user_follows WHERE following_id = ?");
    $follower_count->execute([$user_id_to_view]);
    $follower_count = $follower_count->fetchColumn();

    $following_count = $pdo->prepare("SELECT COUNT(*) FROM user_follows WHERE follower_id = ?");
    $following_count->execute([$user_id_to_view]);
    $following_count = $following_count->fetchColumn();

    $review_count = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ?");
    $review_count->execute([$user_id_to_view]);
    $review_count = $review_count->fetchColumn();

    // 4. Check if logged-in user is following
    $is_following = false;
    if ($logged_in_user_id) {
        $stmt_follow = $pdo->prepare("SELECT * FROM user_follows WHERE follower_id = ? AND following_id = ?");
        $stmt_follow->execute([$logged_in_user_id, $user_id_to_view]);
        if ($stmt_follow->rowCount() > 0) $is_following = true;
    }

} catch (Exception $e) {
    include_once 'includes/header.php';
    echo "<div class='container'><p>Error: " . htmlspecialchars($e->getMessage()) . "</p></div>";
    include_once 'includes/footer.php';
    exit;
}

include_once 'includes/header.php';
?>

<div class="container" style="padding-top: 2rem;">
    <div class="profile-header">
        <img src="<?php echo htmlspecialchars($user['profile_image_url'] ?: 'images/default_profile.png'); ?>" alt="Profile Picture" class="profile-pic">
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user['username']); ?></h1>
            <p><?php echo htmlspecialchars($user['bio']); ?></p>
            <div class="profile-stats">
                <span><strong><?php echo $review_count; ?></strong> Reviews</span>
                <span><strong><?php echo $follower_count; ?></strong> Followers</span>
                <span><strong><?php echo $following_count; ?></strong> Following</span>
            </div>

            <?php if ($logged_in_user_id): ?>
                <form action="follow_handler.php" method="POST">
                    <input type="hidden" name="following_id" value="<?php echo $user_id_to_view; ?>">
                    <button type="submit" name="action" value="<?php echo $is_following ? 'unfollow' : 'follow'; ?>" class="cta-button">
                        <?php echo $is_following ? 'Unfollow' : 'Follow'; ?>
                    </button>
                </form>
            <?php else: ?>
                <p><a href="login.php">Log in</a> to follow this user.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="tabs-container">
    <button class="tab-button active" data-tab-target="#user-posts">Posts</button>
    <button class="tab-button" data-tab-target="#user-reviews">Reviews</button>
</div>

<div class="tab-content-container">
    <div id="user-reviews" class="tab-content">
        <div class="content-section">
            <h2><?php echo htmlspecialchars($user['username']); ?>'s Reviews</h2>
            <div class="reviews-list">
                <?php
                $stmt_reviews = $pdo->prepare("
                    SELECT r.*, p.name AS place_name 
                    FROM reviews r
                    JOIN places p ON r.place_id = p.place_id
                    WHERE r.user_id = ? ORDER BY r.created_at DESC
                ");
                $stmt_reviews->execute([$user_id_to_view]);

                if ($stmt_reviews->rowCount() == 0) {
                    echo "<p>" . htmlspecialchars($user['username']) . " has not written any reviews yet.</p>";
                }

                while ($review = $stmt_reviews->fetch()):
                ?>
                <div class="review-card">
                    <div class="review-header">
                        <h3><a href="pages/places/place_page.php?id=<?php echo $review['place_id']; ?>"><?php echo htmlspecialchars($review['place_name']); ?></a></h3>
                        <span class="rating">
                            <?php for ($i=0;$i<$review['rating'];$i++) echo '★'; ?>
                            <?php for ($i=$review['rating'];$i<5;$i++) echo '☆'; ?>
                        </span>
                        <span class="date"><?php echo date('M j, Y', strtotime($review['created_at'])); ?></span>
                    </div>
                    <div class="review-body">
                        <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Make the POSTS section active by default -->
    <div id="user-posts" class="tab-content active">
        <div class="content-section">
            <h2><?php echo htmlspecialchars($user['username']); ?>'s Posts</h2>

            <?php
            $stmt_posts = $pdo->prepare("
                SELECT * FROM user_posts
                WHERE user_id = ? ORDER BY created_at DESC
            ");
            $stmt_posts->execute([$user_id_to_view]);

            if ($stmt_posts->rowCount() == 0) {
                echo "<p>No posts yet.</p>";
            }

            while ($post = $stmt_posts->fetch()):
            ?>
            <div class="post-card">
                <div class="post-header">
                    <img src="<?php echo htmlspecialchars($user['profile_image_url']); ?>" class="review-profile-pic">
                    <div>
                        <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                        <span class="review-date"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                    </div>
                </div>
                <div class="post-body">
                    <p><?php echo nl2br(htmlspecialchars($post['post_text'])); ?></p>
                    <?php if (!empty($post['post_image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($post['post_image_url']); ?>" alt="Post Image" class="post-image">
                    <?php endif; ?>
                </div>

                <!-- Comments -->
                <div class="comments-section">
                    <?php
                    $stmt_comments = $pdo->prepare("
                        SELECT c.*, u.username, u.profile_image_url
                        FROM post_comments c
                        JOIN users u ON c.user_id = u.user_id
                        WHERE c.post_id = ? ORDER BY c.created_at ASC
                    ");
                    $stmt_comments->execute([$post['post_id']]);

                    while ($comment = $stmt_comments->fetch()):
                    ?>
                    <div class="comment">
                        <img src="<?php echo htmlspecialchars($comment['profile_image_url']); ?>" class="comment-pic">
                        <div class="comment-body">
                            <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                            <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>

                    <?php if ($logged_in_user_id): ?>
                    <form action="comment_handler.php" method="POST" class="comment-form">
                        <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                        <textarea name="comment_text" rows="2" placeholder="Add a comment..." required></textarea>
                        <button type="submit" name="submit_comment">Comment</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
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
            target.classList.add('active');
        });
    });
});
</script>

<?php include_once 'includes/footer.php'; ?>
