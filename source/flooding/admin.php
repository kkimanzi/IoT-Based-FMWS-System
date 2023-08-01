<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

    require_once "config.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }


?>
 
 <!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Home</title>
        <link rel="stylesheet" href="style.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>  
        <script src="https://npmcdn.com/js-alert/dist/jsalert.min.js"></script>
        
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
                <h3 class="text-center p-2">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome</h3>
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

        <!-- Locations -->
        <div class="p-3">
            <h4 class="text-center">Locations</h4>
            <div class="text-center justify-content-center" style="display:flex; align-items:center">
                <table class="text-center table table-fit" style="">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Edit</th>
                        </tr>
                    </thead>
                    <tbody id="locationsBody">
                        <?php
                            $sql = "SELECT id, name FROM location";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<tr>";
                                    echo "<td>".$row["name"]."</td>";
                                    echo "<td><a href='edit_location.php?locationId=".$row["id"]."' class='btn btn-primary text-center ml-3' style='height:2rem'>Edit</a></td>";
                                    echo "</tr>";
                                }
                            }
                            
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="nav justify-content-center" style="width:100%">
                <a href="create_location.php" class="btn btn-warning text-center ml-3">New Location</a>           
            </div>
        </div>    

        <!-- Devices -->
        <div class="p-3">
            <h4 class="text-center">Devices</h4>
            <div class="text-center justify-content-center" style="display:flex; align-items:center">
                <table class="text-center table table-fit" style="">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">location</th>
                            <th scope="col">Edit</th>
                        </tr>
                    </thead>
                    <tbody id="locationsBody">
                        <?php
                            $sql = "SELECT device.id, device.device_id, location.name FROM device
                                    INNER JOIN location
                                    ON device.location_id = location.id";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<tr>";
                                    echo "<th scope='row'>".$row["device_id"]."</th>";
                                    echo "<td>".$row["name"]."</td>";
                                    echo "<td><a href='edit_device.php?deviceId=".$row["id"]."' class='btn btn-primary text-center ml-3' style='height:2rem'>Edit</a></td>";
                                    echo "</tr>";
                                }
                            }
                            
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="nav justify-content-center" style="width:100%">
                <a href="create_device.php" class="btn btn-warning text-center ml-3">New Device</a>           
            </div>
        </div>  
    </body>
</html>
    