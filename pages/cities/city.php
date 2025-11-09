<?php
// Save this as 'pages/cities/city_page.php'
// This ONE file replaces all your individual city HTML files (jaipur.html, jodhpur.html, etc.)
$root_path = '../../';
$asset_path = '../../'; // 2 levels up to root
include_once '../../includes/db.php';

// Get the city ID from the URL
$city_id = $_GET['id'] ?? null;
if (!$city_id) {
    header("Location: cities.php"); // Redirect if no ID
    exit;
}

// Fetch city data
$stmt = $pdo->prepare("SELECT * FROM cities WHERE city_id = ?");
$stmt->execute([$city_id]);
$city = $stmt->fetch();

if (!$city) {
    echo "City not found.";
    exit;
}

include_once '../../includes/header.php';
?>

<!-- Dynamic Header -->
<div class="city-header" style="background-image: url('<?php echo $asset_path . htmlspecialchars($city['image_url']); ?>');">
    <h1><?php echo htmlspecialchars($city['city_name']); ?></h1>
    <div class="breadcrumb">
        <a href="<?php echo $asset_path; ?>index.php">Home</a> &gt; 
        <a href="cities.php">Cities</a> &gt; 
        <?php echo htmlspecialchars($city['city_nadme']); ?>
    </div>
</div>

<div class="content-container">
    <!-- City Info Section -->
    <div class="city-section">
        <h2>About <?php echo htmlspecialchars($city['city_name']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($city['description'])); ?></p>
        
        <div class="city-info-grid" style="margin-top: 20px;">
            <div class="info-box">
                <h3><i class="fas fa-plane"></i> How to Reach (Air)</h3>
                <p><?php echo htmlspecialchars($city['air']); ?></p>
            </div>
            <div class="info-box">
                <h3><i class="fas fa-train"></i> How to Reach (Train)</h3>
                <p><?php echo htmlspecialchars($city['train']); ?></p>
            </div>
            <div class="info-box">
                <h3><i class="fas fa-road"></i> How to Reach (Road)</h3>
                <p><?php echo htmlspecialchars($city['road']); ?></p>
            </div>
            <div class="info-box">
                <h3><i class="fas fa-clock"></i> Best Time to Visit</h3>
                <p><?php echo htmlspecialchars($city['best_time']); ?></p>
            </div>
        </div>
    </div>

    <!-- Places to Visit Section -->
    <div class="city-section">
        <h2><i class="fas fa-landmark"></i> Places to Visit</h2>
        <div class="cities-grid">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM places WHERE city_id = ?");
            $stmt->execute([$city_id]);
            while ($place = $stmt->fetch()) {
                echo '<a href="../places/place_page.php?id=' . htmlspecialchars($place['place_id']) . '" class="city-card">';
                echo '<img src="' . $asset_path . htmlspecialchars($place['image_url']) . '" alt="' . htmlspecialchars($place['name']) . '" class="city-image">';
                echo '<div class="city-card-content">';
                echo '<h3>' . htmlspecialchars($place['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($place['type']) . '</p>';
                echo '</div>';
                echo '</a>';
            }
            ?>
        </div>
    </div>
    
    <!-- Food Section -->
    <div class="city-section">
        <h2><i class="fas fa-utensils"></i> Famous Food</h2>
        <div class="cities-grid">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM food WHERE city_id = ?");
            $stmt->execute([$city_id]);
            while ($food = $stmt->fetch()) {
                echo '<div class="city-card">'; // Not a link unless you make a food_page.php
                echo '<img src="' . $asset_path . htmlspecialchars($food['image_url']) . '" alt="' . htmlspecialchars($food['name']) . '">';
                echo '<div class="city-card-content">';
                echo '<h3>' . htmlspecialchars($food['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($food['specialty']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    
    <!-- Other sections (Shopping, Stays, etc.) can be added here in the same way -->

</div>

<?php
include_once '../../includes/footer.php';
?>
