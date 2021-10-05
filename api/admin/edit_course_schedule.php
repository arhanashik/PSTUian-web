<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("location:index.php");
}
include_once './header.php';


include_once './database.php';
if ($_SESSION['admin'] == "admin") {
    
} else {
    
}
?>
<?php
include_once './header.php';
include_once './database.php';
if (!isset($_REQUEST['id'])) {
    header("location: edit_course_schedule.php");
} else {
    $id = $_REQUEST['id'];
}
?>



<?php
if (isset($_POST['form_course'])) {
    try {

        if (empty($_POST['course_code'])) {
            throw new Exception("Course_schedule  can not be empty.");
        }



        $statement = $db->prepare("UPDATE course_schedule SET course_code=?, course_title=?, credit_hour=?, faculty=? ,status=?   WHERE id=?");
        $statement->execute(array($_POST['course_code'], $_POST['course_title'], $_POST['credit_hour'], $_POST['faculty'], $_POST['status'], $id));
        //header("location: product.php?brand=$table_name");
        $success_message = " Course Schedule updated successfully.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>
<?php
//include_once './database_file.php';
$statement = $db->prepare("SELECT * FROM course_schedule WHERE id=?");
$statement->execute(array($id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $course_code = $row['course_code'];
    $course_title = $row['course_title'];
    $credit_hour = $row['credit_hour'];
    $faculty = $row['faculty'];
    $status = $row['status'];
}
?>

<div class="col-md-8">
    <h2 class="text-success text-center">Edit Course Scedule</h2>
<form action="" method="post" enctype="multipart/form-data">
    <table class="edit_form">
<?php
if (isset($error_message)) {
    echo $error_message;
}
if (isset($success_message)) {
    echo $success_message;
}
?>
        
          <div class="form-group">
             <h3>Course Code</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="course_code" class="form-control" value="<?php echo $course_code; ?>">
        </div>
        
        
             <div class="form-group">
             <h3>Course Title</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="course_title" class="form-control" value="<?php echo $course_title; ?>">
        </div>
        
            <div class="form-group">
             <h3>Credit Hour</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="credit_hour" class="form-control" value="<?php echo $credit_hour; ?>">
        </div>

        
         <div class="form-group">
             <h3>Faculty</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="faculty" class="form-control" value="<?php echo $faculty; ?>">
        </div>

        
        <div class="form-group">
             <h3>Status</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="status" class="form-control" value="<?php echo $status; ?>">
        </div>
        
             <div class="form-group">
            <input type="submit" name="form_course" value="Save" class="btn btn-primary"/>
        </div>
    </table>
</form>
</div>
</body>
</html>

