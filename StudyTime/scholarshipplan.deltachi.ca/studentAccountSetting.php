<?php
ob_start();
require_once("Includes/db.php");

session_start();
$user = $_SESSION['user'];
if ($user != 'false') {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="Includes/css/bootstrap.min.css" rel="stylesheet">
            <link href="Includes/tablesaw-master/dist/tablesaw.css" rel="stylesheet">
            <script src='Includes/jquery/dist/jquery.min.js'></script>
            <script src="Includes/js/bootstrap.min.js"></script>
            <script src="Includes/tablesaw-master/dist/tablesaw.js"></script>
            <script src="Includes/tablesaw-master/dist/tablesaw-init.js"></script>
            <link rel="stylesheet" href="demo.css">
        </head>
        <body>
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="homeScreen.php">Delta Chi Scholarship</a>

                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="fullStudyTimes.php">Full Study Schedule</a></li>
                            <li><a href="submitAGrade.php">Submit Points</a></li>
                            <li><a href="pointchart.php">Point Chart</a></li>
                            <?php
                            $student = (ScholarshipDB::getInstance()->get_user_role($user));


                            if ($student["role"] == "a") {

                                echo" <li class=\"dropdown\">
                                <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Administrator Options <span class=\"caret\"></span></a>
                                <ul class=\"dropdown-menu\">
                                    <li><a href=\"approval.php\">Point Approval</a></li>
                                    <li><a href=\"management.php\">Accounts Management</a></li>
                                   
                                </ul>";
                            }
                            ?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="studentAccountSetting.php">Account Settings</a></li>
                            <li><form name="logout" class="navbar-form navbar-right" method="post">
                                    <button type="submit" class="btn btn-success" value="Log Out" name="LogOut">Log Out</button>
                                </form><li>



                        </ul>
                    </div>
                    <?php
                    if (isset($_POST['LogOut'])) {
                        unset($_SESSION['user']);
                        $_SESSION['user'] = "false";
                        header('Location: loginScreen.php');
                        exit();
                    }
                    ?>
                </div>




            </nav>
            <div class="container theme-showcase" role="main">
                <div class="jumbotron">
                    <h2>Manage Account</h2>
                    <br>
                    <div class="row" align="center">
                        <h3>
                            First Name: <?php
                            $student = (ScholarshipDB::getInstance()->get_personal_info($user));
                            echo $student['first_name'];
                            ?>

                        </h3>
                        <h3>
                            Last Name: <?php
                            echo $student['last_name'];
                            ?>

                        </h3>
                        <h3>
                            GPA: <?php
                            echo number_format((float) $student['GPA'], 2, '.', '');
                            ;
                            ?>

                        </h3>
                    </div>
                </div>
            </div>
            <div class="container theme-showcase" role="main">
                <div class="jumbotron">
                    <h2>Update Password</h2>
                    <?php

                    if (isset($_REQUEST['submitPassword'])) {
                        if ($_POST["password"] != '' & $_POST["password2"] != '' & $_POST["password"] == $_POST["password2"]) {
                            $sucess_1 = (ScholarshipDB::getInstance()->changePassword($student["student_id"],$_POST["password"]));
                            $posted = true;
                        } else {
                            echo "<div class=\"alert alert-danger\">
                                  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                  <strong>ERROR!</strong> Passwords Do Not Match.
                                  </div>";
                        }
                    }
                        if ($posted) {

                            echo "<div class=\"alert alert-success\">
                                  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                  <strong>Success!</strong> Password Changed.
                                  </div>";


                            header("Refresh:1");
                        }
                        ?>
                        <form name="postPassword"  method="post">

                            <div class="row">
                                <div class="form-group col-md-8">Password: <input type="password" class="form-control" name="password"></div>
                                <div class="form-group col-md-8">Confirm Password: <input type="password" class="form-control" name="password2"></div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-success" name="submitPassword">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php require_once("Includes/css.php");
                ?>
            </body>
        </html>
    <?php } ?>