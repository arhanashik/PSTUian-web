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
    header("location: edit_batch.php");
} else {
    $id = $_REQUEST['id'];
}
?>



<?php
if (isset($_POST['form_batch'])) {
    try {

        if (empty($_POST['name'])) {
            throw new Exception("Information Name can not be empty.");
        }



        $statement = $db->prepare("UPDATE batch SET name=?, title=?, session=?, faculty=? ,total_student=?   WHERE id=?");
        $statement->execute(array($_POST['name'], $_POST['title'], $_POST['session'], $_POST['faculty'], $_POST['total_student'], $id));
        //header("location: product.php?brand=$table_name");
        $success_message = " Information updated successfully.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>
<?php
//include_once './database_file.php';
$statement = $db->prepare("SELECT * FROM batch WHERE id=?");
$statement->execute(array($id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $name = $row['name'];
    $title = $row['title'];
    $session = $row['session'];
    $faculty = $row['faculty'];
    $total_student = $row['total_student'];
}
?>
<div class="col-md-8">
    <h2 class="text-success text-center">Edit Batch</h2>
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
             <h3>Batch Name</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
        </div>
        
        <div class="form-group">
             <h3>Batch Title</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
        </div>
      

        <div class="form-group">
             <h3> Session</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="session" class="form-control" value="<?php echo $session; ?>">
        </div>
        
        
           <div class="form-group">
             <h3> Faculty</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="faculty" class="form-control" value="<?php echo $faculty; ?>">
        </div>
       
             <div class="form-group">
             <h3> Total Student</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="total_student" class="form-control" value="<?php echo $total_student; ?>">
        </div>
   
          <div class="form-group">
            <input type="submit" name="form_batch" value="Save" class="btn btn-primary"/>
        </div>
      
    </table>
</form>
</div>
</body>
</html>

