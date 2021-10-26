<?php 
  $play_store_url = "https://play.google.com/store/apps/details?id=com.workfort.pstuian";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>PSTUian</title>
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
      var baseUrl = 'api/mobile/v1/';

      //adding headers to ajax
      $.ajaxSetup({
          headers: { 'x-auth-token': 'unknown' }
      });

      $(function() { // do things when the document is ready
          
      });
  </script>
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
            <a href="index.html" class="site-logo">
              PSTUian
            </a>
            <a href="#" class="ml-auto d-inline-block d-lg-none site-menu-toggle js-menu-toggle text-black"><span
                class="icon-menu h3"></span></a>
          </div>
          <div class="col-12 col-lg-6 ml-auto d-flex">
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
          </div>
          <div class="col-6 d-block d-lg-none text-right">
          </div>
        </div>
      </div>
      
        <div class="site-navbar py-2 js-sticky-header site-navbar-target d-none pl-0 d-lg-block" role="banner">
            <div class="container">
                <div class="d-flex align-items-center">
                    <div class="mr-auto">
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu js-clone-nav mr-auto d-none pl-0 d-lg-block">
                              <li class="active"><a href="index.php" class="nav-link text-left">Home</a></li>
                              <li><a href="students.php" class="nav-link text-left">Students</a></li>
                              <li><a href="teachers.php" class="nav-link text-left">Teachers</a></li>
                              <li><a href="employees.php" class="nav-link text-left">Employees</a></li>
                              <li><a href="donation.php" class="nav-link text-left">Donation</a></li>
                              <li><a href="contact.php" class="nav-link text-left">Contact</a></li>
                            </ul>                                                                                                                                                                                                                                                                                         
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>