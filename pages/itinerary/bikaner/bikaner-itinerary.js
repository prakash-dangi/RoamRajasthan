document.addEventListener('DOMContentLoaded', () => {

    const itineraryData = {
        1: [
            { time: 'Morning (9 AM - 1 PM)', title: 'Junagarh Fort', description: 'Explore this imposing fort that has never been conquered. Admire its palaces like Anup Mahal and Chandra Mahal, temples, and pavilions that showcase a composite culture.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Traditional Rajasthani Thali', description: 'Enjoy an authentic Rajasthani thali at a restaurant near the fort, such as Gallops Restaurant or Heeralal’s.' },
            { time: 'Afternoon (2 PM - 4 PM)', title: 'Lalgarh Palace and Museum', description: 'Visit a section of this stunning red sandstone palace, which now houses the Shri Sadul Museum, displaying royal memorabilia and artifacts.' },
            { time: 'Evening (4 PM onwards)', title: 'Old City & Havelis Walk', description: 'Take a walk or a rickshaw ride through the narrow lanes of Bikaner\'s old city. Don\'t miss the beautiful Rampuria Havelis. For shopping, explore the bustling Kote Gate area for handicrafts and local goods.' }
        ],
        2: [
            { time: 'Morning (8 AM - 12 PM)', title: 'Karni Mata Temple (Rat Temple)', description: 'Take a 30km trip to Deshnoke to visit the unique Karni Mata Temple, famous for the thousands of holy rats that are considered sacred and are fed by devotees.' },
            { time: 'Lunch (12 PM - 1 PM)', title: 'Lunch in Deshnoke or back in Bikaner', description: 'Have a simple lunch at a local eatery in Deshnoke or travel back to Bikaner for more options.' },
            { time: 'Afternoon (2 PM - 5 PM)', title: 'National Research Centre on Camel', description: 'Visit this one-of-a-kind research center. Learn about different camel breeds, see the baby camels, and try camel milk products like ice cream and coffee.' },
            { time: 'Evening (5 PM onwards)', title: 'Taste Bikaneri Bhujia', description: 'No trip to Bikaner is complete without its most famous snack. Visit an original outlet like "Bikharam Chandmal Bhujiawala" to buy fresh, authentic Bikaneri Bhujia and other namkeens.' }
        ],
        3: [
            { time: 'Morning (9 AM - 1 PM)', title: 'Gajner Palace & Lake', description: 'Take a day trip (around 30km) to the Gajner Palace, a former royal hunting lodge on the edge of a serene lake. It is now a heritage hotel, but you can admire its beauty and the surrounding wildlife sanctuary.' },
            { time: 'Lunch (1 PM - 2 PM)', title: 'Lunch at Gajner Palace', description: 'Enjoy a royal lunch experience at the restaurant within the Gajner Palace hotel, overlooking the lake.' },
            { time: 'Afternoon (3 PM - 5 PM)', title: 'Devi Kund Sagar', description: 'On your way back to Bikaner, visit the royal cenotaphs (chhatris) of the rulers of the Bikaner dynasty, showcasing remarkable Rajput and Mughal architectural styles.' },
            { time: 'Evening (5 PM onwards)', title: 'Final Shopping & Departure Prep', description: 'Spend your final evening doing some last-minute shopping for local specialties like Usta art, leather Mojaris (footwear), or stocking up on more Bikaneri snacks.' }
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

