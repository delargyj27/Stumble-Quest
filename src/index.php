<?php

// Start session
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stumble Quest</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>
    <div class="top-bar">
        <a href="tel:(631)-000-0000"><ion-icon name="call-outline"></ion-icon> <span>Click To Call Our Team Now!</span></a>
        <ul>
            <li><a href="login.html">Login</a></li>
            <li><a href="register.html">Register</a></li>
        </ul>
    </div>

    <nav>
        <div class="logo">
            <a href="#"><img src="images/pint.png" alt="logo">Stumble Quest</a>
        </div>
        <div class="toggle">
            <a href="#"><ion-icon name="menu-outline"></ion-icon></a>
        </div>
        <ul class="menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="map.html">Map</a></li>
            <li><a href="bars.html">Bars</a></li>
            <li><a href="crawl.html">Crawl</a></li>
            <li><a href="events.html">Calander</a></li>
            <li><a href="aboutUs.html">FAQ</a></li>
        </ul>
    </nav>
    
<div class="container">
  <div class="about-us">
      <h1>Welcome!</h1>
      <p>Welcome to Stumble Quest, your ultimate guide to exploring the best bars and nightlife in your area. Whether you're planning a night out with friends or looking for new and exciting places to visit, we've got you covered.</p>
  </div>
  <div class="calendar">
      <h1>Events Calendar</h1>
      <iframe src="https://calendar.google.com/calendar/embed?src=yourcalendarid" style="border: 0" width="400" height="300" frameborder="0" scrolling="no"></iframe>
  </div>
</div>

<script>
    const toggleButton = document.querySelector('.toggle a');
    const menu = document.querySelector('.menu');

    toggleButton.addEventListener('click', () => {
        menu.classList.toggle('active');
    });
</script>

</body>
</html>

