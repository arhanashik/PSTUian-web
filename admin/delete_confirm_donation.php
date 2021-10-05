
<?php
include_once './database.php';

if (!isset($_REQUEST['id'])) {
    header("location: delete_confirm_donation.php");
} else {
    $id = $_REQUEST['id'];
    $statement = $db->prepare("DELETE FROM donation WHERE id = ?");
    $statement->execute(array($id));
    header("location: confirm_donation.php");
}
?>