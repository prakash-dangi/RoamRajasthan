document.addEventListener('DOMContentLoaded', () => {

    const itineraryData = {
        1: [
            { time: 'Morning (9 AM - 1 PM)', title: 'Mehrangarh Fort', description: 'Explore one of India\'s largest forts. Don\'t miss the museum, palaces like Moti Mahal and Phool Mahal, and the panoramic views of the Blue City from the top.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Lunch with a Fort View', description: 'Dine at a restaurant in the old city that offers a great view of Mehrangarh Fort, such as Indique or Kim Mohan\'s.' },
            { time: 'Afternoon (2 PM - 3 PM)', title: 'Jaswant Thada', description: 'Visit this intricate white marble cenotaph, often called the "Taj Mahal of Marwar." It\'s located a short walk from Mehrangarh Fort.' },
            { time: 'Evening (4 PM onwards)', title: 'Clock Tower & Sardar Market', description: 'Explore the bustling market around the iconic Clock Tower. It\'s a great place to shop for spices, textiles, and handicrafts. Try the famous Makhaniya Lassi at a local shop.' }
        ],
        2: [
            { time: 'Morning (10 AM - 1 PM)', title: 'Umaid Bhawan Palace Museum', description: 'Visit the museum section of this magnificent palace, which is one of the world\'s largest private residences, showcasing royal artifacts and a collection of vintage cars.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Local Cuisine', description: 'Enjoy lunch at a local restaurant. Try famous Jodhpuri dishes like Mirchi Bada and Mawa Kachori at Janta Sweet Home.' },
            { time: 'Afternoon (3 PM - 5 PM)', title: 'Explore the Blue City & Toorji Ka Jhalra', description: 'Take a walking tour through the narrow, winding lanes of the old city (Brahmpuri) to see the famous blue houses. End your walk at the beautifully restored ancient stepwell, Toorji Ka Jhalra.' },
            { time: 'Evening (6 PM onwards)', title: 'Sunset and Dinner', description: 'Find a rooftop cafe near the stepwell to enjoy the sunset, followed by dinner with a spectacular view of the illuminated Mehrangarh Fort.' }
        ],
        3: [
            { time: 'Morning (9 AM - 12 PM)', title: 'Mandore Gardens', description: 'Explore the ancient capital of Marwar. The gardens have impressive cenotaphs of Jodhpur\'s former rulers, a hall of heroes, and a temple inhabited by monkeys.' },
            { time: 'Afternoon (1 PM - 5 PM)', title: 'Bishnoi Village Safari', description: 'Take a half-day jeep safari to a Bishnoi village. Experience the local culture, witness a traditional opium ceremony, see artisans at work (potters, weavers), and spot wildlife like blackbucks and chinkaras.' },
            { time: 'Evening (6 PM onwards)', title: 'Relaxed Evening & Shopping', description: 'Return to the city. Spend a relaxed evening doing some final shopping for leather goods (mojaris) or Bandhani (tie-dye) textiles for which Jodhpur is famous.' }
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

