<?php
/*
 * Dynamic Itinerary Template
 * Save as: pages/itinerary/itinerary.php
 * This one file replaces jaipur-itinerary.html, etc.
 * Access via: itinerary.php?city_id=JPR001
 */
$root_path = '../../';
include_once '../../includes/db.php';

// 1. Get City ID from URL
$city_id = $_GET['city_id'] ?? null;
if (!$city_id) {
    header("Location: itineraries.php");
    exit;
}

// 2. Fetch City & Itinerary Data
try {
    $stmt_city = $pdo->prepare("SELECT city_name, image_url FROM cities WHERE city_id = ?");
    $stmt_city->execute([$city_id]);
    $city = $stmt_city->fetch();
    
    if (!$city) {
        throw new Exception("City not found.");
    }
    
    // Fetch all itinerary days for this city
    $stmt_days = $pdo->prepare("SELECT * FROM itineraries WHERE city_id = ? ORDER BY duration");
    $stmt_days->execute([$city_id]);
    $all_days = $stmt_days->fetchAll();

    if (empty($all_days)) {
        throw new Exception("No itinerary found for this city.");
    }

} catch (Exception $e) {
    $page_title = 'Error';
    include_once $root_path . 'includes/header.php';
    echo "<div class='container'><p>Error: " . $e->getMessage() . "</p></div>";
    include_once $root_path . 'includes/footer.php';
    exit;
}

// 3. Set page title and include header
$page_title = htmlspecialchars($city['city_name']) . ' Itinerary - Roam Rajasthan';
include_once $root_path . 'includes/header.php';

// 4. Set default header image
$header_image = !empty($city['image_url']) ? $root_path . $city['image_url'] : $root_path . 'images/jaipur/AmerFort.jpg';
?>

<!-- Page Header -->
<section class="page-header" style="background-image: url('<?php echo htmlspecialchars($header_image); ?>');">
    <div class="page-header-content">
        <h1><?php echo htmlspecialchars($city['city_name']); ?> Itinerary</h1>
        <div class="breadcrumb">
            <a href="<?php echo $root_path; ?>index.php">Home</a> &gt; 
            <a href="itineraries.php">Itineraries</a> &gt; 
            <?php echo htmlspecialchars($city['city_name']); ?>
        </div>
    </div>
</section>

<!-- Main Content Area with Accordion -->
<div class="container">

    <!-- 
      REMOVED the old tab containers.
      We will now loop and create an accordion structure.
      Each item will have a <button> header and a <div> content.
    -->
    <div class="accordion-container" style="margin: 30px 0;">
        <?php foreach ($all_days as $index => $day): ?>
            
            <!-- 1. Accordion Header (The "button") -->
            <button class="accordion-header <?php echo $index == 0 ? 'active' : ''; ?>">
                <span>
                    <i class="fas fa-calendar-day"></i>
                    <?php echo htmlspecialchars($day['duration']); ?>
                </span>
                <i class="fas fa-chevron-down accordion-icon"></i>
            </button>
            
            <!-- 2. Accordion Content (The "panel") -->
            <div class="accordion-content <?php echo $index == 0 ? 'active' : ''; ?>">
                <!-- 
                  We move the content from the old tab-content div here.
                  Note: The H2 title is removed, as the button above now acts as the title.
                -->
                <div class="content-section" style="border-top: none; padding-top: 10px; box-shadow: none;">
                    <div class="itinerary-day">
                        <div class="itinerary-day-content">
                            <div class="itinerary-part">
                                <h4><i class="fas fa-sun"></i> Morning</h4>
                                <p><?php echo nl2br(htmlspecialchars($day['morning'])); ?></p>
                            </div>
                            <div class="itinerary-part">
                                <h4><i class="fas fa-cloud-sun"></i> Afternoon</h4>
                                <p><?php echo nl2br(htmlspecialchars($day['afternoon'])); ?></p>
                            </div>
                            <div class="itinerary-part">
                                <h4><i class="fas fa-moon"></i> Evening</h4>
                                <p><?php echo nl2br(htmlspecialchars($day['evening'])); ?></p>
                            </div>
                        </div>
                        <?php if (!empty($day['notes'])): ?>
                        <div class="itinerary-day-header" style="border-top: 1px solid #eee; margin-top: 20px; padding-top: 20px;">
                            <strong>Notes:</strong> <?php echo htmlspecialchars($day['notes']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        <?php endforeach; ?>
    </div>
</div>

<!-- 
  CSS & JAVASCRIPT
  Add this right before the footer.
  This makes the accordion work.
-->
<style>
    .accordion-header {
        background-color: #f4f4f4;
        color: #333;
        cursor: pointer;
        padding: 18px 25px;
        width: 100%;
        text-align: left;
        border: none;
        outline: none;
        transition: background-color 0.3s ease;
        font-size: 1.15rem;
        font-weight: 600;
        border-radius: 5px;
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .accordion-header:hover,
    .accordion-header.active {
        background-color: #e0e0e0;
    }

    .accordion-header.active {
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .accordion-header .accordion-icon {
        transition: transform 0.3s ease;
        font-size: 0.9em;
    }

    .accordion-header.active .accordion-icon {
        transform: rotate(180deg);
    }

    .accordion-content {
        padding: 0 25px;
        background-color: white;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
        border: 1px solid #e0e0e0;
        border-top: none;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    
    /* When active, the content expands */
    .accordion-content.active {
        /* Set a large max-height. The JS will set this. */
    }
</style>

<script>
    // Wait for the document to be ready
    document.addEventListener("DOMContentLoaded", function() {
        const headers = document.querySelectorAll(".accordion-header");

        headers.forEach(header => {
            // Set the initial max-height for the active panel
            if (header.classList.contains('active')) {
                const content = header.nextElementSibling;
                if (content.classList.contains('accordion-content')) {
                    // Set to scrollHeight to show all content
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            }

            // Add click listener
            header.addEventListener("click", function() {
                // Toggle the 'active' class on the button
                this.classList.toggle("active");

                // Get the content panel
                const content = this.nextElementSibling;

                // Check if the panel is currently open
                if (content.style.maxHeight) {
                    // Panel is open, close it
                    content.style.maxHeight = null;
                } else {
                    // Panel is closed, open it
                    // Set max-height to its actual content height
                    content.style.maxHeight = content.scrollHeight + "px";
                }
                
                // Also toggle the active class on the content for styling
                content.classList.toggle("active");
            });
        });
    });
</script>

<?php include_once $root_path . 'includes/footer.php'; ?>

