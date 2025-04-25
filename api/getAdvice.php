<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $soil = $_POST['soil'];
        $water = $_POST['water'];
        $temperature = $_POST['temperature'];
        // Simulate an advice
        $advice = "For $soil soil with $water water availability, and $temperature°C temperature, you should plant rice.";
        echo $advice;
    }
    ?>