// Load Nav & Footer components
function loadHTML(id, file) {
  fetch(`components/${file}`)
    .then((response) => response.text())
    .then((data) => (document.getElementById(id).innerHTML = data))
    .catch((error) => console.error("Error loading component:", error));
}

loadHTML("nav-placeholder", "nav.html");
loadHTML("footer-placeholder", "footer.html");

// Projects.html - Read More/Show Less buttons for overlay text on smaller screens
document.addEventListener("DOMContentLoaded", function () {
  const readMoreButtons = document.querySelectorAll(".read-more-btn");
  readMoreButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const overlayText = this.parentElement;
      const fullText = overlayText.querySelector(".full-text");
      const truncatedText = overlayText.querySelector(".truncated-text");

      if (fullText.style.display === "inline") {
        // Hide the full text and show truncated text
        fullText.style.display = "none";
        truncatedText.style.display = "inline";
        this.textContent = "Read more";
      } else {
        // Show the full text and hide truncated text
        fullText.style.display = "inline";
        truncatedText.style.display = "none";
        this.textContent = "Show less";
      }
    });
  });
});