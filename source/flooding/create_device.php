<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("device: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lag="en">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Edit device</title>
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
                $password = $_POST["password"];
                $password = hash('sha256', $password);
                $location = $_POST["location"] ;
                $deviceId = $_POST["deviceId"];
                
                // prepare and bind
                $stmt = $conn->prepare("INSERT INTO device (device_id, password, location_id) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $deviceId, $password, $location);
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
                <h5 class="text-center pb-4">Device Details</h5>
                <input type="text" style="display:none" id="deviceIdInput" name="deviceIdInput">
                <div class="row mb-3"> 
                    <label for="deviceId" class="col-sm-3 col-form-label">Decive Id</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="deviceId" id="deviceId" >
                    </div>
                </div>
                <div class="row mb-3"> 
                    <label for="password" class="col-sm-3 col-form-label">Device Password</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="password" id="password" >
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="location" class="col-sm-3 col-form-label text-right">Select Area</label>
                    <div class="col-sm-9">
                        <select id="location" name="location" class="form-select btn btn-light" style="height:36px;min-width:200px">
                            
                            <?php
                                $sql = "SELECT id, name FROM location";
                                $result = mysqli_query($conn, $sql);

                                if (mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_assoc($result)){ 
                                        echo "<option class='text-left' type='button' selected value='".$row["id"]."'>".$row["name"]."</option>";
                                    }
                                }
                            ?>
                        </select>
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