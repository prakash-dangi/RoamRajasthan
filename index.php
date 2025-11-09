<?php
/*
 * Homepage
 * Save as: index.php
 */
$page_title = 'Roam Rajasthan - Welcome';
$root_path = ''; // This page is at the root
include_once 'includes/db.php';
include_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Roam Rajasthan</h1>
        <p>Welcome to the land of kings</p>
    </div>
</section>

<!-- Cities Section (Now Dynamic) -->
<section class="home-section">
    <h2>Explore the Cities</h2>
    <div class="container">
        <div class="grid-container">
            <?php
            try {
                // Fetch first 6 cities
                $stmt = $pdo->query("SELECT * FROM cities LIMIT 6");
                while ($city = $stmt->fetch()) {
                    // Default image if none set
                    $image = !empty($city['image_url']) ? $city['image_url'] : 'images/jaipur/hawaMahal.jpg';
            ?>
                    <a href="pages/cities/city.php?id=<?php echo htmlspecialchars($city['city_id']); ?>" class="card">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($city['city_name']); ?>">
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($city['city_name']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($city['description'], 0, 100)); ?>...</p>
                        </div>
                    </a>
            <?php
                }
            } catch (PDOException $e) {
                echo "<p>Could not load cities. Error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="pages/cities/cities.php" class="btn">View All Cities</a>
        </div>
    </div>
</section>

<?php
include_once 'includes/footer.php';
?>

