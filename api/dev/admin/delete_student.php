
<?php
include_once './database.php';

if (!isset($_REQUEST['id'])) {
    header("location: delete_student.php");
} else {
    $id = $_REQUEST['id'];
    $statement = $db->prepare("DELETE FROM student WHERE id = ?");
    $statement->execute(array($id));
    header("location: details_student.php");
}
?>