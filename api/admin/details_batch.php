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

                <th width="5%">name</th>
                <th width="5%">title</th>
                <th width="5%">session</th>
                <th width="5%">faculty</th>
                <th width="5%">total_student</th>


                <th width="5%">Action</th>


                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    $statement_pr = $db->prepare("SELECT * FROM batch ORDER BY id DESC");
                    $statement_pr->execute();
                    $result = $statement_pr->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <tr>
                            <td><?php echo $index; ?></td>


                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['session']; ?></td>
                            <td><?php echo $row['faculty']; ?></td>
                            <td><?php echo $row['total_student']; ?></td>


                            <td><a type="button" class="btn btn-info" href="edit_batch.php?id=<?php echo $row['id']; ?>&category=batch">Edit</a></td>



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