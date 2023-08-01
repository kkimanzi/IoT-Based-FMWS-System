<?php
    require_once "config.php";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $locationId = $_GET["locationId"];
    
    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM location WHERE id=?");
    $stmt->bind_param("s", $locationId);
    $stmt->execute();

    // Redirect user to welcome page
    header("location: admin.php");

?>