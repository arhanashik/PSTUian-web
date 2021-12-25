<?php
$uri = $_SERVER['REQUEST_URI'];
$path = basename($uri);

session_start();
if(!isset($_SESSION['admin']) && !isset($_SESSION['auth_token'])) {
    header('Location: login.php?from=' . $path);
   return;
}

$admin = $_SESSION['admin'];
$role = $admin['role'];
$auth_token = $_SESSION['auth_token'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>PSTUian</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <link href="css/styles.css" rel="stylesheet" />
        <link href="css/custom.css" rel="stylesheet" />
        
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            // Base api url
            var baseUrl = 'api/';

            //adding headers to ajax
            $.ajaxSetup({
                headers: { 
                    'x-auth-token': '<?php echo $auth_token; ?>',
                    'x-admin-id' : '<?php echo $admin['id']; ?>',
                }
            });

            $(function() { // do things when the document is ready
                
            });
        </script>
    </head>
    <body class="sb-nav-fixed">
        <?php include('./top_nav.php'); ?>
        <div id="layoutSidenav">
            <?php include('./side_nav.php'); ?>
            <div id="layoutSidenav_content">