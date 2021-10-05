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
    header("location: edit_faculty.php");
} else {
    $id = $_REQUEST['id'];
}
?>



<?php
if (isset($_POST['form_faculty'])) {
    try {

        if (empty($_POST['title'])) {
            throw new Exception("Title Name can not be empty.");
        }



        $statement = $db->prepare("UPDATE faculty SET short_title=?, title=?   WHERE id=?");
        $statement->execute(array($_POST['short_title'], $_POST['title'], $id));
        //header("location: product.php?brand=$table_name");
        $success_message = " Faculty updated successfully.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>
<?php
//include_once './database_file.php';
$statement = $db->prepare("SELECT * FROM faculty WHERE id=?");
$statement->execute(array($id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $short_title = $row['short_title'];
    $title = $row['title'];
}
?>

<div class="col-md-8">
    <h2 class="text-success text-center">Edit Image Slider</h2>
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
             <h3>Short Title</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="short_title" class="form-control" value="<?php echo $short_title; ?>">
        </div>
    
              <div class="form-group">
             <h3> Title</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>">
        </div>
  
     <div class="form-group">
            <input type="submit" name="form_faculty" value="Save" class="btn btn-primary"/>
        </div>

    </table>
</form>
</div>
</body>
</html>

