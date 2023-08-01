<?php
header('Access-Control-Allow-Origin: *');
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization");
header('Access-Control-Max-Age: 86400');
header('Content-Type: application/json');

require_once "../config.php";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$location = $_GET["location"];

//$sql = "SELECT timestamp, water_level, flow_rate, temperature, humidity FROM data_log";
if ($location != "All"){
	$sql = "SELECT timestamp, water_level, flow_rate, temperature, humidity 
        FROM data_log  
        WHERE device_id 
        IN (SELECT id 
            FROM device 
        	WHERE location_id = $location)";
} else {
    
}

$result = mysqli_query($conn,$sql);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

mysqli_close($conn);

echo json_encode($data);
?>