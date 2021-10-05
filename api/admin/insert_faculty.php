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
if (isset($_POST['form_faculty'])) {

    try {

        if (empty($_POST['s_title'])) {
            throw new Exception("Short Title can not be  empty.");
        }

      if (empty($_POST['title'])) {
            throw new Exception(" title not be empty.");
        }
        

   
        
        include_once './database.php';

        
      
        $statement = $db->prepare("INSERT INTO faculty ( short_title, title) VALUES( ?, ?)");
         $statement->execute(array($_POST['s_title'], $_POST['title']));


        $success_message = "Faculty Added Successfully.";
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
            <h2 class="text-success text-center">Add Faculty</h2>
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
                    
                     <div class="form-group">
                    <input type="text" name="s_title" class="form-control" placeholder="Short title"/>
                </div>

                    <input type="text" name="title" class="form-control" placeholder="Title"/>
                </div>

              
                
             <div class="form-group">
                    <input type="submit" name="form_faculty" value="Save" class="btn btn-primary"/>
                </div>
        
            </form>
        </div>
    </div>

</div>
<?php include_once './footer.php'; ?>