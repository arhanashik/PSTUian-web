<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Settings</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Settings</li>
        </ol>
        <div class="mb-4 card">
            <div class="card-body">
                Theme
                <div class="float-end">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="themeOptions" id="inlineRadio1" value="light">
                        <label class="form-check-label" for="inlineRadio1">Light</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="themeOptions" id="inlineRadio2" value="dark">
                        <label class="form-check-label" for="inlineRadio2">Dark</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-4 card">
            <div class="card-body">
                Password
                <div class="float-end">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#data-password-change-modal">
                    Change
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div class="modal fade" id="data-password-change-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <p id="data-password-change-modal-error"></p>
                        <input type="text" class="form-control"  id="data-password-change-item-id" hidden value="<?php echo $_SESSION['admin']['id']; ?>"/>
                        <div class="form-group">
                            <label for="data-password-change-item-old-password">Old Password</label>
                            <input type="text" class="form-control" id="data-password-change-item-old-password"/>
                        </div>
                        <div class="form-group">
                            <label for="data-password-change-item-new-password">New Password</label>
                            <input type="text" class="form-control" id="data-password-change-item-new-password"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="changePassword()">Change</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
     $(document).ready(function() {
        setThemeData();
    });

    function setThemeData() {
        var navbartheme = localStorage.getItem("nav_theme");
        if(navbartheme == null || navbartheme == '') {
            navbartheme = 'light';
        }
        $(`input[type=radio][name=themeOptions][value='${navbartheme}']`).prop("checked", true);
    }

    $('input[type=radio][name=themeOptions]').change(function() {
        var theme = this.value;
        localStorage.setItem("nav_theme", theme);
        $('#sidenavAccordion').removeClass('sb-sidenav-dark');
        $('#sidenavAccordion').removeClass('sb-sidenav-light');
        $('#sidenavAccordion').addClass(theme == 'light'? 'sb-sidenav-light' : 'sb-sidenav-dark');
        $('#topnavAccordion').removeClass('navbar-light bg-light');
        $('#topnavAccordion').removeClass('navbar-dark bg-dark');
        $('#topnavAccordion').addClass(theme == 'light'? 'navbar-light bg-light' : 'navbar-dark bg-dark');

        $('#toast-message').text('Theme changed successfully!');
        $('#toast').toast('show');
    });

    $('#data-password-change-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        var modal = $(this);
        modal.find('#data-password-change-item-old-password').val('');
        modal.find('#data-password-change-item-new-password').val('');
    });

    function changePassword() {
        var id = $('#data-password-change-item-id').val();
        var oldPassword = $('#data-password-change-item-old-password').val();
        var newPassword = $('#data-password-change-item-new-password').val();
        var data = { 
            id: id,
            old_password: oldPassword, 
            new_password: newPassword
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/admin.php?call=changePassword',
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    $('#data-password-change-modal').modal('hide');
                    $('#toast-message').text(data['message']);
                    $('#toast').toast('show');
                } else {
                    $('#data-password-change-modal-error').text(data['message']);
                    console.log(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $('#data-password-change-modal-error').text(err.Message);
                console.log(err);
            }
        });
    }
</script>
<?php include('./footer.php'); ?>
