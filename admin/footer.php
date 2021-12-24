                <?php include('./toast.php'); ?>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Workfort 2021</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="js/scripts.js"></script>
        <script>
            // setup theme value
            var navbartheme = localStorage.getItem("nav_theme");
            if(navbartheme == null || navbartheme == '') {
                navbartheme = 'light';
            }
            $('#sidenavAccordion').removeClass('sb-sidenav-dark');
            $('#sidenavAccordion').removeClass('sb-sidenav-light');
            $('#sidenavAccordion').addClass(navbartheme == 'light'? 'sb-sidenav-light' : 'sb-sidenav-dark');
            $('#topnavAccordion').removeClass('navbar-light bg-light');
            $('#topnavAccordion').removeClass('navbar-dark bg-dark');
            $('#topnavAccordion').addClass(navbartheme == 'light'? 'navbar-light bg-light' : 'navbar-dark bg-dark');

            // set nav bar expansion state
            setNavBarExpanionState('#nav-link-academic', '#collapse-academic', "nav_bar_academic_expanded");
            setNavBarExpanionState('#nav-link-data', '#collapse-data', "nav_bar_data_expanded");
            setNavBarExpanionState('#nav-link-blood-donation', '#collapse-blood-donation', "nav_bar_blood_donation_expanded");
            setNavBarExpanionState('#nav-link-action', '#collapse-action', "nav_bar_action_expanded");
            // store nav bar collapse state
            storeNavBarState("#nav-link-academic", "nav_bar_academic_expanded");
            storeNavBarState("#nav-link-data", "nav_bar_data_expanded");
            storeNavBarState("#nav-link-blood-donation", "nav_bar_blood_donation_expanded");
            storeNavBarState("#nav-link-action", "nav_bar_action_expanded");

            // sign out
            function signOut() {
                $.ajax({
                    url: `${baseUrl}auth.php?call=signOut`,
                    type:'get',
                    data:{ user_type: 'admin' },
                    success:function(response) {
                        try {
                            let result = JSON.parse(response);
                            if(result['code'] == 200) {
                                window.location = "login.php";
                            } else{
                                $('#toast-title').text('Error');
                                $('#toast-message').text(result['message']);
                                $('#toast').toast('show');
                            }
                        } catch (error) {
                            console.log(response);
                            $('#toast-title').text('Error');
                            $('#toast-message').text('Invalid server response.');
                            $('#toast').toast('show');
                        }
                    },
                    error: function(xhr, status, error) {
                        var err = JSON.parse(xhr.responseText);
                        $('#toast-title').text('Error');
                        $('#toast-message').text("Failed to sign out");
                        $('#toast').toast('show');
                    }
                });
            }

            function setNavBarExpanionState(navBarId, childId, storeVar, defaultCollapsed = true) {
                var navbarExpaned = localStorage.getItem(storeVar);
                if(navbarExpaned == null || navbarExpaned == '') {
                    navbarExpaned = defaultCollapsed? 'collapsed' : 'expanded';
                }
                if(navbarExpaned == 'collapsed') {
                    $(navBarId).addClass('collapsed');
                    $(childId).removeClass('show');
                } else {
                    $(navBarId).removeClass('collapsed');
                    $(childId).addClass('show');
                }
            }

            function storeNavBarState(id, storeVar) {
                $( id ).click(function() {
                    var isCollapsed = $(id).hasClass('collapsed');
                    var collapsed = isCollapsed? 'collapsed' : 'expanded';
                    localStorage.setItem(storeVar, collapsed);
                });
            }
        </script>
    </body>
</html>