<?php
/*
 * All Cities Catalogue
 * Save as: pages/cities/cities.php
 */
$page_title = 'Explore Cities';
$root_path = '../../';
include_once '../../includes/db.php';
include_once '../../includes/header.php';
?>

<!-- Catalogue Header -->
<section class="catalogue-header">
    <h1>Explore Our Cities</h1>
    <p>Discover the best of Rajasthan, one city at a time.</p>
</section>

<!-- City Grid -->
<div class="container" style="padding-top: 2rem;">
    <div class="grid-container">
        <?php
        try {
            // Fetch all cities
            $stmt = $pdo->query("SELECT * FROM cities ORDER BY city_name");
            while ($city = $stmt->fetch()) {
                $image = !empty($city['image_url']) ? $city['image_url'] : $root_path . 'images/jaipur/hawaMahal.jpg';
        ?>
                <a href="city.php?id=<?php echo htmlspecialchars($city['city_id']); ?>" class="card">
                    <img src="<?php echo $root_path . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($city['city_name']); ?>">
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
</div>

<?php include_once '../../includes/footer.php'; ?>

