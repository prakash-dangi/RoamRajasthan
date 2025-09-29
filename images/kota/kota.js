document.addEventListener("DOMContentLoaded", () => {
  // Animate city heading
  const cityHeading = document.querySelector(".city-header h1");
  const cityDesc = document.querySelector(".city-description");

  setTimeout(() => {
    cityHeading.classList.add("visible");
  }, 200);

  setTimeout(() => {
    cityDesc.classList.add("visible");
  }, 600); // description appears after heading

  // Animate cards
  const cards = document.querySelectorAll(".card");
  cards.forEach((card, index) => {
    setTimeout(() => {
      card.classList.add("visible");
    }, index * 300 + 900); // stagger cards after heading+desc
  });
});

