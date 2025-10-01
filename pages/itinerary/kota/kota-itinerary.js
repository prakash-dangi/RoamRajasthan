document.addEventListener('DOMContentLoaded', () => {

    const itineraryData = {
        1: [
            { time: 'Morning (9 AM - 1 PM)', title: 'Garh Palace (City Palace)', description: 'Explore the sprawling palace complex, which houses the Maharao Madho Singh Museum showcasing impressive Rajput art and artifacts.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Local Kota Cuisine', description: 'Head to a local eatery to try the city\'s most famous snack, the "Kota Kachori." Amar Punjabi Dhaba is also a popular spot for a hearty lunch.' },
            { time: 'Afternoon (2 PM - 5 PM)', title: 'Kishore Sagar Lake & Jagmandir Palace', description: 'Enjoy the scenic beauty of this artificial lake. You can take a boat ride to get a closer view of the picturesque Jagmandir Palace located in the center.' },
            { time: 'Evening (5 PM onwards)', title: 'Seven Wonders Park', description: 'Visit this unique park featuring miniature replicas of the Seven Wonders of the World. It\'s especially beautiful in the evening when the structures are illuminated.' }
        ],
        2: [
            { time: 'Morning (9 AM - 12 PM)', title: 'Chambal Garden & River Cruise', description: 'Take a peaceful walk along the banks of the Chambal River in the well-maintained Chambal Garden. You can also go for a boat ride to spot crocodiles and enjoy the scenery.' },
            { time: 'Lunch (12 PM - 1 PM)', title: 'Riverside Lunch', description: 'Have lunch at a restaurant near the Chambal Garden area.' },
            { time: 'Afternoon (2 PM - 4 PM)', title: 'Kota Barrage & Godavari Dham Temple', description: 'Visit the Kota Barrage for impressive views of the dam\'s gushing water (especially during monsoon). Nearby is the Godavari Dham Temple, dedicated to Lord Hanuman.' },
            { time: 'Evening (5 PM onwards)', title: 'Chambal River Front', description: 'Spend a relaxing evening at the newly developed Chambal River Front, a beautiful waterfront project. Enjoy the views, architecture, and the evening laser show.' }
        ],
        3: [
            { time: 'Morning (9 AM - 1 PM)', title: 'Day Trip to Gaipernath Waterfall', description: 'Take a trip (around 25 km) to this scenic waterfall and temple located in a gorge. It is a serene spot for nature lovers and a great place for photography.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Packed Lunch or Local Dhaba', description: 'It is advisable to carry a packed lunch for your trip to Gaipernath, or you can find a local dhaba on the highway on your way back.' },
            { time: 'Afternoon (3 PM onwards)', title: 'Shopping for Kota Doria Sarees', description: 'Explore the markets in Kota or the nearby town of Kaithoon, which is famous for its lightweight, woven Kota Doria sarees. It’s the perfect souvenir to take back from your trip.' }
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

