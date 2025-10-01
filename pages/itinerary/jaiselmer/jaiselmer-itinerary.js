document.addEventListener('DOMContentLoaded', () => {

    const itineraryData = {
        1: [
            { time: 'Morning (9 AM - 1 PM)', title: 'Jaisalmer Fort (Sonar Quila)', description: 'Explore this living fort, a UNESCO World Heritage Site. Wander through its narrow lanes, visit the intricate Jain Temples, and enjoy panoramic views from the cannon points.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Lunch inside the Fort', description: 'Experience dining within the fort walls at a rooftop cafe like The Trio or Gaji\'s Restaurant, offering great food and views.' },
            { time: 'Afternoon (2 PM - 5 PM)', title: 'Havelis of Jaisalmer', description: 'Visit the cluster of five intricately carved havelis known as Patwon Ki Haveli. Also, see the unique, ship-like architecture of Salim Singh Ki Haveli.' },
            { time: 'Evening (5 PM onwards)', title: 'Gadisar Lake', description: 'Enjoy a peaceful evening with a boat ride on the beautiful Gadisar Lake, surrounded by temples and shrines. It\'s a perfect spot to watch the sunset over the cenotaphs.' }
        ],
        2: [
            { time: 'Morning (9 AM - 11 AM)', title: 'Bada Bagh', description: 'Visit this garden complex with a set of royal cenotaphs (chhatris) of Jaisalmer\'s Maharajas. The golden structures against the desert landscape are a stunning sight.' },
            { time: 'Afternoon (1 PM - 4 PM)', title: 'Journey to the Thar Desert', description: 'Travel to the famous Sam Sand Dunes (approx. 40 km). On the way, stop at the mysterious and abandoned village of Kuldhara, known for its ghost stories.' },
            { time: 'Evening (4 PM onwards)', title: 'Desert Safari & Cultural Program', description: 'Upon reaching the dunes, enjoy a thrilling jeep safari followed by a camel safari deep into the desert to watch the sunset. In the evening, enjoy a cultural program with folk music, dance, and a traditional Rajasthani dinner at a desert camp.' }
        ],
        3: [
            { time: 'Morning (9 AM - 11 AM)', title: 'Jaisalmer War Museum', description: 'Visit this museum to learn about the bravery and history of the Indian Army, particularly focusing on the Battle of Longewala in 1971. It\'s an inspiring visit.' },
            { time: 'Lunch (12 PM - 1 PM)', title: 'Local Jaisalmeri Cuisine', description: 'Try local specialties for lunch. Head to a restaurant like Desert Boy\'s Dhani to taste dishes such as Ker Sangri.' },
            { time: 'Afternoon (2 PM onwards)', title: 'Shopping in Local Markets', description: 'Spend your final afternoon shopping for souvenirs. Explore markets like Sadar Bazaar and Manak Chowk for leather goods, mirror-work textiles, camel bone artifacts, and colorful puppets.' }
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

