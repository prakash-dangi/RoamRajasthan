<?php
/*
 * All Itineraries Catalogue
 * Save as: pages/itinerary/itineraries.php
 */
$page_title = 'Plan Your Itinerary';
$root_path = '../../';
include_once '../../includes/db.php';
include_once '../../includes/header.php';
?>

<!-- Catalogue Header -->
<section class="catalogue-header" style="background-image: url('<?php echo $root_path; ?>images/jaipur/AmerFort.jpg');">
    <h1>Plan Your Perfect Trip</h1>
    <p>Curated itineraries for an unforgettable journey.</p>
</section>

<!-- Itinerary Grid -->
<div class="container" style="padding-top: 2rem;">
    <div class="grid-container">
        <?php
        try {
            // Fetch all cities that HAVE an itinerary
            $stmt = $pdo->query("
                SELECT DISTINCT c.city_id, c.city_name, c.image_url 
                FROM cities c
                JOIN itineraries i ON c.city_id = i.city_id
                ORDER BY c.city_name
            ");
            while ($city = $stmt->fetch()) {
                $image = !empty($city['image_url']) ? $city['image_url'] : $root_path . 'images/jaipur/hawaMahal.jpg';
        ?>
                <a href="itinerary.php?city_id=<?php echo htmlspecialchars($city['city_id']); ?>" class="card">
                    <img src="<?php echo $root_path . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($city['city_name']); ?> Itinerary">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($city['city_name']); ?> Itinerary</h3>
                        <p>Click to see curated plans for your trip to <?php echo htmlspecialchars($city['city_name']); ?>.</p>
                    </div>
                </a>
        <?php
            }
        } catch (PDOException $e) {
            echo "<p>Could not load itineraries. Error: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
