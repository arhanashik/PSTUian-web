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
 


if (isset($_POST['form'])) {

    try {

        if (empty($_POST['name'])) {
            throw new Exception(" name can not be  empty.");
        }

        if (empty($_POST['title'])) {
            throw new Exception("title  can not be empty.");
        }

        if (empty($_POST['session'])) {
            throw new Exception("session  not be empty.");
        }

        if (empty($_POST['faculty'])) {
            throw new Exception("faculty contact not be empty.");
        }

        if (empty($_POST['total_student'])) {
            throw new Exception(" Total Student not be empty.");
        }
     
        
        include_once './database.php';
//        $statement = $db->prepare("SHOW TABLE STATUS LIKE $t_name");
//        $statement->execute();
//        $result = $statement->fetchAll();
//        foreach ($result as $row)
//            $new_id = $row[10];


   
        $statement = $db->prepare("INSERT INTO batch (name, title, session, faculty, total_student) VALUES(?, ?, ?, ?, ?)");
        $statement->execute(array($_POST['name'], $_POST['title'], $_POST['session'],$_POST['faculty'],$_POST['total_student']));


        $success_message = "Batch Added Successfully.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>




        <div class="col-md-8">
            <h2 class="text-success text-center">Add Batch</h2>
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
                    <input type="text" name="name" class="form-control" placeholder="  Batch Name"/>
                </div>

                <div class="form-group">
                    <input type="text" name="title"  class="form-control" placeholder="title"/>
                </div>

                <div class="form-group">
                    <input type="text" name="session" class="form-control" placeholder=" session"/>
                </div>
                   <div class="form-group">
                    <input type="text" name="faculty" class="form-control" placeholder=" faculty"/>
                </div>

               
                  <div class="form-group">
                    <input type="text" name="total_student"  class="form-control" placeholder="Total Student"/>
                </div>
                  
                
             

                <div class="form-group">
                    <input type="submit" name="form" value="Save" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>

</div>
<?php include_once './footer.php'; ?>