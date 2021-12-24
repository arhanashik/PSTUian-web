<?php
session_start();
if (isset($_SESSION['admin']) && isset($_SESSION['auth_token'])) {
    header("location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>PSTUian</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/login.css">
</head>
<body>
<div class="container login-container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6 login-form">
            <h3>Admin Panel</h3>
            <div class="login-fields">
                <p id="error"></p>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Your Email *" id="email" value="" />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Your Password *" id="password" value="" />
                </div>
                <div class="form-group">
                    <input type="submit" class="btnSubmit" id="btnSubmit" value="Login" />
                </div>
                <div class="form-group">
                    <a href="#" class="ForgetPwd">Forget Password?</a>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
    var baseUrl = 'api/';
    $(document).ready(function() {
        $("#btnSubmit").click(function() {
            var email = $("#email").val().trim();
            var password = $("#password").val().trim();

            if(email === "" || password === "") {
                return;
            }
            $.ajax({
                url: `${baseUrl}auth.php?call=signIn`,
                type:'post',
                data:{ email: email, password: password, user_type: 'admin' },
                success:function(response) {
                    try {
                        let result = JSON.parse(response);
                        if(result['code'] == 200){
                            window.location = "index.php";
                        }else{
                            $("#error").text(result['message']);
                        }
                    } catch (error) {
                        console.log(response);
                        $("#error").text('Invalid server response.');
                    }
                },
                error: function(xhr, status, error) {
                    var err = JSON.parse(xhr.responseText);
                    $("#error").text(err.Message);
                }
            });
        });
    });
</script>
</body>
</html>