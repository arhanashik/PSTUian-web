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
if (isset($_POST['form_teacher'])) {

    try {

        if (empty($_POST['t_name'])) {
            throw new Exception("Teacher name can not be  empty.");
        }

        if (empty($_POST['t_rank'])) {
            throw new Exception("Teacher rank can not be empty.");
        }

        

       
         if (empty($_POST['t_department'])) {
            throw new Exception("Teacher department not be empty.");
        }
          if (empty($_POST['t_faculty'])) {
            throw new Exception("Teacher faculty not be empty.");
        } 
       
        
        include_once './database.php';
//        $statement = $db->prepare("SHOW TABLE STATUS LIKE $t_name");
//        $statement->execute();
//        $result = $statement->fetchAll();
//        foreach ($result as $row)
//            $new_id = $row[10];
     

       
        $file_basename = basename($_FILES["t_image_url"]["name"]);
        $file_ext = pathinfo($file_basename, PATHINFO_EXTENSION);
        $t_name = strtolower(str_replace(" ","",$_POST['t_name']));
        $up_filename = $t_name . "_" . get_millis() . "." . $file_ext;
        $target_dir = dirname(__FILE__) . "/uploads/" . $up_filename;
       $img_url = "http://agramonia.com/pstuian/admin/uploads/".$up_filename;
          
      
        if (($file_ext != "png") && ($file_ext != "jpg") && ($file_ext != "jpeg") && ($file_ext != "gif"))
            throw new Exception("Only jpg, jpeg, png and gif format images are allowed to upload.");

        move_uploaded_file($_FILES["t_image_url"]["tmp_name"], $target_dir . $file_basename);


        $statement = $db->prepare("INSERT INTO teacher (name, designation, status, phone,linked_in, address, email, department,faculty,fb_link,image_url) VALUES(?,?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
        $statement->execute(array($_POST['t_name'], $_POST['t_rank'], $_POST['t_status'],$_POST['t_contact'],$_POST['t_linked_in'],$_POST['t_address'],$_POST['t_email'],$_POST['t_department'],$_POST['t_faculty'],$_POST['t_fb_link'], $img_url));


        $success_message = "Teacher Added Successfully.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
function get_millis(){
  list($usec, $sec) = explode(' ', microtime());
  return (int) ((int) $sec * 1000 + ((float) $usec * 1000));
}

?>


<?php
include_once './header.php';
include_once './database.php';
?>



        <div class="col-md-8">
            <h2 class="text-success text-center">Add Teacher</h2>
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
                    <input type="text" name="t_name" class="form-control" placeholder="Teacher Name"/>
                </div>

                <div class="form-group">
                    <input type="text" name="t_rank"  class="form-control" placeholder="Teacher rank"/>
                </div>

                <div class="form-group">
                    <input type="text" name="t_status" class="form-control" placeholder="Teacher Status"/>
                </div>

                <div class="form-group">
                    <input type="file" name="t_image_url" class="form-control"/>
                </div>
                
                 <div class="form-group">
                    <input type="text" name="t_linked_in" class="form-control" placeholder="Teacher Linked In"/>
                </div>

                <div class="form-group">
                    <input type="number" name="t_contact" class="form-control" placeholder="Teacher Contact"/>
                </div>

                <div class="form-group">
                    <input type="text" name="t_department"  class="form-control" placeholder="Teacher Department"/>
                </div>
                  <div class="form-group">
                    <input type="text" name="t_address"  class="form-control" placeholder="Teacher Address"/>
                </div>
                   <div class="form-group">
                    <input type="text" name="t_email"  class="form-control" placeholder="Teacher Email"/>
                </div>
                
             
                
                  <div class="form-group">
                    <input type="text" name="t_faculty"  class="form-control" placeholder="Teacher Faculty"/>
                </div>
                <div class="form-group">
                    <input type="text" name="t_fb_link"  class="form-control" placeholder="FB Link"/>
                </div>

                <div class="form-group">
                    <input type="submit" name="form_teacher" value="Save" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>

</div>



<?php include_once './footer.php'; ?>