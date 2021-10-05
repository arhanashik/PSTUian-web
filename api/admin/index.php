<head>


            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
</head>
<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("location: header.php");
}




if (isset($_POST['submit'])) {
    $admin_email = $_POST['email'];
    $admin_password = $_POST['password'];


    try {
        if (empty($admin_email)) {
            throw new Exception("Please Give email address");
        }
        if (empty($admin_password)) {
            throw new Exception("Please Give admin password");
        }

        //login
        //$sql = "SELECT * FROM tbl_admin WHERE admin_email = $admin_email AND admin_password= $admin_password";


        include_once './database.php';
        $statement = $db->prepare("SELECT * FROM admin WHERE email=? AND password=? LIMIT 1");
        $statement->execute(array($admin_email, $admin_password));
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        $num = $statement->rowCount();

        if ($num == 1) {
            $_SESSION['admin'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['id'] = $row['id'];

            header("location: header.php");
        } else {
            throw new Exception("You've enterd invalid password!!!");
        }
    } catch (Exception $ex) {
        $error_message = $ex->getMessage();
    }
}
?>



<div class="login_area">
<?php
if (isset($error_message)) {

    echo "<div class='alert alert-danger'>
            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            $error_message
</div>";
}
?>
    <div class="rakib">

        <div style="text-align:center">
            <h3>Admin Panel</h3></div>
        <form class="form-horizontal" action="" role="form" method="post">
            <div class="form-group">
                <label class="control-label col-sm-2" for="email">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Password:</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" id="pwd" placeholder="Enter password">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="submit" name="submit" class="btn btn-primary" value="Log In"/>
                </div>
            </div>
        </form>
    </div>


 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</div>

<?php include './footer.php'; ?>