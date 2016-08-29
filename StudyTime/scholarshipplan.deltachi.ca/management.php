<?php
ob_start();
require_once("Includes/db.php");
session_start();
$user = $_SESSION['user'];
$posted = false;
$posted_upd = false;
$posted_del = false;
$student = (ScholarshipDB::getInstance()->get_user_role($user));
if ($user != 'false' && $student['role'] == 'a') {
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
                    <h2>Create New User</h2>
                    <h3>Fill Out the form completely to create a new user</h3>
                    <div class="row">
                        <?php
                        $studentID = ScholarshipDB::getInstance()->get_next_studentID();
                        $role = "s";


                        if (isset($_REQUEST['submitUser'])) {

                            $sucess_1 = (ScholarshipDB::getInstance()->submitStudent($studentID, $_POST["first_name"], $_POST["last_name"], $_POST["GPA"]));
                            $sucess_2 = (ScholarshipDB::getInstance()->submitUser($_POST["user_name"], $_POST["password"], $role, $studentID));
                            $posted = true;
                        }
                        if ($posted) {

                            echo "<div class=\"alert alert-success\">
  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
  <strong>Success!</strong> Created New User.
</div>";


                            header("Refresh:1");
                        }
                        ?>
                        <form name="postUser"  method="post">

                            <div class="form-group col-md-8">First Name:<input type="text" class="form-control" name="first_name"></div>
                            <div class="form-group col-md-8">Last Name:<input type="text" class="form-control" name="last_name"></div>
                            <div class="form-group col-md-8">GPA:<input type="number" step="any" class="form-control" name="GPA"></div>
                            <div class="form-group col-md-8">Username:<input type="text" class="form-control" name="user_name"></div>
                            <div class="form-group col-md-8">Password For User:<input type="password" class="form-control" name="password"></div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success" name="submitUser">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>



            </div>
            <div class="container theme-showcase" role="main">
                <div class="jumbotron">
                    <h2>Update GPA</h2>
                    <div class="row">
                        <form name="updateStudent"  method="post">
                            <?php
                            $student = $_POST["student"];
                            $arr = array($student);
                            $full_name = implode(" ", $arr);

                            $name = explode(" ", $full_name);
                            $first_name = $name[0];
                            $last_name = $name[1];
                            if (isset($_REQUEST['updateGPA'])) {
                                $studentID = (ScholarshipDB::getInstance()->get_student_id_by_full_name($first_name, $last_name));

                                ScholarshipDB::getInstance()->updateGPA($studentID, $_POST["updateGPAtext"]);
                                $posted_upd = true;
                            }
                            if ($posted_upd) {

                                echo "<div class=\"alert alert-success\">
                                  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                  <strong>Success!</strong> GPA Was Updated.
                                  </div>";


                                header("Refresh:1");
                            }
                            ?>
                            <div class="form-group col-md-12">
                                <select name="student">

                                    <?php
                                    $studentData = (ScholarshipDB::getInstance()->get_students());
                                    $cnt = 0;
                                    for ($k = 0; $k < count($studentData); $k++) {
                                        $row = $studentData[$k];
                                        echo "<option name =";
                                        echo $row["student_id"];
                                        echo "value=";
                                        echo $row["student_id"];
                                        echo">";
                                        echo $row["first_name"];
                                        echo " ";
                                        echo $row["last_name"];
                                        echo"</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2">GPA:<input type="number" step="any" class="form-control" name="updateGPAtext"></div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary" name="updateGPA">Update GPA</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="container theme-showcase" role="main">
                <div class="jumbotron">
                    <h2>Delete a Student</h2>
                    <div class="row">
                        <form name="deleteStudent"  method="post">
                            <?php
                            $student = $_POST["student"];
                            $arr = array($student);
                            $full_name = implode(" ", $arr);

                            $name = explode(" ", $full_name);
                            $first_name = $name[0];
                            $last_name = $name[1];
                            if (isset($_REQUEST['deleteUser'])) {
                                $studentID = (ScholarshipDB::getInstance()->get_student_id_by_full_name($first_name, $last_name));
                                $delete_2 = (ScholarshipDB::getInstance()->deleteUser($studentID));
                                $delete_1 = (ScholarshipDB::getInstance()->deleteStudent($studentID));

                                $posted_del = true;
                            }
                            if ($posted_del) {

                                echo "<div class=\"alert alert-success\">
                                  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                  <strong>Success!</strong> Student Was Deleted.
                                  </div>";


                                header("Refresh:0");
                            }
                            ?>
                            <div class="form-group col-md-12">
                                <select name="student">

                                    <?php
                                    $studentData = (ScholarshipDB::getInstance()->get_students());
                                    $cnt = 0;
                                    for ($k = 0; $k < count($studentData); $k++) {
                                        $row = $studentData[$k];
                                        echo "<option name =";
                                        echo $row["student_id"];
                                        echo "value=";
                                        echo $row["student_id"];
                                        echo">";
                                        echo $row["first_name"];
                                        echo " ";
                                        echo $row["last_name"];
                                        echo"</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-danger" name="deleteUser">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="container theme-showcase" role="main">
                <div class="jumbotron">
                    <h2>Add Administrator</h2>

                    <div class="row">
                        <form name="changeRole"  method="post">

                            <div class="form-group col-md-12">
                                <br>
                                <h4>Current Administrators:</h4>
                                <br>
                                <?php
                                $adminData = (ScholarshipDB::getInstance()->get_admins());
                                for ($k = 0; $k < count($adminData); $k++) {
                                    $row = $adminData[$k];

                                    $admin = (ScholarshipDB::getInstance()->get_student_by_studentID($row["student_id"]));
                                    echo "<font color=\"red\">$admin[0]</font>";
                                    echo " ";
                                    echo "<font color=\"red\">$admin[1]</font>";
                                    echo "<br>";
                                }
                                ?>
                                <br><br>
                                <h4>Update/Downgrade a Student's Role</h4>
                                <h5>Select Student:</h5>
                                <select name="student">

                                    <?php
                                    $studentData = (ScholarshipDB::getInstance()->get_students());
                                    $cnt = 0;
                                    for ($k = 0; $k < count($studentData); $k++) {
                                        $row = $studentData[$k];
                                        echo "<option name =";
                                        echo $row["student_id"];
                                        echo "value=";
                                        echo $row["student_id"];
                                        echo">";
                                        echo $row["first_name"];
                                        echo " ";
                                        echo $row["last_name"];
                                        echo"</option>";
                                    }
                                    ?>
                                </select>
                                <br><br>

                                Update Role:
                                <br>
                                <div><input type="radio" name="role" value="admin_role" <?php if (isset($_POST['save']) && $_POST['type'] == 'Study Hours') { ?>checked<?php } ?>>Administrator</div>
                                <div><input type="radio" name="role" value="student_role" <?php if (isset($_POST['save']) && $_POST['type'] == 'Study Hall') { ?>checked<?php } ?>>Student</div>

                            </div>


                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary" name="update">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                if (isset($_REQUEST['update'])) {
                    $student = $_POST["student"];
                    $arr = array($student);
                    $full_name = implode(" ", $arr);

                    $name = explode(" ", $full_name);
                    $first_name = $name[0];
                    $last_name = $name[1];
                    $student_id = (ScholarshipDB::getInstance()->get_student_id_by_full_name($first_name, $last_name));

                    if ($_POST["role"] == "student_role") {
                        $role = "s";

                        ScholarshipDB::getInstance()->update_role($role, $student_id);
                        header("Refresh:0");
                    } else if ($_POST["role"] == "admin_role") {
                        $role = "a";

                        ScholarshipDB::getInstance()->update_role($role, $student_id);
                        header("Refresh:0");
                    }
                }
                ?>
            </div>
            <div class="container theme-showcase" role="main">
                <div class="jumbotron">
                    <h2>Change/Reset Points</h2>
                    <h4>Change a Student's Points Or Reset All Points In Database</h4>

                    <p> Fill out the form below to submit points!</p>
                    <?php
                    if (isset($_REQUEST['resetPoints'])) {

                        ScholarshipDB::getInstance()->reset_points();
                    }
                    if (isset($_REQUEST['updatePoints'])) {
                        $student = $_POST["student"];
                        $arr = array($student);
                        $full_name = implode(" ", $arr);

                        $name = explode(" ", $full_name);
                        $first_name = $name[0];
                        $last_name = $name[1];
                        $student_id = (ScholarshipDB::getInstance()->get_student_id_by_full_name($first_name, $last_name));
                        $description = $_POST["type"];
                        $points = $_POST["points"];

                        $sucess = (ScholarshipDB::getInstance()->update_points_student($points, $description, $student_id));
                        ScholarshipDB::getInstance()->total_points($student_id);
                        $posted = true;
                    }
                    if ($posted) {

                        echo "<div class=\"alert alert-success\">
  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
  <strong>Success!</strong> Updated Points Successfully.
</div>";


                        header("Refresh:3");
                    }
                    ?>
                    <form name="changePoints"  method="post" enctype="multipart/form-data">
                        <div class= "row">
                            <div class="form-group col-md-12">
                                <h5>Select Student:</h5>
                                <select name="student">

                                    <?php
                                    $studentData = (ScholarshipDB::getInstance()->get_students());
                                    $cnt = 0;
                                    for ($k = 0; $k < count($studentData); $k++) {
                                        $row = $studentData[$k];
                                        echo "<option name =";
                                        echo $row["student_id"];
                                        echo "value=";
                                        echo $row["student_id"];
                                        echo">";
                                        echo $row["first_name"];
                                        echo " ";
                                        echo $row["last_name"];
                                        echo"</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <table width="500" align="left" cellpadding="30">



                            <tr>
                                <td><div align="left">Point Category:</div></td>
                                <td><div align="left"><input type="radio" name="type" value="Study Hours" <?php if (isset($_POST['save']) && $_POST['type'] == 'Study Hours') { ?>checked<?php } ?>>Study Hours</div></td>
                            </tr>
                            <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left"><input type="radio" name="type" value="Study Hall" <?php if (isset($_POST['save']) && $_POST['type'] == 'Study Hall') { ?>checked<?php } ?>>Study Hall</div></td>
                            </tr>
                            <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left"><input type="radio" name="type" value="Professor/Advisor Visit" <?php if (isset($_POST['save']) && $_POST['type'] == 'Professor/Advisor Visit') { ?>checked<?php } ?>>Professor/Advisor Visit</div></td>
                            </tr>
                            <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left"><input type="radio" name="type" value="Tutoring" <?php if (isset($_POST['save']) && $_POST['type'] == 'Tutoring') { ?>checked<?php } ?>>Tutoring</div></td>
                            </tr>
                            <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left"><input type="radio" name="type" value="Keys To Success" <?php if (isset($_POST['save']) && $_POST['type'] == 'Keys To Success') { ?>checked<?php } ?>>Keys To Success</div></td>
                            </tr>
                            <tr>
                                <td><div align="left"></div></td>
                                <td><div align="left"><input type="radio" name="type" value="Meeting With ABT" <?php if (isset($_POST['save']) && $_POST['type'] == 'Meeting With ABT') { ?>checked<?php } ?>>Meeting With ABT</div></td>
                            </tr>
                            <tr >
                                <td><div align="left"></div></td>
                                <td><div align="left" style="color:red; display: <?php if (isset($_POST['save']) && $_POST['type'] == '') { ?>inline <?php } else { ?> none <?php } ?>">*Required - Select one</div></td>
                            </tr>
                        </table>
                        <div class="row">




                            <div class="form-group col-md-8">Change Number Of Points To: <br><input type="number" name="points" min="0" ></div>
                            <div align="left" style="color:red; display: <?php if (isset($_POST['save']) && $_POST['points'] == '') { ?>inline <?php } else { ?> none <?php } ?>">*Required</div>


                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success" name="updatePoints">Submit</button>
                            </div>


                        </div>
                    </form>
                    <div class="row">
                        <form name="changePoints"  method="post">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-danger" name="resetPoints">Reset All Points</button>
                            </div>
                        </form>
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

<?php } ?>

