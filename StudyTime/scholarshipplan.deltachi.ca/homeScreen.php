<?php
ob_start();
require_once("Includes/db.php");
session_start();
$user = $_SESSION['user'];
$posted = false;
if ($user != 'false') {
    ?>
    <!DOCTYPE html>
    <html>

        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
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
                        defaultView: 'agendaDay',
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
            <?php $eventID = ScholarshipDB::getInstance()->get_next_eventID(); ?> 
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
                    <h1> Welcome <?php
                        $student = (ScholarshipDB::getInstance()->get_personal_info($user));
                        echo $student['first_name'];
                        ?>!</h1>
                    <h3 style="color:blue;"><img src="Includes/image.png" height="60" width="60"> The Chapter's Current GPA Is: <?
                        $Avg_Grade = (ScholarshipDB::getInstance()->avg_gpa());
                        echo number_format((float) $Avg_Grade, 2, '.', '');
                        ?>
                    </h3>
                    <h2>Post a Study Time</h2>
                    <p> Fill out the form below and submit a new study time for the calendar!</p>
                    <form name="postStudyTime"  method="post">
                        <div class="row">
                            <?php
                            if (isset($_REQUEST['submitStudyTime'])) {
                                if ($_POST["location"] != "" && $_POST["startDate"] != "" && $_POST["startTime"] != "" && $_POST["endTime"] != "") {
                                    $sucess = (ScholarshipDB::getInstance()->submit_studytime($eventID, $_POST["location"], $_POST["startDate"], $_POST["startTime"], $_POST["endTime"]));
                                    $posted = true;
                                }
                            }
                            if ($posted) {

                                echo "<div class=\"alert alert-success\">
                                  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                  <strong>Success!</strong> Created New Post.
                                  </div>";


                                header("Refresh:0");
                            }
                            ?>
                            <div class="form-group col-md-8">
                                <input type="text" placeholder="Location" class="form-control" name="location">
                                <div align="left" style="color:red; display:<?php if (isset($_POST['submitStudyTime']) && $_POST['Location'] == '') { ?>inline <?php } else { ?> none <?php } ?>" >* Required</div>
                            </div>


                            <div class="form-group col-md-8">Start Date:<input type="date" class="form-control" name="startDate">
                                <div align="left" style="color:red; display:<?php if (isset($_POST['submitStudyTime']) && $_POST['startDate'] == '') { ?>inline <?php } else { ?> none <?php } ?>" >* Required</div>
                            </div>
                            <div class="form-group col-md-8">Start Time:<input type="time" class="form-control" name="startTime">
                                <div align="left" style="color:red; display:<?php if (isset($_POST['submitStudyTime']) && $_POST['startTime'] == '') { ?>inline <?php } else { ?> none <?php } ?>" >*Required</div>
                            </div>



                            <div class="form-group col-md-8">End Time:<input type="time" class="form-control" name="endTime">
                                <div align="left" style="color:red; display:<?php if (isset($_POST['submitStudyTime']) && $_POST['endTime'] == '') { ?>inline <?php } else { ?> none <?php } ?>" >*Required</div>
                            </div>


                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success" name="submitStudyTime">Submit</button>
                            </div>

                        </div>
                    </form>

                </div>
                <div class="jumbotron container-fluid">
                    <div class="page-header">
                        <h1> Daily Study Schedule </h1>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10" id='calendar'></div>
                        </div>
                        <br><br><br><br>
                    </div>
                </div>
            </div>


        </body>
        <?php require_once("Includes/css.php"); ?>
        <footer>
            <p align="center" style="color: white">Copyright © 2016 Sean Partridge</style></p>
            <p align="center" style="color: white">Contact information: <a href="mailto:sjpartri@ualberta.ca">
                    sjpartri@ualberta.ca</a>.</p>
        </footer>
    </html>
    <?php
} else {

    header($_SERVER["SERVER_PROTOCOL"] . " 401 Not Authoried", true, 404);
    echo "401 Not Authoried";
}
?>