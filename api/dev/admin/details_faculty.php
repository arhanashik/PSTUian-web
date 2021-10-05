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
?>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-8">
            <table class="table">
                <thead>
                <th>#</th>

                <th width="5%">Short Title</th>
                <th width="5%">Title</th>

                <th width="5%">Action</th>


                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    $statement_pr = $db->prepare("SELECT * FROM faculty ORDER BY id DESC");
                    $statement_pr->execute();
                    $result = $statement_pr->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <tr>
                            <td><?php echo $index; ?></td>


                            <td><?php echo $row['short_title']; ?></td>
                            <td><?php echo $row['title']; ?></td>


                            <td><a type="button" class="btn btn-info" href="edit_faculty.php?id=<?php echo $row['id']; ?>&category=faculty">Edit</a></td>



                        </tr>
                        <?php
                        $index++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>