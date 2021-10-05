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
if (isset($_POST['form_slider'])) {

    try {

        if (empty($_POST['title'])) {
            throw new Exception("Title name can not be  empty.");
        }

     
         if (empty($_FILES['e_image_url'])) {
            throw new Exception("Slider not be empty.");
        }
        
        include_once './database.php';
//        $statement = $db->prepare("SHOW TABLE STATUS LIKE $t_name");
//        $statement->execute();
//        $result = $statement->fetchAll();
//        foreach ($result as $row)
//            $new_id = $row[10];

  $file_basename = basename($_FILES["e_image_url"]["name"]);
        $file_ext = pathinfo($file_basename, PATHINFO_EXTENSION);
        $t_name = strtolower(str_replace(" ","",$_POST['title']));
        $up_filename = $t_name . "_" . get_millis() . "." . $file_ext;
        $target_dir = dirname(__FILE__) . "/uploads/" . $up_filename;
       $img_url = "http://agramonia.com/pstuian/admin/uploads/".$up_filename;
          
      
        if (($file_ext != "png") && ($file_ext != "jpg") && ($file_ext != "jpeg") && ($file_ext != "gif"))
            throw new Exception("Only jpg, jpeg, png and gif format images are allowed to upload.");

         move_uploaded_file($_FILES["e_image_url"]["tmp_name"], $target_dir . $file_basename);



        $statement = $db->prepare("INSERT INTO slider (title,image_url) VALUES(?, ?)");
        $statement->execute(array($_POST['title'], $img_url));


        $success_message = "Image url Successfully.";
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
            <h2 class="text-success text-center">Add Slider</h2>
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
                    <input type="text" name="title" class="form-control" placeholder="Title Name"/>
                </div>

               

                <div class="form-group">
                    <input type="file" name="e_image_url" class="form-control"/>
                </div>

              
                <div class="form-group">
                    <input type="submit" name="form_slider" value="Save" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>

</div>
<?php include_once './footer.php'; ?>