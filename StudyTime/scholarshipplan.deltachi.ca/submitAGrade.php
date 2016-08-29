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
            <?php $pointID = ScholarshipDB::getInstance()->get_next_pointID(); ?> 
            <div class="container theme-showcase" role="main">
                <div class="jumbotron">
                    <h1>Submit Points</h1>

                    <p> Fill out the form below to submit points!</p>
                    <form name="postStudyTime"  method="post" enctype="multipart/form-data">
                        <table width="500" align="left" cellpadding="30">
                            <tr>
                                <td><div align="left">Point Type:</div></td>
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
                                <td><div align="left"><input type="radio" name="type" value="Keys To Sucess" <?php if (isset($_POST['save']) && $_POST['type'] == 'Keys To Success') { ?>checked<?php } ?>>Keys To Success</div></td>
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

                            <div class="form-group col-md-8">Date:<input type="date" class="form-control" name="date"></div>
                            <div class="form-group col-md-8">Number Of Points: <br><input type="number" step="any" name="points1" min="0.5" ></div>
                            <div class="form-group col-md-8">
                                Select image to upload:
                                <input type="file" name="fileToUpload" id="fileToUpload">
                            </div>


                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success" name="save">Submit</button>
                            </div>


                        </div>
                    </form>

                    <?php
                    if (!empty($_FILES["fileToUpload"])) {
                        $target_dir = "uploads/";
                        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                        if ($imageFileType != null) {

                            $uploadOk = 1;
                            // Check if image file is a actual image or fake image

                            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                            if ($check !== false) {
                                echo "File is an image - " . $check["mime"] . ".";
                                $uploadOk = 1;
                            } else {
                                echo "File is not an image.";
                                $uploadOk = 0;
                            }


                            // Check file size
                            if ($_FILES["fileToUpload"]["size"] > 500000) {
                                echo "Sorry, your file is too large.";
                                $uploadOk = 0;
                            }

                            // Allow certain file formats
                            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                                $uploadOk = 0;
                            }

                            // Check if $uploadOk is set to 0 by an error
                            if ($uploadOk == 0) {
                                echo "Sorry, your file was not uploaded.";

                                // if everything is ok, try to upload file
                            } else {
                                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {



                                    $studentID = (ScholarshipDB::getInstance()->get_student_id_by_name($user));

                                    $sucess = (ScholarshipDB::getInstance()->submit_points($pointID, $_POST["type"], $_POST["date"], $studentID, $target_file, $_POST["points1"]));
                                    header("Refresh:0");
                                } else {
                                    echo "Sorry, there was an error uploading your file.";
                                }
                            }
                        } else {
                            $studentID = (ScholarshipDB::getInstance()->get_student_id_by_name($user));

                            $sucess = (ScholarshipDB::getInstance()->submit_points($pointID, $_POST["type"], $_POST["date"], $studentID, $nothing, $_POST["points1"]));
                            header("Refresh:0");
                        }
                    }
                    ?>

                </div>
            </div>
        </body>
    <?php require_once("Includes/css.php");
    ?>
        <footer>
            <p align="center" style="color: white">Copyright © 2016 Sean Partridge</style></p>
            <p align="center" style="color: white">Contact information: <a href="mailto:sjpartri@ualberta.ca">
                    sjpartri@ualberta.ca</a>.</p>
        </footer>
    </html>
<?php } ?>

