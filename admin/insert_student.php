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
if (isset($_POST['form_student'])) {

    try {

        if (empty($_POST['s_name'])) {
            throw new Exception("Student name can not be  empty.");
        }

        if (empty($_POST['s_id'])) {
            throw new Exception("Student id can not be empty.");
        }

        if (empty($_POST['s_reg'])) {
            throw new Exception("Student reg not be empty.");
        }

     

        include_once './database.php';
//        $statement = $db->prepare("SHOW TABLE STATUS LIKE $t_name");
//        $statement->execute();
//        $result = $statement->fetchAll();
//        foreach ($result as $row)
//            $new_id = $row[10];


        $file_basename = basename($_FILES["s_image_url"]["name"]);
        $file_ext = pathinfo($file_basename, PATHINFO_EXTENSION);
        $up_filename = $_POST['s_id'] . "." . $file_ext;
        $target_dir = dirname(__FILE__) . "/uploads/" . $up_filename;
        $img_url = "http://agramonia.com/pstuian/admin/uploads/" .$up_filename ;

        if (($file_ext != "png") && ($file_ext != "jpg") && ($file_ext != "jpeg") && ($file_ext != "gif"))
            throw new Exception("Only jpg, jpeg, png and gif format images are allowed to upload.");

        move_uploaded_file($_FILES["s_image_url"]["tmp_name"], $target_dir);

        $statement = $db->prepare("INSERT INTO student (name,id, reg,phone,linked_in, blood,address,email,batch,session,faculty,fb_link,image_url) VALUES(?, ?, ?, ?, ?, ?,?, ?, ?, ?,?, ?,?)");
        $statement->execute(array($_POST['s_name'], $_POST['s_id'], $_POST['s_reg'], $_POST['s_contact'], $_POST['s_linked_in'], $_POST['s_blood'], $_POST['s_address'], $_POST['s_email'], $_POST['s_batch'], $_POST['s_session'], $_POST['s_faculty'], $_POST['s_fb_link'], $img_url));


        $success_message = "Student Added Successfully.";
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
    <h2 class="text-success text-center">Add Student</h2>
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
            <input type="text" name="s_name" class="form-control" placeholder="Student Name"/>
        </div>

        <div class="form-group">
            <textarea type="number" name="s_id"  class="form-control" placeholder="Student Id"></textarea>
        </div>

        <div class="form-group">
            <input type="text" name="s_reg" class="form-control" placeholder="Student Registration"/>
        </div>

        <div class="form-group">
            <input type="text" name="s_linked_in" class="form-control" placeholder="Student Linked In"/>
        </div>

        <div class="form-group">
            <input type="file" name="s_image_url" class="form-control"/>
        </div>

        <div class="form-group">
            <input type="number" name="s_contact" class="form-control" placeholder="Student Contact"/>
        </div>

        <div class="form-group">
            <input type="text" name="s_blood"  class="form-control" placeholder="Student Blood"/>
        </div>
        <div class="form-group">
            <input type="text" name="s_address"  class="form-control" placeholder="Student Address"/>
        </div>
        <div class="form-group">
            <input type="text" name="s_email"  class="form-control" placeholder="Student Email"/>
        </div>

        <div class="form-group">
            <input type="text" name="s_batch"  class="form-control" placeholder="Student Batch"/>
        </div>
        <div class="form-group">
            <input type="text" name="s_session"  class="form-control" placeholder="Student Session"/>
        </div>

        <div class="form-group">
            <input type="text" name="s_faculty"  class="form-control" placeholder="Student Faculty"/>
        </div>
        <div class="form-group">
            <input type="text" name="s_fb_link"  class="form-control" placeholder="Facebook Link"/>
        </div>

        <div class="form-group">
            <input type="submit" name="form_student" value="Save" class="btn btn-primary"/>
        </div>
    </form>
</div>
</div>

</div>
<?php include_once './footer.php'; ?>