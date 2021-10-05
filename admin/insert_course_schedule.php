
<?php

session_start();
if(!isset($_SESSION['admin'])){
    header("location:index.php");
}
include_once './header.php';


include_once './database.php';
if($_SESSION['admin']=="admin"){
  
}
else{
   
}
if (isset($_POST['form_course'])) {

    try {

        if (empty($_POST['course_code'])) {
            throw new Exception("Course Code can not be  empty.");
        }

        if (empty($_POST['course_title'])) {
            throw new Exception("Course_title can not be empty.");
        }

        if (empty($_POST['credit_hour'])) {
            throw new Exception("Credit Hour not be empty.");
        }

        if (empty($_POST['faculty'])) {
            throw new Exception("Faculty contact not be empty.");
        }

        if (empty($_POST['status'])) {
            throw new Exception("Status not be empty.");
        }
     
    
        
        include_once './database.php';
//        $statement = $db->prepare("SHOW TABLE STATUS LIKE $t_name");
//        $statement->execute();
//        $result = $statement->fetchAll();
//        foreach ($result as $row)
//            $new_id = $row[10];


   

        $statement = $db->prepare("INSERT INTO course_schedule (course_code, course_title, credit_hour, faculty, status) VALUES(?, ?, ?, ?, ?)");
        $statement->execute(array($_POST['course_code'], $_POST['course_title'], $_POST['credit_hour'],$_POST['faculty'],$_POST['status']));


        $success_message = "Course Material Successfully.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<?php
include_once './header.php';
include_once './database.php';
?>



        <div class="col-md-8">
            <h2 class="text-success text-center">Course Schedule</h2>
            <form method="post" class="form-group" enctype="multipart/form-data">

<?php
if (isset($error_message)) {
    echo "<div class='text-danger'>" . $error_message . "</div>";
}
if (isset($success_message)) {
    echo "<div class='text-success'>" . $success_message . "</div>";
}
?>

                <div class="form-group">
                    <input type="text" name="course_code" class="form-control" placeholder="Course Code"/>
                </div>

                <div class="form-group">
                    <input type="text" name="course_title"  class="form-control" placeholder="Course Title"/>
                </div>

                <div class="form-group">
                    <input type="text" name="credit_hour" class="form-control" placeholder="Credit Hour"/>
                </div>

              
             
                
                  <div class="form-group">
                    <input type="text" name="faculty"  class="form-control" placeholder="Faculty"/>
                </div>
                  <div class="form-group">
                    <input type="text" name="status"  class="form-control" placeholder="status"/>
                </div>


                <div class="form-group">
                    <input type="submit" name="form_course" value="Save" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>

</div>
<?php include_once './footer.php'; ?>