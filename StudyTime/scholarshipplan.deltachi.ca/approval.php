<?php
ob_start();
require_once("Includes/db.php");
session_start();
$user = $_SESSION['user'];
$student = (ScholarshipDB::getInstance()->get_user_role($user));
if ($user != 'false' && $student['role'] == 'a') {
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
                        <a class="navbar-brand" href="#">Delta Chi Scholarship</a>

                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="fullStudyTimes.php">Full Study Schedule</a></li>
                            <li><a href="submitAGrade.php">Submit Points</a></li>
                            <li><a href="pointchart.php">Point Chart</a></li>
                            <?php
                            $student = (ScholarshipDB::getInstance()->get_user_role($user));


                            if ($student["role"] == "a") {
                                echo "<li>";
                                echo $student['role'];
                                echo "</li>";
                                echo" <li class=\"dropdown\">
                                <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Administrator Options <span class=\"caret\"></span></a>
                                <ul class=\"dropdown-menu\">
                                    <li><a href=\"approval.php\">Point Approval</a></li>
                                    <li><a href=\"management.php\">Accounts Management</a></li>
                                   
                                </ul>";
                            }
                            ?>
                        </ul>

                        <form name="logout" class="navbar-form navbar-right" method="post">
                            <button type="submit" class="btn btn-success" value="Log Out" name="LogOut">Log Out</button>
                        </form>
                        <ul class="nav navbar-nav navbar-right">

                            <li><a href="studentAccountSetting.php">Account Settings</a></li>
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

    
            <form name="PointList" method="post">    

                   <div class="container theme-showcase" role="main">
                    <table  class = "pointTable">
                        <thead>
                            <tr>

                                <th> <div align="center">Type </div></th>

                        <th> <div align="center">Date </div></th>

                        <th> <div align="center">Student </div></th>

                        <th> <div align="center">Image </div></th>

                        <th> <div align="center">Points Requested </div></th>

                        <th> <div align="center">  </div></th>
                        </tr>
                        </thead>

                        <?php
                        $pointData = (ScholarshipDB::getInstance()->get_point_data());
                        $cnt = 0;
                        if (count($pointData) != 0){
                        for ($k = 0; $k < count($pointData); $k++) {
                            $row = $pointData[$k];
                            $student = (ScholarshipDB::getInstance()->get_studentinfo_byID($row["student_id"]));
                            ?>

                            <tr>
                                <td><div align="center"><?php echo $row["description"] ?></div></td>

                                <td><div align="center"><?php echo $row["date_created"] ?></div></td>

                                <td><div align="center"><?php echo $student["first_name"]; ?>  <?php echo $student["last_name"]; ?></div></td>


                                <td class="merchImg" align="center"><?php
                                    if ($row['image'] != null) {
                                        echo "<img class=\"imgSmall\" height=\"50\" width=\"50\" src=\"";
                                        echo $row['image'];
                                        echo "\">";
                                    } else {
                                        echo "No Image";
                                    }
                                    ?></td>

                                <td><div align="center"><input type="number" step="any" name="points2" placeholder="<?php echo number_format((float) $row["points"], 2, '.', ''); ?>"></div></td>

                                <?php
                                echo "<td><div align='center'><input class='btn btn-primary' type='submit' name='points$cnt' value='Approve' style='horizontal-align: middle;'/></div></td>";


                                if (isset($_POST["points$cnt"])) {

                                    ScholarshipDB::getInstance()->add_points_to_student($_POST["points2"], $row["description"], $row["student_id"]);
                                    ScholarshipDB::getInstance()->delete_points_fromStudyHours($row["id"]);
                                    ScholarshipDB::getInstance()->total_points($row["student_id"]);
                                   header("Refresh:0");
                                }
                                $cnt++;
                                ?>
                            </tr>
                          
                            <?php
                        }
                        }else{
                            ?>
                            <tr>
                              <td><div align="center">No New Approvals</div></td>
                              <td><div align="center">No New Approvals</div></td>
                              <td><div align="center">No New Approvals</div></td>
                              <td><div align="center">No New Approvals</div></td>
                              <td><div align="center">No New Approvals</div></td>
                              <td><div align="center">No New Approvals</div></td>
                              
                             </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </form>
            
            <div id="overlay"></div>
            <div id="overlayContent">
                <img id="imgBig" src="" alt=""  />
            </div>

            <script>

                $(".imgSmall ").each(function () {

                    var src = $(this).attr("src");
                    $(this).click(function () {
                        $("#imgBig").attr("src", src);
                        $("#overlay").show();
                        $("#overlayContent").show();
                    });

                });
                $("#imgBig").click(function () {
                    $("#imgBig").attr("src", "");
                    $("#overlay").hide();
                    $("#overlayContent").hide();
                });


            </script>


        </body>
        <?php require_once("Includes/css.php");
        ?>

    </html>

<?php } ?>
