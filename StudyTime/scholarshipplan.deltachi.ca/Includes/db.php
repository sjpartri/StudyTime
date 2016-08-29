<?php

class ScholarshipDB extends mysqli {

    //single instance of self shared among all instances
    private static $instance = null;
    // db connection config vars
    private $user = "scholarshipchair";
    private $pass = "Cornell1890";
    private $dbName = "scholarship";
    private $dbHost = "scholarship.deltachi.ca:3306";
    private $con = null;

    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    // private constructor
    // private constructor
    private function __construct() {

        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }

    public function is_valid_login($user, $password) {

        //$name = $this->real_escape_string($user);
        //$password1 = $this->real_escape_string($password);
        $result = $this->query("select * from scholarship.users WHERE user_name = '$user' AND password = '$password'");

        $count = 0;
        while (($row = $result->fetch_array() != false)) {
            $count++;
        }
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function updateGPA($studentID,$updateGPA){
        $sql = "UPDATE scholarship.students SET GPA= '$updateGPA' WHERE student_id='$studentID'";
      
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }

    public function fetch_events() {
        //List of events
        $json = array();

        $requete = "SELECT * FROM scholarship.events ORDER BY id";

        // Execute the query
        $resultat = $this->query($requete) or die(print_r($this->errorInfo()));


        // sending the encoded result to success page
        return json_encode($resultat->fetchAll(PDO::FETCH_ASSOC));
    }
    public function get_next_studentID(){
        $query = "SELECT MAX(student_id) FROM scholarship.students";
        $result = $this->query($query);
        $id = 0;
        while (($row = $result->fetch_array()) != false) {
            $id = $row["MAX(student_id)"];
        }
        return $id + 1;
    }
    public function get_next_eventID() {
        $query = "SELECT MAX(ID) FROM scholarship.events";
        $result = $this->query($query);
        $id = 0;
        while (($row = $result->fetch_array()) != false) {
            $id = $row["MAX(ID)"];
        }
        return $id + 1;
    }
    public function get_next_pointID() {
        $query = "SELECT MAX(ID) FROM scholarship.StudyHours";
        $result = $this->query($query);
        $id = 0;
        while (($row = $result->fetch_array()) != false) {
            $id = $row["MAX(ID)"];
        }
        return $id + 1;
    }
   

    public function submit_studytime($eventID, $location, $startDate, $startTime, $endTime) {
        $start = $startDate . "T" . $startTime;
        $end = $startDate . "T" . $endTime;
        $sql = "INSERT INTO scholarship.events VALUES ('$eventID', '$start', '$end', '$location')";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    public function submitStudent($studentID,$first_name,$last_name,$GPA ){
        $sql = "INSERT INTO scholarship.students VALUES ('$studentID','$first_name','$last_name', '0','0','0','0','0','0','0','$GPA')";
        
         if ($this->query($sql) === TRUE) {
            
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    public function submitUser($user_name,$password,$role,$studentID){
         $sql = "INSERT INTO scholarship.users VALUES ('$user_name', '$password', '$role', '$studentID')";
          if ($this->query($sql) === TRUE) {
            
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    public function total_points($student_id) {
         $sql = "UPDATE scholarship.students SET Total= study_hours+professor_advisor+tutoring+key_to_sucess+study_hall+meeting_abt"
              . " WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }

    public function study_table_results($user) {
        $student_id = ScholarshipDB::getInstance()->get_student_id_by_name($user);
    }

    public function submit_points($pointID, $type, $date, $studentID, $target_file, $points) {
       
        $sql = "INSERT INTO scholarship.StudyHours VALUES ($pointID,'$type','$date', '$studentID','$target_file','$points')";
        if ($this->query($sql) === TRUE) {
          
              echo "<div class=\"alert alert-success\">
                                  <a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>
                                  <strong>Success!</strong> Created New Post.
                                  </div>";

        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }

    public function get_student_id_by_name($name) {
        $query = "SELECT STUDENT_ID FROM scholarship.users WHERE user_name = '$name'";
        $result = $this->query($query);
        $row = $result->fetch_array();
        return $row[0];
    }
    
    public function get_admins(){
        $query = "select student_id from scholarship.users where role='a'";
        $result = $this->query($query);
        $resultArray = $result->fetch_all(MYSQLI_ASSOC);
        return $resultArray;
    }
    public function get_student_by_studentID($student_id){
     
         $query = "select first_name,last_name from scholarship.students where student_id = '$student_id'";
         $result = $this->query($query);
         $row = $result->fetch_array();
        return $row;        
    }
    public function get_user_role($user){
        $query = "select role from scholarship.users where user_name = '$user'";
        $result = $this->query($query);
         $row = $result->fetch_array();
        return $row;  
    }
    public function get_students(){
        $query =  "select * from scholarship.students ORDER BY total DESC";
        $result = $this->query($query);
        $resultArray = $result->fetch_all(MYSQLI_ASSOC);
        return $resultArray;
    }
    public function update_role($role,$student_id){
        $sql = "UPDATE scholarship.users SET role= '$role' WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    public function get_point_data(){
        $query =  "select * from scholarship.StudyHours";
        $result = $this->query($query);
        $resultArray = $result->fetch_all(MYSQLI_ASSOC);
   $row = $resultArray[1];
           
    
        return $resultArray;
    }

    public function get_personal_info($username) {

        $query = "SELECT S.* "
                . "FROM scholarship.students S "
                . "INNER JOIN scholarship.users U ON U.STUDENT_ID = S.STUDENT_ID "
                . "WHERE U.USER_NAME = '$username'";
        $result = $this->query($query);
        $personalInfo = null;
        while (($row = $result->fetch_array()) != false) {
            $personalInfo = $row;
        }
        return $personalInfo;
    }
    
    public function avg_gpa(){
        $query = "SELECT AVG(GPA) FROM scholarship.students S";
        $result = $this->query($query);
        $row = $result->fetch_array();
        return $row[0];
    }


public function get_studentinfo_byID($student_id){
    $query = "SELECT S.* "
                . "FROM scholarship.students S "
                . "INNER JOIN scholarship.users U ON U.STUDENT_ID = S.STUDENT_ID "
                . "WHERE U.STUDENT_ID = '$student_id'";
        $result = $this->query($query);
        $personalInfo = null;
        while (($row = $result->fetch_array()) != false) {
            $personalInfo = $row;
        }
        return $personalInfo;
    }
public function reset_points(){
    $sql = "UPDATE scholarship.students "
            . "SET study_hours = 0, professor_advisor= 0, tutoring = 0, key_to_sucess = 0, study_hall = 0, "
            . "meeting_abt = 0, Total = 0 ";
     if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
}
public function changePassword($id,$password){
    $sql = "UPDATE scholarship.users "
            . "SET password = '$password' "
            . "WHERE student_id = '$id'";
     if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
}
public function update_points_student($points,$description,$student_id){
    if ($description == "Study Hours"){
         $sql = "UPDATE scholarship.students SET study_hours= '$points' WHERE student_id='$student_id'";
     
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
     if ($description == "Study Hall"){
         $sql = "UPDATE scholarship.students SET study_hall= $points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
     if ($description == "Professor/Advisor Visit"){
         $sql = "UPDATE scholarship.students SET professor_advisor= $points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
     if ($description == "Tutoring"){
         $sql = "UPDATE scholarship.students SET tutoring= $points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
      if ($description == "Keys To Success"){
         $sql = "UPDATE scholarship.students SET key_to_sucess= $points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
     if ($description == "Meeting With ABT"){
         $sql = "UPDATE scholarship.students SET meeting_abt= $points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }


   
            
}

public function add_points_to_student($points,$description,$student_id){
    if ($description == "Study Hours"){
         $sql = "UPDATE scholarship.students SET study_hours= study_hours+$points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
     if ($description == "Study Hall"){
         $sql = "UPDATE scholarship.students SET study_hall= study_hall+$points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
     if ($description == "Professor/Advisor Visit"){
         $sql = "UPDATE scholarship.students SET professor_advisor= professor_advisor+$points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
     if ($description == "Tutoring"){
         $sql = "UPDATE scholarship.students SET tutoring= tutoring+$points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
      if ($description == "Keys To Success"){
         $sql = "UPDATE scholarship.students SET key_to_sucess= key_to_sucess+$points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }
    
     if ($description == "Meeting With ABT"){
         $sql = "UPDATE scholarship.students SET meeting_abt= meeting_abt+$points WHERE student_id='$student_id'";
        if ($this->query($sql) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
    }


   
            
}
public function get_student_id_by_full_name($first_name,$last_name){
 
    $sql = "SELECT student_id "
            . "FROM scholarship.students "
            . "WHERE first_name = '$first_name' "
            . "AND last_name = '$last_name'";
    $result = $this->query($sql);
     $row = $result->fetch_array();
        return $row[0];
}
public function deleteStudent($studentID) {
    $sql = "DELETE FROM scholarship.students "
            . "WHERE STUDENT_ID = '$studentID'";

       if ($this->query($sql) === TRUE) {
           header("Refresh:0");
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
  
}

public function deleteUser($studentID){
     $sql2 = "DELETE FROM scholarship.users "
            . "WHERE STUDENT_ID = '$studentID'";
      if ($this->query($sql2) === TRUE) {
          
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
}
public function delete_points_fromStudyHours($id){
  $query = "DELETE FROM scholarship.StudyHours "
            . "WHERE id = $id";
   if ($this->query($query) === TRUE) {
           
        } else {
            echo "Error: " . $sql . "<br>" . $this->error;
        }
}
}
?>
