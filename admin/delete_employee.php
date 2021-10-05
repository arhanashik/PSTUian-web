
<?php
include_once './database.php';

if (!isset($_REQUEST['id'])) {
    header("location: delete_employee.php");
} else {
    $id = $_REQUEST['id'];
    $statement = $db->prepare("DELETE FROM employee WHERE id = ?");
    $statement->execute(array($id));
    header("location: details_employee.php");
}
?>