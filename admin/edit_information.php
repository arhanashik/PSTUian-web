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
    header("location: edit_information.php");
} else {
    $id = $_REQUEST['id'];
}
?>



<?php
if (isset($_POST['donation_option'])) {
    try {

        if (empty($_POST['donation'])) {
            throw new Exception("Information Name can not be empty.");
        }



        $statement = $db->prepare("UPDATE info SET donation_option=?   WHERE id=?");
        $statement->execute(array($_POST['donation_option'], $id));
        //header("location: product.php?brand=$table_name");
        $success_message = " Information updated successfully.";
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>
<?php
//include_once './database_file.php';
$statement = $db->prepare("SELECT * FROM info WHERE id=?");
$statement->execute(array($id));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $donation_option = $row['donation_option'];
}
?>



<div class="col-md-8">
    <h2 class="text-success text-center">Edit Information</h2>
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
             <h3>Send Donation</h3>
        </div>
        
        <div class="form-group">
            <input type="text" name="donation_option" class="form-control" value="<?php echo $donation_option; ?>">
        </div>

          <div class="form-group">
            <input type="submit" name="donation" value="Save" class="btn btn-primary"/>
        </div>

        
        
        
    </table>
</form>
</body>
</html>

