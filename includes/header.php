<?php
/*
 * ==========================================================
 * Site Header - UPGRADED
 * ==========================================================
 * This REPLACES your old includes/header.php.
 * It shows the user's profile picture in the nav.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($root_path)) {
    $root_path = '';
}

// Fetch user profile pic for nav
$nav_user_img = $root_path . 'images/default_profile.png';
if (isset($_SESSION['user_id'])) {
    // We can do a quick DB check here if we want the most up-to-date image
    // For performance, we could store it in the session at login
    if (isset($_SESSION['profile_image_url'])) {
         $nav_user_img = $root_path . $_SESSION['profile_image_url'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Roam Rajasthan'; ?></title>
    <link rel="stylesheet" href="<?php echo $root_path; ?>css/main.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    <header>
        <div class="nav-bar">
            <div class="logo">
                <a href="<?php echo $root_path; ?>index.php">
                    <!-- <img src="<?php echo $root_path; ?>images/logo1.png" alt="Roam Rajasthan Logo">     -->
                    <span>RoamRajasthan</span>
                </a>
            </div>
            <div class="nav-items">
                <ul>
                    <li><a href="<?php echo $root_path; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $root_path; ?>pages/cities/cities.php">Cities</a></li>
                    <li><a href="<?php echo $root_path; ?>pages/itinerary/itineraries.php">Itineraries</a></li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo $root_path; ?>profile.php">My Profile</a></li>
                        <li><a href="<?php echo $root_path; ?>logout.php">Logout</a></li>
                        <li>
                            <a href="<?php echo $root_path; ?>profile.php" title="My Profile">
                                <img src="<?php echo $nav_user_img; ?>" alt="My Profile" class="nav-user-img">
                            </a>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo $root_path; ?>login.php">Login</a></li>
                        <li><a href="<?php echo $root_path; ?>register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>
    <main>

