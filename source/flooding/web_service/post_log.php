<?php
    header('Access-Control-Allow-Origin: *');
    header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
    header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    header ("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization");
    header('Access-Control-Max-Age: 86400');
    header('Content-Type: application/json');
    
    require_once "../config.php";
    require '../vendor/autoload.php';
    use Twilio\Rest\Client;
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        http_response_code(500);
        die("Connection failed: " . $conn->connect_error);
    }

    $device_id = $password = $waterLevel = $flowRate = $temperature = $humidity = "";
    if (isset($_POST["id"])){
        $device_id = $_POST["id"];
    } else {
        http_response_code(400);
        die("Bad Params");
    }
    if (isset($_POST["password"])){
        $password = $_POST["password"];
    } else {
        http_response_code(400);
        die("Bad Params");
    }
    if (isset($_POST["waterLevel"])){
        $waterLevel = $_POST["waterLevel"];
    }
    if (isset($_POST["flowRate"])){
        $flowRate = $_POST["flowRate"];
    }
    if (isset($_POST["temperature"])){
        $temperature = $_POST["temperature"];
    }
    if (isset($_POST["humidity"])){
        $humidity = $_POST["humidity"];
    }
    
    // Prepare a select statement
    $password = hash('sha256', $password);
    $sql = "SELECT id FROM device WHERE device_id ='".$device_id."' AND password = '$password'";
        
    $waterLevelL = $waterLevelU = $flowRateL = $flowRateU =$temperatureL = $temperatureU = $humidityL = $humidityU = "";
    
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        $id = $row["id"];
        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO data_log (device_id, water_level, flow_rate, temperature, humidity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id, $waterLevel, $flowRate, $temperature, $humidity);
        $stmt->execute();


        // Trigger check
        $sql = "SELECT waterLevelL, waterLevelU, flowRateL, flowRateU, temperatureL, temperatureU, humidityL, humidityU 
            FROM location  
            WHERE id 
            IN (SELECT location_id 
                FROM device 
                WHERE id = $id)";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
                $waterLevelL = $row["waterLevelL"];
                $waterLevelU = $row["waterLevelU"];
                $flowRateL = $row["flowRateL"];
                $flowRateU = $row["flowRateU"];
                $temperatureL = $row["temperatureL"];
                $temperatureU = $row["temperatureU"];
                $humidityL = $row["humidityL"];
                $humidityU = $row["humidityU"];

                // 1. If (water level > crtical thresh || flow rate > flowarate) automatically flag flood
                if ($waterLevel <= $row["waterLevelU"] || $flowRate >= $row["flowRateU"]){
                    if (!alertSent($id, "red")){
                        verifyAndSend($id, "red",$waterLevelL, $waterLevelU, $flowRateL, $flowRateU);
                    }
                }
                // 2. else if (water level > 1stthresh || flow rate > flowrate) based on past data, apply weighted probability
                else if ($waterLevel <= $row["waterLevelL"] || $flowRate >= $row["flowRateL"]){
                    if (!alertSent($id, "orange")){
                        verifyAndSend($id, "orange",$waterLevelL, $waterLevelU, $flowRateL, $flowRateU);                        
                    }

                }
                // 3. else all is well
        }

        http_response_code(200);        
  
    } else {
        http_response_code(401);
    }

    function verifyAndSend($id, $category,$waterLevelL, $waterLevelU, $flowRateL, $flowRateU){
        global $conn;
        // Use previous data to make decision (5 minutes)
        $dataFetchDate = new DateTime("5 minutes ago", new DateTimeZone('Europe/London'));
        $dataFetchTimestamp = $dataFetchDate->format('Y-m-d H:i:s');

        
        echo "inside verify $category</br>";
        echo "timestamp  = $dataFetchTimestamp";

        $sql = "SELECT * FROM data_log WHERE device_id=$id AND timestamp > '$dataFetchTimestamp' ORDER BY timestamp DESC";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) >= 8){
            $waterLevelAvg = $flowRateAvg = $numRows = 0;
            while ($row = mysqli_fetch_assoc($result)){
                $waterLevelAvg += $row["water_level"];
                $flowRateAvg += $row["flow_rate"];
                $numRows++;
            }
            

            // Average out
            $waterLevelAvg = $waterLevelAvg / $numRows;
            $flowRateAvg = $flowRateAvg / $numRows;
            echo "numrows = $numRows </br>";
            echo "AVG 1 = $waterLevelAvg";
            echo "AVG 2 = $flowRateAvg";

            echo "Averaged waterlevel = $waterLevelAvg</br>";
            echo "</br>Category = $category </br>";
            if ($category == "orange"){
                if ($waterLevelAvg <= $waterLevelL && $flowRateAvg >= $flowRateL ){
                    // Flagged an imminent flood
                    echo "Flagged category orange flood";
                    echo "</br>";
                    sendSms($id, "orange");
                    logAlertSent($id, "orange");
                }
            } else if ($category == "red"){
                if ($waterLevelAvg <= $waterLevelU || $flowRateAvg >= $flowRateU ){
                    // Flagged an imminent flood
                    echo "Flagged category orange flood";
                    echo "</br>";
                    sendSms($id, "red");
                    logAlertSent($id, "red");
                }
            }
            
        } else {
            // Too little data to make conclusive decision
        }
    }
    function alertSent($id, $category){
        
        global $conn;

        $sql = "SELECT * 
            FROM flood_alert  
            WHERE category = '$category'
            AND location_id 
            IN (SELECT location_id 
                FROM device 
                WHERE id = $id)
            
            ORDER BY time_issued DESC 
            LIMIT 1";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) >= 1){
            $row = mysqli_fetch_assoc($result);
            $timeIssued = new DateTime($row["time_issued"]);

            $currentTime = new DateTime("now", new DateTimeZone('Europe/London'));


            // Check if alert was sent less than 30 minutes ago
            $diffInMinutes = round(($currentTime->getTimestamp() - $timeIssued->getTimestamp()) / 60 , 2);
            if ($diffInMinutes >= 30){
                return false;
            } else {
                return true;
            }
        } else{
            return false;
        }

    }


    function sendSms($id, $category){
        global $conn;
        $sql = "SELECT * 
            FROM user  
            WHERE location_id 
            IN (SELECT location_id 
                FROM device 
                WHERE id = $id)";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) >= 1){
            // TODO:credentials here
            $sid = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
            $token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
            $client = new Client($sid, $token);

            
            while($row = mysqli_fetch_assoc($result)){ 
                $message = false;
                if ($row["phone"] != ""){
                    
                    do {
                        echo "Inside inside";
                        $body = "";
                        if ($category == "red"){
                            $body = "Hey ".$row["username"]."! A flood was detected in your region.";
                        } else {
                            $body = "Hey ".$row["username"]."! An imminent flood was detected in your region. Please stay alert";
                        }
                        $message = $client->messages->create(
                            // the number you'd like to send the message to
                            $row["phone"],
                            [
                                // A Twilio phone number you purchased at twilio.com/console
                                // TODO: twilio phone here
                                'from' => '+xxxxxxxxxxx',
                                // the body of the text message you'd like to send
                                    
                                'body' => $body
                            ]
                        );
                    } while (!$message);
                }

                echo "Sent message to ".$row["phone"];
                echo "</br>";

            }
            
        }
    }

    function logAlertSent($id, $category){
        global $conn;

        $sql = "SELECT location_id FROM device WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            $location_id = $row["location_id"];
            // prepare and bind
            $stmt = $conn->prepare("INSERT INTO flood_alert (location_id, category) VALUES (?, ?)");
            $stmt->bind_param("ss", $location_id, $category);
            $stmt->execute();
        }
        /*
        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO flood_alert (location_id) VALUES (?)");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        */
    }
?>