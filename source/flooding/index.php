<?php

// Initialize the session
session_start();
 
/*
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
*/
if (!isset($_GET["location"])){
    header("location: index.php?location=All");
}
?>
 
 <!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Data Logs</title>

        <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"
        ></script>
        

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>  
        <script src="https://npmcdn.com/js-alert/dist/jsalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

        <style>
            body{ font: 14px sans-serif; }
            .wrapper{ width: 360px; padding: 20px; }
            table.table-fit {
                width: auto !important;
                table-layout: auto !important;
                padding-left: 30px;
                padding-right: 30px;
            }
            table.table-fit thead th, table.table-fit tfoot th {
                width: auto !important;
                padding-left: 30px;
                padding-right: 30px;
            }
            table.table-fit tbody td, table.table-fit tfoot td {
                width: auto !important;
                padding-left: 30px;
                padding-right: 30px;
            }
        </style>

    </head>
        
    <body>
        <div class="shadow-sm p-2 mb-5 bg-body rounded">
            <div class="container" width="100%">
            <h3 class="text-center p-2">Hi, <b><?php if(isset($_SESSION["username"])){echo htmlspecialchars($_SESSION["username"]);}else{echo "Guest";} ?></b>. Welcome to the FMWS website</h3>
            
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #e3f2fd !important;">
                <div class="container container-fluid">
                <a class="navbar-brand" href="index.php">FMWS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Data Logs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="guidelines.php">Safety & Guidelines </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="helpline.php">Help Line</a>
                    </li>
                    <?php 
                        if(isset($_SESSION["role"]) && $_SESSION["role"] == "admin"){
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="admin.php">Admin Dash</a>';
                            echo '</li>';
                        }
                        if(!isset($_SESSION["username"])){
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="login.php">Login</a>';
                            echo '</li>';
                        }
                        if(!isset($_SESSION["username"])){
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="sign_up.php">Sign Up</a>';
                            echo '</li>';
                        }
                        if(isset($_SESSION["username"])){
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="logout.php" class="btn btn-danger text-center ml-3">Sign Out</a>';
                            echo '</li>';
                        }
                    ?>
                    </ul>
                    <span id="account" class="navbar-text"></span>
                </div>
                </div>
            </nav>
        </div>   

        <?php
            require_once "config.php";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $location = 0;
        ?>

        <div class="container">
            <!-- Data Log -->
            <div class="p-3">
                <h4 class="text-center">Data Log</h4>
                <!-- Select  Item -->
                <div class="m-2">

                <form class="p-2" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
                    <div class="row mb-3"> 
                        <label for="location" class="col-sm-3 col-form-label text-right">Select Area</label>
                        <div class="col-sm-6">
                            <select id="location" name="location" class="form-select btn btn-light" style="height:36px;min-width:200px">
                                <option class='text-left' type='button' value="All">All</option>
                                <?php
                                    $sql = "SELECT id, name FROM location";
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0){
                                        while($row = mysqli_fetch_assoc($result)){ 
                                            if($_SERVER["REQUEST_METHOD"] == "GET"){  
                                                if ($row["id"] == $_GET["location"] ){
                                                    echo "<option class='text-left' type='button' selected value='".$row["id"]."'>".$row["name"]."</option>";
                                                } else {
                                                    echo "<option class='text-left' type='button' value='".$row["id"]."'>".$row["name"]."</option>";
                                                }
                                            } else {
                                                echo "<option class='text-left' type='button' value='".$row["id"]."'>".$row["name"]."</option>";
                                            }
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="submit" class="btn btn-primary" style="height:36px" value="Go">
                        </div>
                    </div>
                </form>
                </div>
                

                <!-- Tabulated -->
                <div class="text-center justify-content-center" style="display:flex; align-items:center">
                    <table class="text-center table table-fit" style="">
                        <thead>
                            <tr>
                                <th scope="col">time</th>
                                <th scope="col">water level</th>
                                <th scope="col">flow rate</th>
                                <th scope="col">temperature</th>
                                <th scope="col">humidity</th>
                            </tr>
                        </thead>
                        <tbody id="myClientsBody">
                            <?php
                            $sql = "SELECT timestamp, water_level, flow_rate, temperature, humidity FROM data_log";
                                if($_SERVER["REQUEST_METHOD"] == "GET"){    
                                    $location = $_GET["location"];
                                    if ($location != "All"){
                                        $sql = "SELECT timestamp, water_level, flow_rate, temperature, humidity 
                                                FROM data_log  
                                                WHERE device_id 
                                                IN (SELECT id 
                                                    FROM device 
                                                    WHERE location_id = $location)";
                                    }
                                }

                                
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0){
                                   while($row = mysqli_fetch_assoc($result)){
                                       echo "<tr>";
                                       echo "<th scope='row'>".$row["timestamp"]."</th>"; 
                                       echo "<td>".$row["water_level"]."</td>"; 
                                       echo "<td>".$row["flow_rate"]."</td>";
                                       echo "<td>".$row["temperature"]."</td>";
                                       echo "<td>".$row["humidity"]."</td>";
                                       echo "</tr>";
                                    }
                                }
                            ?>  
                        </tbody>
                    </table>
                </div>

                <!-- Graphs -->
                <!-- Water Level -->
                <div style="margin-top:32px">
                    <h4 class="text-center">Graphs</h4>
                        <div class="text-center justify-content-center" style="display:flex; align-items:center">
                        <canvas id="chartJSContainer" width="700" height="600"></canvas>
                        
                        <script src="https://www.chartjs.org/dist/master/chart.js"></script>
                        </div>
                    </div>
                </div>  
            </div>

        </div>

        <script>
            $(document).ready(function () {
                showGraph();
            });       
            
            getParameter = (key) => {
                address = window.location.search
                parameterList = new URLSearchParams(address)
                return parameterList.get(key)
            }

            function showGraph(){
                console.log("HERE HERE");
                var location = getParameter("location");
                var url = "web_service/read_log.php?location="+location;
                $.post(url,
                function (data)
                {   
                    data = JSON.parse(JSON.stringify(data));
                    console.log(data);

                    var xValues = [];
                    var waterLevelArr = [];
                    var flowRateArr = [];
                    var temperatureArr = [];
                    var humidityArr = [];

                    for (var i in data){
                        xValues.push(data[i].timestamp);
                        waterLevelArr.push(data[i].water_level);
                        flowRateArr.push(data[i].flow_rate);
                        temperatureArr.push(data[i].temperature);
                        humidityArr.push(data[i].humidity);
                    }

                    var options = {
                        type: 'line',
                        responsive: true,
                        maintainAspectRatio: false,
                        data: {
                            labels: xValues,
                            datasets: [{
                                label: 'Water Level',
                                data: waterLevelArr,
                                borderColor: 'red',
                                backgroundColor: 'red'
                            },
                            {
                                label: 'Flow Rate',
                                data: flowRateArr,
                                borderColor: 'blue',
                                backgroundColor: 'blue',
                                yAxisID: 'y2',
                            },
                            {
                                label: 'Temperature',
                                data: temperatureArr,
                                borderColor: 'orange',
                                backgroundColor: 'orange',
                                yAxisID: 'y3',
                            },
                            {
                                label: 'Humidity',
                                data: humidityArr,
                                borderColor: 'purple',
                                backgroundColor: 'purple',
                                yAxisID: 'y4',
                            }
                            ]
                        },
                        options: {
                            scales: {
                            y: {
                                type: 'linear',
                                position: 'left',
                                stack: 'demo',
                                stackWeight: 1,
                                grid: {
                                borderColor: 'red'
                                }
                            },
                            y2: {
                                type: 'linear',
                                position: 'left',
                                offset: true,
                                stack: 'demo',
                                stackWeight: 1,
                                grid: {
                                borderColor: 'blue'
                                }
                            },
                            y3: {
                                type: 'linear',
                                position: 'left',
                                offset: true,
                                stack: 'demo',
                                stackWeight: 1,
                                grid: {
                                borderColor: 'orange'
                                }
                            },
                            y4: {
                                type: 'linear',
                                position: 'left',
                                offset: true,
                                stack: 'demo',
                                stackWeight: 1,
                                grid: {
                                borderColor: 'purple'
                                }
                            }
                            }
                        }
                    }

                    var ctx = document.getElementById('chartJSContainer').getContext('2d');
                    new Chart(ctx, options);
                });
            }
        </script>
    </body>
</html>