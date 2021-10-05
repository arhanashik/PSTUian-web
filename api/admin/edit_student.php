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
    header("location: edit_student.php");
} else {
    $id = $_REQUEST['id'];
}
?>



<?php
if (isset($_POST['form_student'])) {
    
    try {

        if (empty($_POST['name'])) {
            throw new Exception("Student Name can not be empty.");
        }

        if (empty($_POST['id'])) {
            throw new Exception(" Student id can not be empty.");
        }
        if(empty($_FILES["image_url"]["name"])) {
        $statement = $db->prepare("UPDATE student SET name=? , id=? , reg=? , phone=? ,linked_in=?, blood=? , address=?, email=? , batch=?, session=? , faculty=? ,fb_link=? WHERE id=?");
        $statement->execute(array($_POST['name'], $_POST['id'], $_POST['reg'], $_POST['phone'], $_POST['linked_in'], $_POST['blood'], $_POST['address'], $_POST['email'], $_POST['batch'], $_POST['session'], $_POST['faculty'], $_POST['fb_link'], $id));
        //header("location: product.php?brand=$table_name");
        $success_message = "Student Information updated successfully.";
    
        }

     else{if(isset($_FILES["image_url"]["name"])){
        $file_basename = basename($_FILES["image_url"]["name"]);
        $file_ext = pathinfo($file_basename, PATHINFO_EXTENSION);
        $up_filename = $_POST['id'] . "." . $file_ext;
        $target_dir = dirname(__FILE__) . "/uploads/" . $up_filename;
        $img_url = "http://agramonia.com/pstuian/uploads/" .$up_filename ;

        if (($file_ext != "png") && ($file_ext != "jpg") && ($file_ext != "jpeg") && ($file_ext != "gif"))
            throw new Exception("Only jpg, jpeg, png and gif format images are allowed to upload.");

        move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_dir);

     } 
   
        $statement = $db->prepare("UPDATE student SET name=? , id=? , reg=? , phone=? ,linked_in=?, blood=? , address=?, email=? , batch=?, session=? , faculty=? ,fb_link=? ,image_url=? WHERE id=?");
        $statement->execute(array($_POST['name'], $_POST['id'], $_POST['reg'], $_POST['phone'], $_POST['linked_in'], $_POST['blood'], $_POST['address'], $_POST['email'], $_POST['batch'], $_POST['session'], $_POST['faculty'], $_POST['fb_link'], $img_url, $id));
        //header("location: product.php?brand=$table_name");
        $success_message = "Student Information updated successfully.";
     }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
    
     
      

    } 
    
    

?>
<?php
//include_once './database_file.php';
$statement = $db->prepare("SELECT * FROM student WHERE id=?");
$statement->execute(array($id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $name = $row['name'];
    $id = $row['id'];
    $reg = $row['reg'];
    $phone = $row['phone'];
    $linked_in = $row['linked_in'];
    $blood = $row['blood'];

    $address = $row['address'];
    $email = $row['email'];
    $batch = $row['batch'];

    $session = $row['session'];
    $faculty = $row['faculty'];
    $fb_link = $row['fb_link'];
     $image_url = $row['image_url'];
}
?>

<div class="col-md-8">
    <h2 class="text-success text-center">Edit Student</h2>
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
             <h3>Student Name</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
        </div>
        
        
          <div class="form-group">
             <h3>Student Id</h3>
        </div>
        

        
          <div class="form-group">
            <input type="text" name="id" class="form-control" value="<?php echo $id; ?>">
        </div>
        
        
         <div class="form-group">
             <h3>Student Registration</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="reg" class="form-control" value="<?php echo $reg; ?>">
        </div>
        
         <div class="form-group">
             <h3>Student Phone</h3>
        </div>
       
          <div class="form-group">
            <input type="number" name="phone" class="form-control" value="<?php echo $phone; ?>">
        </div>
        
          <div class="form-group">
             <h3>Student Linked In</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="linked_in" class="form-control" value="<?php echo $linked_in; ?>">
        </div>
        
          <div class="form-group">
             <h3>Student Blood</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="blood" class="form-control" value="<?php echo $blood; ?>">
        </div>

         <div class="form-group">
             <h3>Student Address</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
        </div>
  
            <div class="form-group">
             <h3>Student Email</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
        </div>
  
             <div class="form-group">
             <h3>Student Batch</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="batch" class="form-control" value="<?php echo $batch; ?>">
        </div>
        
           <div class="form-group">
             <h3>Student Session</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="session" class="form-control" value="<?php echo $session; ?>">
        </div>
        
             <div class="form-group">
             <h3>Student Faculty</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="faculty" class="form-control" value="<?php echo $faculty; ?>">
        </div>
        
         <div class="form-group">
             <h3>Student Fb Link</h3>
        </div>
       
          <div class="form-group">
            <input type="text" name="fb_link" class="form-control" value="<?php echo $fb_link; ?>">
        </div>
          <div class="form-group">
             <h3>Student Image</h3>
        </div>
       
          <div class="form-group">
              <input type="file" name="image_url" class="form-control" value="<?php echo $image_url; ?>">
        </div>

         <div class="form-group">
            <input type="submit" name="form_student" value="Save" class="btn btn-primary"/>
        </div>

    </table>
</form>
    </div>
</body>
</html>

