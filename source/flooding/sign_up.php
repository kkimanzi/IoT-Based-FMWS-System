<?php 
    $username = $phonenumber = $password = $confirmpassword = $dont_match = "";
    require_once "config.php";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        
        
        $username = sanitize($_POST["username"]);
        $phonenumber = sanitize($_POST["phonenumber"]);
        $password = $_POST["password"];
        $confirmpassword = $_POST["confirmpassword"];
        $location = $_POST["location"];

        if ($password != $confirmpassword){
            $dont_match = "Passwords do not match";
            echo "HERE OUTSIDE";
        } else {
            $hashedpassword = hash('sha256', $password);

            // prepare and bind
            $role = "user";
            $stmt = $conn->prepare("INSERT INTO user (username, phone, password, location_id, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $phonenumber, $hashedpassword, $location, $role);
            $stmt->execute();
                
            // Redirect user to welcome page
            header("location: login.php");
        }
        

    }

    function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>

<!DOCTYPE html>
<html lag="en">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Sign Up</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>  
        <script src="https://npmcdn.com/js-alert/dist/jsalert.min.js"></script>
    </head>
    <body>

        

        <div class="container" class="mb-5" style="max-width: 650px; margin-top: 32px;">
        <form class="card p-5" method="post" action="<?php echo htmlSpecialChars($_SERVER['PHP_SELF']); ?>">
                <h5 class="text-center pb-4">Sign Up</h5>
                <div class="row mb-3"> 
                    <label for="username" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="username" id="username" minlength="4" maxlength="20" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="phonenumber" class="col-sm-3 col-form-label">Cell Phone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="phonenumber" id="phonenumber" minlength="10" maxlength="13" value="<?php echo $phonenumber; ?>" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="password" class="col-sm-3 col-form-label">Password</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" name="password" id="password" minlength="6" maxlength="20" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="confirmpassword" class="col-sm-3 col-form-label">Confirm Password</label>
                    <div class="col-sm-9">
                        <?php echo (!empty($dont_match)) ? '<p class="alert alert-danger" role="alert">Passwords do not match</p>' : ''; ?>
                        <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" minlength="6" maxlength="20" required>
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
                    <div class="col-sm-12  position-relative">
                        <input id="createButton" type="submit" value="Sign Up" class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-primary" style="width: 4rem; height:2rem;">
                    </div>
                    <div class="col-sm-3"></div> 
                </div>
            </form>
        </div>

    </body>
</html>