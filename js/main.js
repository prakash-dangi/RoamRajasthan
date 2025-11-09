/*
  This is the complete, consolidated JavaScript for your entire website.
  This single file replaces all the individual .js files from your original project
  (e.g., jaipur.js, bikaner.js, etc.) as they all contained this exact same code.
*/

/**
 * This is the original openTab function from your project.
 * It is now in one central file and will work for any page
 * that uses the tabbed HTML structure (like city.php and itinerary.php).
 * @param {Event} evt The click event.
 * @param {string} tabName The ID of the tab content to show.
 */
function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the "active" class
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    var currentTab = document.getElementById(tabName);
    if (currentTab) {
        currentTab.style.display = "block";
    }
    
    if (evt && evt.currentTarget) {
        evt.currentTarget.className += " active";
    }
}

/**
 * This part of the code waits for the page to fully load.
 * It then finds the tab with id="defaultOpen" and "clicks" it.
 * This ensures your pages don't load with an empty content area.
 *
 * This is a more robust way to do what your original files did with:
 * document.getElementById("defaultOpen").click();
 */
window.addEventListener('load', function() {
    // Find the element with id="defaultOpen"
    const defaultOpenElement = document.getElementById("defaultOpen");
    
    // If it exists, programmatically click it to open the first tab
    if (defaultOpenElement) {
        defaultOpenElement.click();
    }
});

/**
 * This is the new function to handle the "Follow" button clicks
 * on user_profile.php.
 */
function handleFollow(followerId, followingId) {
    // Use the Fetch API to send data to the server
    fetch('follow_handler.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        // Send the IDs as form data
        body: `follower_id=${followerId}&following_id=${followingId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the button and follower count
            const followButton = document.getElementById('follow-button');
            const followersCount = document.getElementById('followers-count');
            
            if (data.action === 'followed') {
                followButton.textContent = 'Following';
                followButton.classList.add('following');
            } else {
                followButton.textContent = 'Follow';
                followButton.classList.remove('following');
            }
            // Update the count on the page
            followersCount.textContent = data.newCount;
        } else {
            // Handle any errors
            console.error('Error:', data.message);
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
    });
}

