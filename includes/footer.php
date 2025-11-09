<?php
/*
 * Site Footer
 * Save as: includes/footer.php
 */

// Ensure $root_path is set (it should be set by the main page)
if (!isset($root_path)) {
    $root_path = '';
}
?>
    </main>
    <!-- Main content ends here -->

    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h3>Roam Rajasthan</h3>
                <p>Your ultimate guide to exploring the land of kings, forts, and legends.</p>
            </div>
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo $root_path; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $root_path; ?>pages/cities/cities.php">Cities</a></li>
                    <li><a href="<?php echo $root_path; ?>pages/itinerary/itineraries.php">Itineraries</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo $root_path; ?>profile.php">Profile</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $root_path; ?>login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-section social">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> RoamRajasthan.com | All Rights Reserved
        </div>
    </footer>

    <script src="<?php echo $root_path; ?>js/main.js"></script>

</body>
</html>

