<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Weather App</title>
  <link rel="stylesheet" href="SujenManChitrakar_2358815.css" />
</head>

<body>
  <?php
  //fetch current data from open weather map
  $apiKey = "78538f2a77f2eda10a309627f735ae14";
  $url = "https://api.openweathermap.org/data/2.5/weather?q=tameside&appid=" . $apiKey . "&units=metric";
  $content = file_get_contents($url);
  $decoded = json_decode($content, true);
  // echo $content."<br>";
  
  $main = $decoded['main'];
  $temp = $main['temp'];
  $min_temp = $main['temp_min'];
  $max_temp = $main['temp_max'];
  $humidity = $main['humidity'];
  $pressure = $main['pressure'];
  $wind = $decoded['wind']['speed'];
  $date_time = $decoded['dt'] - $decoded['dt'] % 86400; // subtract hours to make unix timestamp 12:00am
  $past_week_time = $date_time - 604800;
  $today_time = time() - time() % 86400;
  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "weatherapp";

  // Create connection to database
  $conn = new mysqli($servername, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  //inserting the values fetched from the api in the database using SQL
  $insert_sql = "INSERT INTO `past_weather`(`min_temp`, `max_temp`, `humidity`, `wind`, `pressure`, `date_time`) VALUES ("
    . $min_temp . ","
    . $max_temp . ","
    . $humidity . ","
    . $wind . ","
    . $pressure . ","
    . $date_time
    . ")";
  $select_one_sql = "SELECT * FROM `past_weather` WHERE `date_time` = " . $date_time;
  $select_one_result = $conn->query($select_one_sql);
  //creating the new record
  if ($select_one_result->num_rows == 0) {
    if ($conn->query($insert_sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }

  $select_seven_sql = "SELECT * FROM `past_weather` WHERE `date_time` > " . $past_week_time . " and date_time < " . time();
  $select_seven_result = $conn->query($select_seven_sql);

  ?>
  <div class="box">
    <div class="search_segment">
      <input type="text" id="search-bar" placeholder="Search for a country/city" />
      <button id="button">
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1.5em"
          width="1.5em" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M505.04 442.66l-99.71-99.69c-4.5-4.5-10.6-7-17-7h-16.3c27.6-35.3 44-79.69 44-127.99C416.03 93.09 322.92 0 208.02 0S0 93.09 0 207.98s93.11 207.98 208.02 207.98c48.3 0 92.71-16.4 128.01-44v16.3c0 6.4 2.5 12.5 7 17l99.71 99.69c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.59.1-33.99zm-297.02-90.7c-79.54 0-144-64.34-144-143.98 0-79.53 64.35-143.98 144-143.98 79.54 0 144 64.34 144 143.98 0 79.53-64.35 143.98-144 143.98zm.02-239.96c-40.78 0-73.84 33.05-73.84 73.83 0 32.96 48.26 93.05 66.75 114.86a9.24 9.24 0 0 0 14.18 0c18.49-21.81 66.75-81.89 66.75-114.86 0-40.78-33.06-73.83-73.84-73.83zm0 96c-13.26 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z">
          </path>
        </svg>
      </button>
    </div>
    <!-- shows the data in more detailed and organized format -->
    <div class="weather">
      <h2 class="city">Weather in Sevenoaks</h2>
      <h2 class="time"></h2>
      <div class="date"></div>
      <h1 class="temp">25°C</h1>
      <img id="icon" src="" alt="icon" />
      <div class="description">Cloudy</div>
      <div class="humidity">Humidity: 60%</div>
      <div class="wind">Wind speed: 80 Km/h</div>
      <div class="max">Maximum Temperature: 25°C</div>
      <div class="min">Minimum Temperature: 25°C</div>
      <div class="rain">Rain: 10mm</div>
    </div>
  </div>
  <!-- making a table to show data of past week-->
  <div class="weatable">
    <table>
      <tr>
        <th>Day</th>
        <th>Max temp</th>
        <th>Min temp</th>
        <th>Humidity</th>
        <th>Wind</th>
        <th>Pressure</th>
      </tr>
      <?php
      // creating table in the upper portion of the code
      if ($select_seven_result->num_rows > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($select_seven_result)) {
          $current_day = date("D", $row["date_time"]);
          echo "<tr>
                  <td>" . $current_day . "</td>
                  <td id=\"Max_temp\">" . $row["max_temp"] . "</td>
                  <td id=\"Min_temp\">" . $row["min_temp"] . "</td>
                  <td id=\"humidity\">" . $row["humidity"] . "</td>
                  <td id=\"wind\">" . $row["wind"] . "</td>
                  <td id=\"pressure\">" . $row["pressure"] . "</td>
                </tr>";
        }
      } else {
        echo "0 results";
      }
      ?>
    </table>
  </div>
  <!-- linking with JS file -->
  <script src="SujenManChitrakar_2358815.js"></script>
</body>

</html>