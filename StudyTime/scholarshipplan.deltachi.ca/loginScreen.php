<?php ob_start(); session_start();?>
<!DOCTYPE html>
<?php
require_once("Includes/db.php");
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="Includes/css/bootstrap.min.css" rel="stylesheet">
         <script src='Includes/jquery/dist/jquery.min.js'></script>
         <script src="Includes/js/bootstrap.min.js"></script>
     
        <title>Delta Chi Scholarship Web Application</title>
</head>
<body>
<nav class="navbar navbar-default navbar-inverse" role="navigation">
     <div class="container-fluid" id="navfluid">
       <div class="navbar-header">
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigationbar">
       <span class="sr-only">Toggle navigation</span>
       <span class="icon-bar"></span>
       <span class="icon-bar"></span>
       <span class="icon-bar"></span>
      </button>
           <a class="navbar-brand" href="#">Delta Chi Scholarship</a>
    
    </div>
<div class="collapse navbar-collapse" id="navigationbar">
  <form name="logon" class="navbar-form navbar-right" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Username" class="form-control" name="user" value="<?php echo (isset($_POST['logon'])) ? $_POST['user'] : '' ?>">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" class="form-control" name="userpassword">
                </div>
                <button type="submit" class="btn btn-success" name="logon">Sign In</button>
            </form>
</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>

 <div class="container theme-showcase" role="main">
    <div class="jumbotron" >
        <div class="container">
      
           <img src="Includes/logo.png" alt="Delta Chi Logo" class="img-responsive">
            
            <h1> Hello and Welcome! </h1>
            <p> You have either stumbled upon or intentionally found the Delta Chi Alberta (BETA Testing) Scholarship Page.
                 Our purpose is to shatter the preconceived notion of what a fraternity is by cultivating a diverse
                 community of individuals who continually strive to achieve excellence, confront new challenges, and
                 embrace new leadership opportunities together, thus developing a brotherhood that enables young men
                 to become gentlemen.
            </p>
            <p>
            <form action="http://deltachi.ca">
                <input type="submit" class="btn btn-primary btn-lg"  role="button" value="Visit Our Website">
            </p>
            
        </div>
    </div>
 </div>
       

   
   
              
                <?php
                if (isset($_REQUEST['logon'])){
                    if($_POST["user"] == "")
                    {
                        echo "<p style='color:red;'>Please enter a username<p>";
                    } else { echo "<br> <br> <br>"; }
                } else { echo "<br> <br> <br>"; }
                ?>
                
                <?php
                if (isset($_REQUEST['logon'])){
                    if($_POST["userpassword"] == "")
                    {
                        echo "<p style='color:red;'>Please enter a password<p>";
                    } else { echo "<br> <br> <br>"; }
                } else { echo "<br> <br> <br>"; }
                ?>
             
                <?php if (isset($_REQUEST['logon'])) {
                    if ($_POST["user"] != "" && $_POST["userpassword"] != "") {
                        $valid = (ScholarshipDB::getInstance()->is_valid_login($_POST["user"], $_POST["userpassword"]));
                        if ($valid == true) {
                            $_SESSION['user'] = $_POST["user"];
                            $_SESSION['screen'] = "Logon";
                            if (isset($_REQUEST['logon'])) {
                                header('Location: homeScreen.php');
                                exit();
                            }
                        } else {
                            echo "<br><p style='color:red;'>Incorrect username and/or password<p>";
                        }
                    }
                }?>
   
</body>
<?php   require_once("Includes/css.php");  ?>
<footer>
  <p align="center" style="color: white">Copyright © 2016 Sean Partridge</style></p>
  <p align="center" style="color: white">Contact information: <a href="mailto:sjpartri@ualberta.ca">
  sjpartri@ualberta.ca</a>.</p>
</footer>
</html>
