<?php
// Initialize the session
session_start();

?>
 
 <!DOCTYPE php>
<php lang="en">

    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Home</title>
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
            <h3 class="text-center p-2">Hi, <b><?php if(isset($_SESSION["username"])){echo htmlspecialchars($_SESSION["username"]);}else{echo "Guest";} ?></b>. Welcome</h3>
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
        </div>

        <!--  -->
        <div class="p-3">
            <div class="container" width="100%">
                <h4 class="text-center" >Safety & Preparedness & Guidelines</h4>
   
                <h5>On Receiving Flood Alert</h5> 
                <ul>
                    <li>Find safe shelter</li>
                    <li>Avoid walking, driving or swimming</li>
                    <li>Avoid bridges and cliffy areas</li>
                    <li>You may need to:
                        <li>Evacuate to a different area</li>
                        <li>Move to a higher ground</li>
                        <li>Remain where you are</li>
                    </li>
                </ul>   
                <br>

                <h5>Safety During a Flood</h5>
                <ul>
                    <li>Stay tuned for emergency information and instructions</li>
                    <li>Find shelter immediately if not already safely sheltered</li>
                    <li>Avoid swimming, driving or walking. <b>Remain indoors</b></li>
                    <li>Contact the emergency <a href="helpline.php">hotline</a> for support or counseling if need be</li>
                    <li>Move to a higher floor/ roof if trapped inside</li>
                </ul>
                <br>   

                <h5>Safety After Flood</h5>
                <ul>
                    <li>Move back to your home if cleared</li>
                    <li>Were protective clothing to protect yourself from probably contaminated water</li>
                    <li>Be cautious of wild animals that may have slithered in your house</li>
                    <li>Be cautious of electrocutoins</li>
                </ul>
                <br>

            </div>    
        </div>

    </body>
</php>
    