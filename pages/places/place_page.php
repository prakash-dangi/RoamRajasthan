<?php
/*
 * ==========================================================
 * Place Page - Reviews & Replies (secure & functional)
 * ==========================================================
 */

$root_path = '../../';
$asset_path = '../../';
include_once $root_path . 'includes/db.php';

// --- Safe Session Start ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get logged-in user ID
$user_id = $_SESSION['user_id'] ?? null;
$is_logged_in = !is_null($user_id);

// Get Place ID from URL
$place_id = $_GET['id'] ?? null;
if (!$place_id) {
    header("Location: ../cities/cities.php");
    exit;
}

// --- Handle Form Submissions ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handle Review Submission
    if (isset($_POST['submit_review']) && $is_logged_in && $place_id) {
        $rating = $_POST['rating'] ?? null;
        $review_text = $_POST['review_text'] ?? '';

        try {
            $stmt = $pdo->prepare("INSERT INTO reviews (place_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
            $stmt->execute([$place_id, $user_id, $rating, $review_text]);
            $new_review_id = $pdo->lastInsertId();

            // Handle review photo uploads
            $upload_dir = $root_path . 'uploads/reviews/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

            if (isset($_FILES['review_photos'])) {
                $total_files = count($_FILES['review_photos']['name']);
                for ($i = 0; $i < $total_files; $i++) {
                    if ($_FILES['review_photos']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES['review_photos']['tmp_name'][$i];
                        $file_name = uniqid() . '-' . basename($_FILES['review_photos']['name'][$i]);
                        $upload_path = $upload_dir . $file_name;
                        if (move_uploaded_file($tmp_name, $upload_path)) {
                            $db_path = 'uploads/reviews/' . $file_name;
                            $stmt_photo = $pdo->prepare("INSERT INTO review_photos (review_id, image_url) VALUES (?, ?)");
                            $stmt_photo->execute([$new_review_id, $db_path]);
                        }
                    }
                }
            }

            $_SESSION['message'] = "Review submitted successfully!";
        } catch (Exception $e) {
            $_SESSION['message'] = "Error submitting review: " . $e->getMessage();
        }
    }

    // Handle Reply Submission
    if (isset($_POST['submit_reply']) && $is_logged_in) {
        $review_id = $_POST['review_id'] ?? null;
        $reply_text = $_POST['reply_text'] ?? '';

        if ($review_id) {
            try {
                $stmt = $pdo->prepare("INSERT INTO review_replies (review_id, user_id, reply_text) VALUES (?, ?, ?)");
                $stmt->execute([$review_id, $user_id, $reply_text]);
                $_SESSION['message'] = "Reply posted!";
            } catch (Exception $e) {
                $_SESSION['message'] = "Error posting reply: " . $e->getMessage();
            }
        }
    }

    // PRG Pattern: Redirect to avoid form resubmission
    header("Location: place_page.php?id=" . $place_id . "#reviews-section");
    exit;
}

// --- Fetch Place Info ---
$stmt = $pdo->prepare("SELECT p.*, c.city_name, c.city_id FROM places p JOIN cities c ON p.city_id = c.city_id WHERE p.place_id = ?");
$stmt->execute([$place_id]);
$place = $stmt->fetch();
if (!$place) die("Place not found.");

// --- Fetch Reviews ---
$stmt_reviews = $pdo->prepare("
    SELECT r.*, u.username, u.profile_image_url 
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.place_id = ?
    ORDER BY r.created_at DESC
");
$stmt_reviews->execute([$place_id]);
$reviews = $stmt_reviews->fetchAll();

$page_title = htmlspecialchars($place['name']);
include_once $root_path . 'includes/header.php';
?>

<link rel="stylesheet" href="<?php echo $root_path; ?>css/reviews.css">
<style>
/* STAR RATING */
<style>
/* STAR RATING - Horizontal, normal order */
.star-rating {
    display: flex;
    flex-direction: row-reverse; /* Needed for left-to-right filling */
    font-size: 1.5rem;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    color: #ccc;
    cursor: pointer;
    margin-right: 5px;
}

.star-rating label:before {
    content: "\f005"; /* Font Awesome star */
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
}

/* Highlight stars on hover and checked */
.star-rating input[type="radio"]:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: gold;
}

.review-user-link {
    text-decoration: none;
    color: inherit;
}

.review-user-link:hover strong {
    text-decoration: underline;
    color: #007bff;
}

.review-profile-pic,
.review-profile-pic-small {
    transition: transform 0.2s ease;
}

.review-profile-pic:hover,
.review-profile-pic-small:hover {
    transform: scale(1.05);
}

</style>


<!-- Place Header -->
<div class="city-header" style="background-image: url('<?php echo $root_path . htmlspecialchars($place['image_url']); ?>');">
    <h1><?php echo htmlspecialchars($place['name']); ?></h1>
    <div class="breadcrumb">
        <a href="<?php echo $root_path; ?>index.php">Home</a> &gt; 
        <a href="<?php echo $root_path; ?>pages/cities/cities.php">Cities</a> &gt; 
        <a href="<?php echo $root_path; ?>pages/cities/city_page.php?id=<?php echo $place['city_id']; ?>"><?php echo htmlspecialchars($place['city_name']); ?></a> &gt;
        <?php echo htmlspecialchars($place['name']); ?>
    </div>
</div>

<div class="content-container">
    <div class="city-section">
        <h2>About <?php echo htmlspecialchars($place['name']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($place['description'])); ?></p>
    </div>

    <!-- Reviews Section -->
    <div class="city-section" id="reviews-section">
        <h2>Reviews (<?php echo count($reviews); ?>)</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="session-message"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if ($is_logged_in): ?>
        <!-- Review Form -->
        <div class="review-form-container">
            <h3>Write a Review</h3>
            <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Rating:</label>
                    <div class="star-rating">
                        <!-- Order reversed so 1-star is leftmost -->
                        <input type="radio" id="star5" name="rating" value="5" required><label for="star5"></label>
                        <input type="radio" id="star4" name="rating" value="4"><label for="star4"></label>
                        <input type="radio" id="star3" name="rating" value="3"><label for="star3"></label>
                        <input type="radio" id="star2" name="rating" value="2"><label for="star2"></label>
                        <input type="radio" id="star1" name="rating" value="1"><label for="star1"></label>
                    </div>
                </div>

                </div>
                <div class="form-group">
                    <label>Review:</label>
                    <textarea name="review_text" rows="5" placeholder="Share your experience..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Upload Photos (Optional):</label>
                    <input type="file" name="review_photos[]" multiple accept="image/*">
                </div>
                <button type="submit" name="submit_review" class="cta-button">Submit Review</button>
            </form>
        </div>
        <?php else: ?>
            <p>Please <a href="<?php echo $root_path; ?>login.php">log in</a> to write a review.</p>
        <?php endif; ?>

        <!-- Reviews List -->
        <div class="reviews-list">
            <?php if (empty($reviews)): ?>
                <p>Be the first to review this place!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-author">
                            <a href="<?php echo $root_path; ?>user_profile.php?id=<?php echo $review['user_id']; ?>" class="review-user-link">
                                <img src="<?php echo $root_path . htmlspecialchars($review['profile_image_url'] ?: 'images/default_profile.png'); ?>" 
                                    alt="profile" 
                                    class="review-profile-pic">
                            </a>
                            <div>
                                <a href="<?php echo $root_path; ?>user_profile.php?id=<?php echo $review['user_id']; ?>" class="review-user-link">
                                    <strong><?php echo htmlspecialchars($review['username']); ?></strong>
                                </a>
                                <span class="review-date"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></span>
                            </div>
                        </div>

                        <div class="review-rating">
                            <?php for ($i = 0; $i < $review['rating']; $i++) echo '<i class="fas fa-star"></i>'; ?>
                            <?php for ($i = $review['rating']; $i < 5; $i++) echo '<i class="far fa-star"></i>'; ?>
                        </div>

                        <div class="review-text">
                            <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        </div>

                        <!-- Review Photos -->
                        <?php
                        $stmt_photos = $pdo->prepare("SELECT image_url FROM review_photos WHERE review_id = ?");
                        $stmt_photos->execute([$review['review_id']]);
                        $photos = $stmt_photos->fetchAll();
                        ?>
                        <?php if (!empty($photos)): ?>
                            <div class="review-photos">
                                <?php foreach ($photos as $photo): ?>
                                    <a href="<?php echo $root_path . htmlspecialchars($photo['image_url']); ?>" target="_blank">
                                        <img src="<?php echo $root_path . htmlspecialchars($photo['image_url']); ?>" alt="Review photo">
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Replies -->
                        <div class="review-replies">
                            <?php if ($is_logged_in): ?>
                            <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" method="POST" class="reply-form">
                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                <textarea name="reply_text" rows="2" placeholder="Write your reply..." required></textarea>
                                <button type="submit" name="submit_reply" class="cta-button-small">Post Reply</button>
                            </form>
                            <?php endif; ?>

                            <?php
                            $stmt_replies = $pdo->prepare("
                                SELECT rr.*, u.username, u.profile_image_url, u.user_id
                                FROM review_replies rr
                                JOIN users u ON rr.user_id = u.user_id
                                WHERE rr.review_id = ?
                                ORDER BY rr.created_at ASC
                            ");
                            $stmt_replies->execute([$review['review_id']]);
                            $replies = $stmt_replies->fetchAll();
                            ?>
                            <?php foreach ($replies as $reply): ?>
                                <div class="reply-card">
                                    <div class="review-author">
                                        <a href="<?php echo $root_path; ?>user_profile.php?id=<?php echo $reply['user_id']; ?>" class="review-user-link">
                                            <img src="<?php echo $root_path . htmlspecialchars($reply['profile_image_url'] ?: 'images/default_profile.png'); ?>" 
                                                alt="profile" 
                                                class="review-profile-pic-small">
                                        </a>
                                        <div>
                                            <a href="<?php echo $root_path; ?>user_profile.php?id=<?php echo $reply['user_id']; ?>" class="review-user-link">
                                                <strong><?php echo htmlspecialchars($reply['username']); ?></strong>
                                            </a>
                                            <span class="review-date-small"><?php echo date('F j, Y', strtotime($reply['created_at'])); ?></span>
                                        </div>
                                    </div>
                                    <p><?php echo nl2br(htmlspecialchars($reply['reply_text'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once $root_path . 'includes/footer.php'; ?>
