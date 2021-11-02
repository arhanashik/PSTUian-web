<?php 
  if(!isset($_GET['ui']) ||  strlen($_GET['ui']) <= 0 
  || !isset($_GET['ut']) || strlen($_GET['ut']) <= 0
  || !isset($_GET['at']) || strlen($_GET['at']) <= 0) {
    echo 'Invalid Request';
    exit(1);
  }

  $ui = $_GET['ui'];
  $ut = $_GET['ut'];
  $at = $_GET['at'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>PSTUian</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=B612+Mono|Cabin:400,700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">

  <!-- JQuery -->
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script>
      // Base api url
      var baseUrl = 'api/mobile/v1/';

      //adding headers to ajax
      $.ajaxSetup({
          headers: { 'x-auth-token': '<?php echo $at; ?>' }
      });
  </script>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
  <div class="site-wrap">
    <div class="header-top">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12 col-lg-6 d-flex">
            <div class="site-logo">
              PSTUian | Reset Password
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="site-section bg-light">
      <div class="container hide" id="message-container">
        <div class="row">
          <div class="col-lg-12">
            <p id="message"></p>
          </div>
        </div>
      </div>
      <div class="container" id="reset-container">
        <div class="row">
          <div class="col-lg-12">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" class="form-control form-control-lg">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" class="form-control form-control-lg">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <input type="submit" value="Reset Password" onclick="resetPassword()" class="btn btn-primary py-3 px-5">
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- .site-wrap -->

  <!-- Details Modal -->
  <div class="modal fade" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="details-dialog-title">Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="details-dialog-message"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btn-submit" data-dismiss="modal">Try Again</button>
        </div>
      </div>
    </div>
  </div>

  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <script type="text/javascript">
    function resetPassword() {
      var new_password = $('#new-password').val();
      var confirm_password = $('#confirm-password').val();
      if(new_password == null || new_password == '' || confirm_password == null || confirm_password == '') {
        $('#details-dialog-title').text('Error');
        $('#details-dialog-message').text('Sorry, all fields are required.');
        $('#details-modal').modal('show');
        return;
      }
      if(new_password !== confirm_password) {
        $('#details-dialog-title').text('Error');
        $('#details-dialog-message').text('Both passwords should be same.');
        $('#details-modal').modal('show');
        return;
      }
      var data = { 
        user_id: <?php echo $ui; ?>, 
        user_type: '<?php echo $ut; ?>', 
        password: new_password,
      };
      $.ajax({
          url: `${baseUrl}auth.php?call=resetPassword`,
          type:'post',
          data: data,
          success:function(response) {
            var data = JSON.parse(response);
            if(data['success'] === true) {
              $('#reset-container').hide();
              $('#message-container').show();
              $('#message').html(data['message']);
              return;
            }
            $('#details-dialog-title').text('Error');
            $('#btn-submit').html('Try Again');
            $('#details-dialog-message').text(data['message']);
            $('#details-modal').modal('show');
          },
          error: function(xhr, status, error) {
            $('#details-dialog-title').text('Error');
            try {
              var err = JSON.parse(xhr.responseText);
              $('#details-dialog-message').text(err.Message);
            } catch (error) {
              $('#details-dialog-message').text("Sorry, operation failed. Please try again");
              console.error(error);
            }
            $('#btn-submit').html('Try Again');
            $('#details-modal').modal('show');
          }
      });
    }
  </script>
</body>
</html>