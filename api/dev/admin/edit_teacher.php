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
    header("location: edit_teacher.php");
} else {
    $id = $_REQUEST['id'];
}
?>



<?php
if (isset($_POST['form_teacher'])) {
    try {

        if (empty($_POST['name'])) {
            throw new Exception("Teacher Name can not be empty.");
        }
        
         if(empty($_FILES["image_url"]["name"])) {
        
        $statement = $db->prepare("UPDATE teacher SET name=? , designation=? , status=? , phone=? ,linked_in=? , address=?, email=? , department=? , faculty=? ,fb_link=?  WHERE id=?");
        $statement->execute(array($_POST['name'], $_POST['designation'], $_POST['status'], $_POST['phone'], $_POST['linked_in'], $_POST['address'], $_POST['email'], $_POST['department'], $_POST['faculty'], $_POST['fb_link'], $id));
        $success_message = "Teacher Information updated successfully.";
           
         }
    else{if(isset($_FILES["image_url"]["name"])){
        $file_basename = basename($_FILES["image_url"]["name"]);
        $file_ext = pathinfo($file_basename, PATHINFO_EXTENSION);
        $t_name = strtolower(str_replace(" ","",$_POST['name']));
        $up_filename = $t_name . "_" . get_millis() . "." . $file_ext;
        $target_dir = dirname(__FILE__) . "/uploads/" . $up_filename;
        $img_url = "http://agramonia.com/pstuian/uploads/".$up_filename;
          
      
        if (($file_ext != "png") && ($file_ext != "jpg") && ($file_ext != "jpeg") && ($file_ext != "gif"))
            throw new Exception("Only jpg, jpeg, png and gif format images are allowed to upload.");

        move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_dir . $file_basename);

    }

        $statement = $db->prepare("UPDATE teacher SET name=? , designation=? , status=? , phone=? ,linked_in=? , address=?, email=? , department=? , faculty=? ,fb_link=? ,image_url=? WHERE id=?");
        $statement->execute(array($_POST['name'], $_POST['designation'], $_POST['status'], $_POST['phone'], $_POST['linked_in'], $_POST['address'], $_POST['email'], $_POST['department'], $_POST['faculty'], $_POST['fb_link'], $img_url, $id));
        //header("location: product.php?brand=$table_name");
     
    } }catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}



function get_millis(){
  list($usec, $sec) = explode(' ', microtime());
  return (int) ((int) $sec * 1000 + ((float) $usec * 1000));
}
?>
<?php
//include_once './database_file.php';
$statement = $db->prepare("SELECT * FROM teacher WHERE id=?");
$statement->execute(array($id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $name = $row['name'];
    $designation = $row['designation'];
    $status = $row['status'];
    $phone = $row['phone'];
    $linked_in = $row['linked_in'];


    $address = $row['address'];
    $email = $row['email'];
    $department = $row['department'];

    $faculty = $row['faculty'];
    $fb_link = $row['fb_link'];
    $image_url = $row['image_url'];
}
?>

<div class="col-md-8">
    <h2 class="text-success text-center">Edit Teacher</h2>
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
             <h3>Teacher Name</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
        </div>
        
 
           <h3>Teacher Designation</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="designation" class="form-control" value="<?php echo $designation; ?>">
        </div>
        

         <h3>Teacher Status</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="status" class="form-control" value="<?php echo $status; ?>">
        </div>
        
         <h3>Teacher Phone</h3>
        </div>
        
        <div class="form-group">
            <input type="number" name="phone" class="form-control" value="<?php echo $phone; ?>">
        </div>


            <h3>Teacher Linked In</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="linked_in" class="form-control" value="<?php echo $linked_in; ?>">
        </div>
       
        <div class="form-group">
            <h3>Teacher Address</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="address" class="form-control" value="<?php echo $address; ?>">
        </div>


           <div class="form-group">
            <h3>Teacher Email</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
        </div>

        
           <div class="form-group">
            <h3>Teacher Department</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="department" class="form-control" value="<?php echo $department; ?>">
        </div>
        
        
        
          <div class="form-group">
            <h3>Teacher Faculty</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="faculty" class="form-control" value="<?php echo $faculty; ?>">
        </div>
     
        
        

        <div class="form-group">
            <h3>Teacher  Fb linked_in</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="fb_link" class="form-control" value="<?php echo $fb_link; ?>">
        </div>
        
         <div class="form-group">
            <h3>Teacher  Image</h3>
        </div>
        
        <div class="form-group">
            <input type="file" name="image_url" class="form-control" value="<?php echo $image_url; ?>">
        </div>
        
        
        
           
          <div class="form-group">
            <input type="submit" name="form_teacher" value="Save" class="btn btn-primary"/>
        </div>
      

    </table>
</form>
</div>
</body>
</html>

