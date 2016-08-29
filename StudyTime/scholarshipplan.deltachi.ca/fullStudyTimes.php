<?php
ob_start();
require_once("Includes/db.php");
session_start();
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="Includes/css/bootstrap.min.css" rel="stylesheet">
        <script src='Includes/jquery/dist/jquery.min.js'></script>
        <script src="Includes/js/bootstrap.min.js"></script>

        <!-- Full Calender IO -->
        <link rel='stylesheet' href='Includes/fullcalendar/dist/fullcalendar.css' />
        <script src='Includes/moment/min/moment.min.js'></script>
        <script src='Includes/fullcalendar/dist/fullcalendar.js'></script>

        <script>


            $(document).ready(function () {
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();

                var calendar = $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    editable: false,
                    overlap: true,
                    events: "http://scholarshipplan.deltachi.ca/events.php",
                    eventRender: function (event, element) {
                        element.find('.fc-title').prepend("<br/> Location: ");

                    },
                });
            });
        </script>
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
                <div id='calendar'></div>
            </div>
        </div>
        <?php require_once("Includes/css.php"); ?>
        <footer>
            <p align="center" style="color: white">Copyright © 2016 Sean Partridge</p>
            <p align="center" style="color: white">Contact information: <a href="mailto:sjpartri@ualberta.ca">
                    sjpartri@ualberta.ca</a>.</p>
        </footer>
    </body>

</html>
