// Example JS for form handling and weather data simulation

document.addEventListener("DOMContentLoaded", function() {
    // Simulate Weather Data
    const weatherElement = document.getElementById("weather-data");
    weatherElement.innerText = "Sunny, 27°C with a gentle breeze.";

    // Handle crop advice form submission
    const adviceForm = document.getElementById("advice-form");
    const adviceResult = document.getElementById("advice-result");

    adviceForm.addEventListener("submit", function(e) {
        e.preventDefault();

        const soil = document.getElementById("soil").value;
        const water = document.getElementById("water").value;
        const temperature = document.getElementById("temperature").value;

        // Simulate an API call
        setTimeout(function() {
            adviceResult.innerText = `Based on your input (Soil: ${soil}, Water: ${water}, Temp: ${temperature}°C), we recommend planting tomatoes.`;
        }, 500);
    });
});
