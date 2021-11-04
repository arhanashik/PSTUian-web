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
            var navbarTablesExpaned = localStorage.getItem("nav_bar_tables_expanded");
            if(navbarTablesExpaned == null || navbarTablesExpaned == '') {
                navbarTablesExpaned = 'expanded';
            }
            if(navbarTablesExpaned == 'collapsed') {
                $('#nav-link-tables').addClass('collapsed');
                $('#collapseTables').removeClass('show');
            } else {
                $('#nav-link-tables').removeClass('collapsed');
                $('#collapseTables').addClass('show');
            }
            // store nav bar collapse state
            $( "#nav-link-tables" ).click(function() {
                var isCollapsed = $('#nav-link-tables').hasClass('collapsed');
                var collapsed = isCollapsed? 'collapsed' : 'expanded';
                localStorage.setItem("nav_bar_tables_expanded", collapsed);
            });

            // sign out
            function signOut() {
                $.ajax({
                    url: `${baseUrl}auth.php?call=signOut`,
                    type:'get',
                    data:{ user_type: 'admin' },
                    success:function(response){
                        var data = JSON.parse(response);
                        if(data['code'] == 200){
                            sessionStorage.clear();
                            window.location = "login.php";
                        } else{
                            $('#toast-title').text('Error');
                            $('#toast-message').text(data['message']);
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
        </script>
    </body>
</html>