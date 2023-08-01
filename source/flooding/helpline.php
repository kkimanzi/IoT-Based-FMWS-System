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

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

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

        <!-- My Crops -->
        <div class="p-3">
            <div class="container" width="100%">
                <h4 class="text-center" >Flooding Helpline</h4>
                <p>Flood support and counseling isn't easy. A lot of emotional distress and logistical issues are caused by the natural disaster.
                    That's why we provide a 24/7, 365-day-a-year hotline for you to reach out to. We have a team of multilingual counsellors and support staff ready for you at your time of need. 
                    The helpline is toll-free, and accessible to all residents in the UK. Call or text to get in touch.</p>
                <br>
                <p class="text-center h6">
                    <a href="tel:1-145-356-7489" style="font-size:16px" >1-145-356-7489</a>
                </p>
                <br>
                <h5>Counseling Services</h5>
                <p> We understand that floods not only cause physical damage, but have emotional impact too. Our staff are qualified counsellors with experience, and you should free to reach out. 
                    We value your privacy and confidentiality, and any information shared shall be treated with utmost care and respect.  
                    Our counsellors are ready to provide:</p> 
                    <ul>
                        <li>Emotional distress counseling </li>
                        <li>Tools on how to reorganize your life after the crisis </li>
                        <li>Tips for healthy coping</li>
                    </ul>
                    
                <br>
                <h5>Evacuation Services</h5>
                <p> We understand it might be difficult for you to logistically or mentally organize yourself in the case of an evacuation. 
                    We have a team on standby ready to help you mentally accept the reality of evacuation, and provide you with tips on how to evacuate. 
                    Our staff is here to: </p> 
                <ul>
                    <li>Advice you on evacuation procedures </li>
                    <li>Provide logistical help where attainable</li>
                    <li>Advice you on safe places to evacuate to</li>
                    <li>Advice you on how to live after evacuation</li>
                </ul>
                    
                <br>
                <p> For any other enquiries, feel free to call or text and we'll be here for you.</p>
                        
            </div>    
        </div>

    </body>
</php>
    