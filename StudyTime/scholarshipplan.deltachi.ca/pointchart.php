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
                    <h1>Point Chart</h1>
                    <table class="tablesaw" data-tablesaw-mode="swipe" data-tablesaw-mode-switch data-tablesaw-minimap>
                        <thead>
                            <tr>

                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="persist">First Name </th>

                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-sortable-default-col data-tablesaw-priority="persist"> Last Name </th>

                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="7"> Study Hours Points </th>

                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="2">Professor Points</div></th>

                                <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="3"> <div align="center">Tutoring Points</div></th>

                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="4"> <div align="center">Key To Success Points</div></th>

                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="5"> <div align="center">Study Hall Points</div></th>

                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="6"> <div align="center">Meeting With ABT Points</div></th>

                        <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="1"> <div align="center">Total Points</div></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $studentData = (ScholarshipDB::getInstance()->get_students());
                            $cnt = 0;
                            for ($k = 0; $k < count($studentData); $k++) {
                                $row = $studentData[$k];
                                ?>

                                <tr>
                                    <td><?php echo $row["first_name"] ?></td>

                                    <td><?php echo $row["last_name"] ?></td>

                                    <td><?php echo $row["study_hours"] ?></td>

                                    <td><?php echo $row["professor_advisor"] ?></td>

                                    <td><?php echo $row["tutoring"] ?></td>

                                    <td><?php echo $row["key_to_sucess"] ?></td>

                                    <td><?php echo $row["study_hall"] ?></td>

                                    <td><?php echo $row["meeting_abt"] ?></td>

                                    <td><?php echo $row["Total"] ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php require_once("Includes/css.php");
            ?>
        </body>
        <footer>
            <p align="center" style="color: white">Copyright © 2016 Sean Partridge</style></p>
            <p align="center" style="color: white">Contact information: <a href="mailto:sjpartri@ualberta.ca">
                    sjpartri@ualberta.ca</a>.</p>
        </footer>
    </html>
<?php } ?>
