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
                <th>
                <tr>
                    <td width="5%">Serial</td>
                    <td width="5%">Image</td>
                    <td width="5%">Name</td>
                    <td width="10%">Designation</td>
                    <td width="5%">Status</td>
                    <td width="5%">Contact</td>

                    <td width="5%">Address</td>
                    <td width="5%">Email</td>
                    <td width="5%">Department</td> 
                    <td width="5%">Faculty</td>

                    <td width="5%">Action</td>

                </tr>
                </thead>
                <tbody>
                    <?php
                    $index = 1;
                    $statement_pr = $db->prepare("SELECT * FROM teacher ORDER BY id DESC");
                    $statement_pr->execute();
                    $result = $statement_pr->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <tr>
                            <td><?php echo $index; ?></td>
                            <td>  <img src="<?php echo $row['image_url']; ?>" alt="Teacher Image" style="width: 200px; height: 200px"></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['designation']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['phone']; ?></td>

                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['department']; ?></td>  
                            <td><?php echo $row['faculty']; ?></td>




                            <td><a type="button" class="btn btn-info" href="edit_teacher.php?id=<?php echo $row['id']; ?>&category=teacher">Edit</a></td>


                            <td><a type="button" class="btn btn-danger" href="delete_teacher.php?id=<?php echo $row['id']; ?>">Delete</a></td>


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