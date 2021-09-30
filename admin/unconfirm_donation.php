 <?php 

session_start();
if(!isset($_SESSION['admin'])){
    header("location:index.php");
}
include_once './header.php';


include_once './database.php';
if($_SESSION['admin']=="admin"){
  
}
else{
   
}?>

<?php

include_once './header.php';
include_once './database.php';
if(isset($_GET['id']))
{
     $statement = $db->prepare("UPDATE donation SET confirm=1 WHERE id=?");
     $statement->execute(array($_GET['id']));

}



?>

<html>
    <title>Unconfirm Donation List list</title>
    <head>
        <link rel="stylesheet" href="bootstrap.min.css">
        
    </head>
    
    <body>
        <div  class="col-md-12 offset-md-2">
            <table class="table">
                  <h2 class="text-success text-center">Unconfirm Donation List</h2>
     <tr>
                <th width="5%">Serial</th>
                 <th width="5%">Name</th>
               
                 <th width="5%">Info</th>
                <th width="5%">Email</th>
                <th width="5%">Reference</th>
              
                <th width="10%">Action</th>
            </tr>
            
            <?php
            include_once 'database.php';
            $i = 0;
            $statement = $db->prepare("SELECT * FROM donation WHERE confirm = 0");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $i++;
              
                
            ?>
            
            <tr class="food">
              <td><?php echo $i; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['info']; ?></td>
                 <td><?php echo $row['email']; ?></td>
                  <td><?php echo $row['reference']; ?></td>
              
               
               
                  <td><a type="button" class="btn btn-success" href="unconfirm_donation.php?id=<?php echo $row['id']; ?>">Confirm</a></td>

            </tr>
            <?php
                
            }
            ?>
        </table>
        </div>
    </body>
    
</html>



<?php include_once './footer.php'; ?>