<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lag="en">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Edit Location</title>
        <link rel="stylesheet" href="style.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>  
        <script src="https://npmcdn.com/js-alert/dist/jsalert.min.js"></script>
    </head>
    <body>

        <?php
            require_once "config.php";
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
               die("Connection failed: " . $conn->connect_error);
            }

            
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                // Create device
                $locationName = sanitize($_POST["locationName"]);
                $serverId = $_SESSION["id"];

                $waterLevelL = $_POST["waterLevelL"];
                $waterLevelU = $_POST["waterLevelU"];
                $flowRateL = $_POST["flowRateL"];
                $flowRateU = $_POST["flowRateU"];
                $temperatureL = $_POST["temperatureL"];
                $temperatureU = $_POST["temperatureU"];
                $humidityL = $_POST["humidityL"];
                $humidityU = $_POST["humidityU"];

                // prepare and bind
                $stmt = $conn->prepare("INSERT INTO location (name, waterLevelL, waterLevelU, flowRateL, flowRateU, temperatureL, temperatureU, humidityL, humidityU) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $locationName, $waterLevelL, $waterLevelU,
                    $flowRateL, $flowRateU, $temperatureL, $temperatureU, $humidityL,$humidityU);
                $stmt->execute();

                // Redirect user to welcome page
                header("location: admin.php");
            }

            function sanitize($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }       
        
        ?>

        

        <div class="shadow-sm p-2 mb-5 bg-body rounded">
            <div class="container" width="100%">
            <div class="nav justify-content-center" style="width:100%">
                    <h3><a href="my_server.php" class="btn text-center ml-3">Home</a></h3>
                </div>
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
        </div>
        
        <div class="container" class="mb-5" style="max-width: 650px; margin-top: 32px;">
            <form class="card p-5" method="post" action="<?php echo htmlSpecialChars($_SERVER['PHP_SELF']); ?>">
                <h5 class="text-center pb-4">Location Details</h5>
                <input type="text" style="display:none" id="locationIdInput" name="locationIdInput">
                <div class="row mb-3"> 
                    <label for="locationName" class="col-sm-3 col-form-label">location Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="locationName" id="locationName" >
                    </div>
                </div>
                <div class="row mb-3"> 
                    <h6 class="text-center pb-4">Water Level</h6>
                    <label for="waterLevelL" class="col-sm-3 col-form-label">1st Thershold</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="waterLevelL" id="waterLevelL" >
                    </div>
                    <label for="waterLevelU" class="col-sm-3 col-form-label">Critical Threshold</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="waterLevelU" id="waterLevelU" >
                    </div>
                </div>
                <div class="row mb-3"> 
                    <h6 class="text-center pb-4">Flow Rate</h6>
                    <label for="flowRateL" class="col-sm-3 col-form-label">1st Thershold</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="flowRateL" id="flowRateL" >
                    </div>
                    <label for="flowRateU" class="col-sm-3 col-form-label">Critical Threshold</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="flowRateU" id="flowRateU" >
                    </div>
                </div>
                <div class="row mb-3"> 
                    <h6 class="text-center pb-4">Temperature (Usual Operating Conditions)</h6>
                    <label for="temperatureL" class="col-sm-3 col-form-label">Lower Limit</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="temperatureL" id="temperatureL">
                    </div>
                    <label for="temperatureU" class="col-sm-3 col-form-label">Upper Limit</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="temperatureU" id="temperatureU">
                    </div>
                </div>
                <div class="row mb-3"> 
                    <h6 class="text-center pb-4">Humidity (Usual Operating COnditions)</h6>
                    <label for="humidityL" class="col-sm-3 col-form-label">Lower Limit</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="humidityL" id="humidityL">
                    </div>
                    <label for="humidityU" class="col-sm-3 col-form-label">Upper Limit</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="humidityU" id="humidityU">
                    </div>
                </div>


                <div class="row mb-3" style="margin-top: 40px;"> 
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6  position-relative">
                        <input id="create" type="submit" class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-warning" style="width: 5rem; height:2rem;" value="Create">
                        
                    </div>
                    <div class="col-sm-3"></div>
                    
                </div>
            </form>
        </div>
    </body>
</html>