<?php 
  $uri = $_SERVER['REQUEST_URI'];
  $path = isset($_GET['from']) ? $_GET['from'] : 'index.php';

  session_start();
  if(isset($_SESSION['x_auth_token'])) {
    header('Location: ' . $path);
    return;
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>PSTUian | Sign In</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=B612+Mono|Cabin:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="fonts/icomoon/style.css">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  
  <link rel="stylesheet" href="css/aos.css">
  <link rel="stylesheet" href="css/style.css">

  <!-- JQuery -->
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script>
      // Base api url
      let baseUrl = 'api/mobile/v1/';

      //adding headers to ajax
      $.ajaxSetup({
          headers: { 'x-auth-token': null }
      });
  </script>
  <!-- Register the device -->
  <script src="js/device.js"></script>
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
  <div class="site-wrap">
    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>

    <div class="header-top">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12 col-lg-6 d-flex">
            <a href="index.php" class="site-logo">
            PSTUian | Sign In
            </a>
            <!-- <a href="#" class="ml-auto d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black"><span
                class="icon-menu h3"></span></a> -->
          </div>
          <!-- <div class="col-12 col-lg-6 ml-auto d-flex">
            <div class="ml-md-auto top-social d-none d-lg-inline-block">
              <a href="https://www.facebook.com/groups/pstuian/" class="d-inline-block p-3"><span class="icon-facebook"></span></a>
                <a href="#" class="d-inline-block p-3"><span class="icon-twitter"></span></a>
                <a href="#" class="d-inline-block p-3"><span class="icon-instagram"></span></a>
            </div>
            <form action="#" class="search-form d-inline-block">

              <div class="d-flex">
                <input type="email" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-secondary" ><span class="icon-search"></span></button>
              </div>
            </form>
          </div> -->
          <div class="col-6 d-block d-lg-none text-right">
          </div>
        </div>
      </div>
    </div>

    <div class="site-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
              <div class="row">
                <div class="col-md-12 form-group">
                  <label for="user-type">User Type</label>
                  <select class="form-control form-control-lg bg-light float-start" id="user-type" aria-label="User Type">
                    <option selected value="student">Student</option>
                    <option value="teacher">Teacher</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                  <label for="email">Email Address</label>
                  <input type="email" id="email" class="form-control form-control-lg">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                  <label for="password">Password</label>
                  <input type="password" id="password" class="form-control form-control-lg">
                </div>
              </div>

              <div class="row">
                  <div class="col-12">
                      <input type="submit" value="Sign In" onclick="signin()" class="btn btn-primary py-3 px-5">
                  </div>
              </div>
          </div>
        </div>
      </div>
    </div>

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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
        
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <a href="terms_and_conditions.php">Terms and Conditions</a>
                    <span class="mx-1">&bullet;</span>
                    <a href="privacy_policy.php">Privacy Policy</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="copyright">
                        <p>
                            Copyright Workfort&copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <!-- .site-wrap -->

    <!-- loader -->
    <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#ff5e15"/></svg></div>

    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/main.js"></script>
    <script>
      function signin() {
        let user_type = $('#user-type').val();
        let email = $('#email').val();
        let password = $('#password').val();
        if(user_type == null || user_type == '' || email == null || email == '' ||  password == null || password == '') {
          $('#details-dialog-title').text('Error');
          $('#details-dialog-message').text('Sorry, all fields are required.');
          $('#details-modal').modal('show');
          return;
        }
        let deviceId = localStorage.getItem("device_id");
        if(!deviceId || deviceId === null && deviceId === '') {
          $('#details-dialog-title').text('Error');
          $('#details-dialog-message').text('Sorry, invalid device.');
          $('#details-modal').modal('show');
          return;
        }

        let data = { 
          user_type : user_type, 
          email : email, 
          password : password,
          device_id : deviceId,
        };
        $.ajax({
            url: `${baseUrl}auth.php?call=signIn`,
            type:'post',
            data: data,
            success:function(response) {
              console.log(response);
              let result = JSON.parse(response);
              if(result['success'] !== true) {
                $('#details-dialog-title').text('Error');
                $('#details-dialog-message').text(result['message']);
                $('#details-modal').modal('show');
                return;
              }

              let auth_token = result['auth_token'];
              let user = result['data'];
              sessionStorage.setItem("auth_token", auth_token);
              sessionStorage.setItem("user_id", user['id']);
              sessionStorage.setItem("user_type", user_type);
              window.location.replace('<?php echo $path; ?>');
            },
            error: function(xhr, status, error) {
              $('#details-dialog-title').text('Error');
              try {
                var err = JSON.parse(xhr.responseText);
                $('#details-dialog-message').text(err.Message);
              } catch (error) {
                $('#details-dialog-message').text("Sorry, failed to send your query. Please try again");
                console.error(error);
              }
              $('#details-modal').modal('show');
            }
        });
      }
    </script>
</body>
</html>