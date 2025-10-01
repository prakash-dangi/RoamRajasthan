document.addEventListener('DOMContentLoaded', () => {

    // --- ITINERARY DATA ---
    // This is where you'll define the plans for each day.
    // You can easily replace this content for other cities.
    const itineraryData = {
        1: [ // Day 1
            {
                time: 'Morning',
                title: 'Hawa Mahal & City Palace',
                description: 'Start your day with the iconic Hawa Mahal (Palace of Winds). Then, explore the vast City Palace complex, which includes museums and courtyards.'
            },
            {
                time: 'Afternoon',
                title: 'Jantar Mantar & Lunch',
                description: 'Visit the Jantar Mantar, an astronomical observatory. Afterward, enjoy a traditional Rajasthani thali at a nearby restaurant like LMB in Johari Bazar.'
            },
            {
                time: 'Evening',
                title: 'Shopping in Bapu Bazaar',
                description: 'Spend your evening shopping for textiles, handicrafts, and souvenirs in the bustling lanes of Bapu Bazaar and Johari Bazaar.'
            }
        ],
        2: [ // Day 2
            {
                time: 'Morning',
                title: 'Amer Fort',
                description: 'Visit the magnificent Amer Fort. You can either walk up, take a jeep, or enjoy an elephant ride. Explore its palaces, halls, and temples.'
            },
            {
                time: 'Afternoon',
                title: 'Jaigarh & Nahargarh Forts',
                description: 'After Amer, visit the nearby Jaigarh Fort, famous for the world\'s largest cannon on wheels. Then, head to Nahargarh Fort for stunning sunset views over Jaipur.'
            },
            {
                time: 'Evening',
                title: 'Dinner at a Rooftop Restaurant',
                description: 'Enjoy a relaxing dinner at a rooftop restaurant with views of the illuminated city. Many options are available around the city center.'
            }
        ],
        3: [ // Day 3
            {
                time: 'Morning',
                title: 'Albert Hall Museum & Birla Mandir',
                description: 'Visit the Albert Hall Museum, the oldest museum in Rajasthan. Following that, visit the serene and beautiful Birla Mandir, a modern white marble temple.'
            },
            {
                time: 'Afternoon',
                title: 'Galtaji (Monkey Temple)',
                description: 'Take a trip to Galtaji, an ancient Hindu pilgrimage site with a series of temples built into a narrow crevice in the ring of hills that surrounds Jaipur.'
            },
            {
                time: 'Evening',
                title: 'Chokhi Dhani',
                description: 'Experience Rajasthani culture, folk dances, and traditional dinner at Chokhi Dhani, a mock village resort on the outskirts of Jaipur.'
            }
        ]
    };

    const buttons = document.querySelectorAll('.day-btn');
    const contentArea = document.getElementById('itinerary-content');

    // Function to display the itinerary for the selected number of days
    function displayItinerary(days) {
        // 1. Add a class to start the fade-out animation
        contentArea.classList.add('loading');

        // 2. Wait for the fade-out to complete before changing the content
        setTimeout(() => {
            contentArea.innerHTML = ''; // Clear existing content

            for (let i = 1; i <= days; i++) {
                const dayData = itineraryData[i];
                if (dayData) {
                    // Create and add the "Day X" heading
                    const dayHeading = document.createElement('h3');
                    dayHeading.textContent = `Day ${i}`;
                    contentArea.appendChild(dayHeading);

                    // Create and add the timeline items for the day
                    dayData.forEach(item => {
                        const timelineItem = document.createElement('div');
                        timelineItem.className = 'timeline-item';
                        timelineItem.innerHTML = `
                            <div class="timeline-time">${item.time}</div>
                            <div class="timeline-details">
                                <h4>${item.title}</h4>
                                <p>${item.description}</p>
                            </div>
                        `;
                        contentArea.appendChild(timelineItem);
                    });
                }
            }
            
            // 3. Remove the class to start the fade-in animation
            contentArea.classList.remove('loading');
        }, 300); // This duration matches the CSS transition time
    }

    // Event listeners for the day selection buttons
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            // Update active button style
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Display the corresponding itinerary
            const days = parseInt(button.getAttribute('data-days'));
            displayItinerary(days);
        });
    });

    // Display the 1-day itinerary by default on page load
    displayItinerary(1);
});

