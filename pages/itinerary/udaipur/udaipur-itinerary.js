document.addEventListener('DOMContentLoaded', () => {

    const itineraryData = {
        1: [
            { time: 'Morning (9 AM - 1 PM)', title: 'City Palace', description: 'Explore the vast City Palace complex on the banks of Lake Pichola. It offers a stunning mix of Rajasthani and Mughal architecture, with several palaces and museums inside.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Lakeside Lunch', description: 'Enjoy lunch at a restaurant by Lake Pichola, such as Ambrai or Upre, offering stunning views of the Lake Palace and Jagmandir.' },
            { time: 'Afternoon (2 PM - 4 PM)', title: 'Jagdish Temple & Old City Walk', description: 'Visit the large and artistic Jagdish Temple. Afterwards, take a walk through the narrow lanes of the old city, exploring art shops and local markets near the palace.' },
            { time: 'Evening (5 PM onwards)', title: 'Sunset Boat Ride on Lake Pichola', description: 'Enjoy a serene sunset boat ride on Lake Pichola for beautiful views of the City Palace, Jagmandir, and the Lake Palace hotel. This is a must-do Udaipur experience.' }
        ],
        2: [
            { time: 'Morning (9 AM - 12 PM)', title: 'Saheliyon Ki Bari & Fateh Sagar Lake', description: 'Visit Saheliyon Ki Bari, a beautiful garden with fountains, lotus pools, and marble elephants. Afterwards, spend time at the scenic Fateh Sagar Lake and enjoy a boat ride.' },
            { time: 'Lunch (12 PM - 1 PM)', title: 'Lunch near Fateh Sagar', description: 'Have lunch at one of the many cafes or restaurants located along the periphery of Fateh Sagar Lake.' },
            { time: 'Afternoon (2 PM - 5 PM)', title: 'Monsoon Palace (Sajjangarh)', description: 'Drive up to the Monsoon Palace, perched on a hill. It offers breathtaking panoramic views of the city, its lakes, and the surrounding Aravalli mountains, especially at sunset.' },
            { time: 'Evening (7 PM onwards)', title: 'Dharohar Folk Dance Show', description: 'Experience a vibrant cultural evening at Bagore Ki Haveli with the Dharohar folk dance and puppet show, which takes place daily at 7 PM.' }
        ],
        3: [
            { time: 'Morning (8 AM - 1 PM)', title: 'Day trip to Kumbhalgarh Fort', description: 'Take a day trip (approx. 2.5-hour drive) to Kumbhalgarh Fort, a UNESCO World Heritage Site with the second-longest continuous wall in the world, after the Great Wall of China.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Lunch near Kumbhalgarh', description: 'Have lunch at a hotel or resort near the fort before heading to your next destination.' },
            { time: 'Afternoon (3 PM - 5 PM)', title: 'Ranakpur Jain Temple (Optional)', description: 'On the way back from Kumbhalgarh, you can visit the stunningly intricate Ranakpur Jain Temple, famous for its 1,444 uniquely carved marble pillars.' },
            { time: 'Evening (7 PM onwards)', title: 'Return to Udaipur', description: 'Drive back to Udaipur in the evening. Enjoy a relaxed dinner at a restaurant of your choice, perhaps trying a different lakeside dining experience.' }
        ]
    };

    const buttons = document.querySelectorAll('.day-btn');
    const contentArea = document.getElementById('itinerary-content');

    function displayItinerary(days) {
        contentArea.classList.add('loading');
        setTimeout(() => {
            contentArea.innerHTML = '';
            for (let i = 1; i <= days; i++) {
                const dayData = itineraryData[i];
                if (dayData) {
                    const dayHeading = document.createElement('h3');
                    dayHeading.textContent = `Day ${i}`;
                    contentArea.appendChild(dayHeading);
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
            contentArea.classList.remove('loading');
        }, 300);
    }

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            const days = parseInt(button.getAttribute('data-days'));
            displayItinerary(days);
        });
    });

    displayItinerary(1);
});

