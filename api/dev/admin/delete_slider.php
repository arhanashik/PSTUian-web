
<?php
include_once './database.php';

if (!isset($_REQUEST['id'])) {
    header("location: delete_slider.php");
} else {
    $id = $_REQUEST['id'];
    $statement = $db->prepare("DELETE FROM slider WHERE id = ?");
    $statement->execute(array($id));
    header("location: details_slider.php");
}
?>