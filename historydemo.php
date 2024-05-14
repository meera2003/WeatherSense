<?php
session_start();
include '../login system with avatar/config.php';
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login system with avatar/login.php');
}
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historical Weather Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="historydemostyles.css">

</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">WeatherSense </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span id="" class="navbar-toggler-icon"></span id="">
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.html">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="forecast.html">Forecast</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="aboutus.html">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active " aria-current="page" href="email.html" >Contact Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="./login system with avatar/home.php">Profile</a>
              </li>

              
              
            </ul>
            <div id="google_element">
              <script>
                  function googleTranslateElementInit() {
                      new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_element');
                  }
                  var googleTranslateScript = document.createElement('script');
                  googleTranslateScript.type = 'text/javascript';
                  googleTranslateScript.async = true;
                  googleTranslateScript.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                  ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild(googleTranslateScript);
              </script>
                  </div>
            <!-- <form class="navbar-form navbar-right d-flex" role="search" id="navBarSearchForm"> -->
              <!-- <button class="btn btn-outline-primary" type="submit" id="submit" >SignIn / SignUp</button> -->
              
              <!-- <a href="simple php system/php/logout.php" class="btn btn-primary btn-lg">Logout</a> -->
            <!-- </form> -->
          </div>
        </div>
      </nav> 
<main>
    <div>
        <div>
        <div>
        <div>
    <form id="weatherForm">
        <label for="location">Location:</label>
        <input type="text" id="location" required>
        <label for="fromDate">From Date:</label>
        <input type="date" id="fromDate" required>
        <label for="toDate">To Date:</label>
        <input type="date" id="toDate" required>
        <button type="submit">Get Data</button>
    </form>
    </div>
    </div>
    </div>
    </div>
    <div id="weatherData"></div>
</main>

<script>

    // Get current date
    const currentDate = new Date();

    // Calculate date 7 days ago
    const sevenDaysAgo = new Date();
    sevenDaysAgo.setDate(currentDate.getDate() - 8);

    // Format the date to set it as min attribute in the input element
    const minDate = sevenDaysAgo.toISOString().split('T')[0];

    // Set the min attribute of the "From Date" input element
    document.getElementById('fromDate').setAttribute('min', minDate);

    // Set the min attribute of the "To Date" input element
    document.getElementById('toDate').setAttribute('min', minDate);

    // Format the current date to set it as max attribute in the input element
    const maxDate = currentDate.toISOString().split('T')[0];

    // Set the max attribute of the "From Date" input element
    document.getElementById('fromDate').setAttribute('max', maxDate);

    // Set the max attribute of the "To Date" input element
    document.getElementById('toDate').setAttribute('max', maxDate);

    const weatherForm = document.getElementById('weatherForm');
    const weatherDataDiv = document.getElementById('weatherData');

    weatherForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const location = document.getElementById('location').value;
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;

        fetchDataForWeek(location, fromDate, toDate);
    });

    async function fetchDataForWeek(location, fromDate, toDate) {
        const startDate = new Date(fromDate);
        const endDate = new Date(toDate);

        const daysDifference = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));

        const weatherDataArray = [];

        for (let i = 0; i <= daysDifference; i++) {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + i);

            const formattedDate = currentDate.toISOString().split('T')[0];

            const weatherData = await fetchWeatherData(location, formattedDate, formattedDate);
            
            weatherDataArray.push(weatherData);
        }

        displayWeatherData(weatherDataArray);
    }

    async function fetchWeatherData(location, fromDate, toDate) {
        const url = `https://weatherapi-com.p.rapidapi.com/history.json?q=${encodeURIComponent(location)}&dt=${encodeURIComponent(fromDate)}&lang=en`;
        const options = {
            method: 'GET',
            headers: {
                'X-RapidAPI-Key': '127d8668eamsh690930e3d4cf22ap17bc4ejsnb515a1b39150',
                'X-RapidAPI-Host': 'weatherapi-com.p.rapidapi.com'
            }
        };
        try {
            const response = await fetch(url, options);
            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Error fetching weather data:', error);
        }
    }

    function displayWeatherData(dataArray) {
        weatherDataDiv.innerHTML = '';

        const table = document.createElement('table');

        const headers = ['Location', 'Date', 'Average Temperature (°C)', 'Max Temperature (°C)', 'Min Temperature (°C)', 'Condition'];
        const headerRow = document.createElement('tr');
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            headerRow.appendChild(th);
        });
        table.appendChild(headerRow);

        dataArray.forEach(data => {
            if (!data || !data.forecast || data.forecast.forecastday.length === 0) {
                const row = document.createElement('tr');
                const cell = document.createElement('td');
                cell.colSpan = headers.length;
                cell.textContent = 'No weather data available for the selected location and dates.';
                row.appendChild(cell);
                table.appendChild(row);
            } else {
                const weatherInfo = data.forecast.forecastday[0];
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${data.location.name}, ${data.location.country}</td>
                    <td>${weatherInfo.date}</td>
                    <td>${weatherInfo.day.avgtemp_c}°C</td>
                    <td>${weatherInfo.day.maxtemp_c}°C</td>
                    <td>${weatherInfo.day.mintemp_c}°C</td>
                    <td>${weatherInfo.day.condition.text}</td>
                `;
                table.appendChild(row);
            }
        });

        weatherDataDiv.appendChild(table);
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>
