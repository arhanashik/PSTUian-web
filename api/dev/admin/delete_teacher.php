
<?php
include_once './database.php';

if (!isset($_REQUEST['id'])) {
    header("location: delete_teacher.php");
} else {
    $id = $_REQUEST['id'];
    $statement = $db->prepare("DELETE FROM teacher WHERE id = ?");
    $statement->execute(array($id));
    header("location: details_teacher.php");
}
?>