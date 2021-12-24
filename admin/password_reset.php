<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Password Reset</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Password Reset</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                How to create password reset link
            </div>
            <div class="card-body">
                <div>
                    Add a new entry in the password reset table and use the data from that entry for USER_ID, USER_TYPE, AUTH_TOKEN
                    <ol>
                        <li><b>USER_ID:</b> Id of the user who want to reset the password(e.g. 1302018)</li>
                        <li><b>USER_TYPE:</b> Type of the user who want to reset the password(student/teacher)</li>
                        <li><b>AUTH_TOKEN:</b> Authentication token to reset the password(keep it empty or  type "auto" to auto generate)</li>
                    <ol>
                </div>
                <div class="row">
                    <div class="col-2 form-group">
                        <input type="text" class="form-control" id="url-input-user-id" placeholder="[USER_ID]"/>
                    </div>
                    <div class="col-2 form-group">
                        <select class="form-select" id="url-input-user-type" aria-label="User type">
                            <option selected value="">[USER_TYPE]</option>
                            <option value="student">student</option>
                            <option value="teacher">teacher</option>
                        </select>
                    </div>
                    <div class="col-5 form-group">
                        <input type="text" class="form-control" id="url-input-auth-token" placeholder="[AUTH_TOKEN]"/>
                    </div>
                    <div class="col-3 form-group">
                        <button type="button" class="btn btn-primary" onclick="generateResetLink()">Generate Reset Link</button>
                    </div>
                </div>
                <div>
                    <br/>
                    Password reset link:
                    <br/>
                    <b>
                        https://www.pstuian.com/reset_password.php?ui=<span id="url-user-id">[USER_ID]</span>&ut=<span id="url-user-type">[USER_TYPE]</span>&at=<span id="url-auth-token">[AUTH_TOKEN]</span>
                    </b>
                </div>
            </div>
        </div>
        <div class="mb-4">
        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
        Add New
        </button>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">User Id</th>
                        <th scope="col">Type</th>
                        <th scope="col">Email</th>
                        <th scope="col">Auth Token</th>
                        <th scope="col">Created</th>
                        <th scope="col">Updated</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <nav aria-label="Data Table Page navigation">
                <div class="row">
                    <div class="col-6">
                        <span id="page-number">Showing results for Page 1</span>
                    </div>
                    <div class="col-6">
                        <ul class="pagination justify-content-end">
                            <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous" onclick="loadPrevious()">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#" onclick="loadPage(1)">1</a></li>
                            <li class="page-item"><a class="page-link" href="#" onclick="loadPage(2)">2</a></li>
                            <li class="page-item"><a class="page-link" href="#" onclick="loadPage(3)">3</a></li>
                            <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next" onclick="loadNext()">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Data Add Modal -->
    <div class="modal fade" id="data-add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <p class="text-danger" id="data-add-modal-error"></p>
                        <div class="form-group">
                            <label for="data-add-item-user-id">User Id</label>
                            <input type="text" class="form-control" id="data-add-item-user-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-user-type">User type</label>
                            <select class="form-select" id="data-add-item-user-type" aria-label="User type">
                                <option selected value="student">student</option>
                                <option value="teacher">teacher</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-request-email">Email</label>
                            <input type="email" class="form-control" id="data-add-item-email"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-auth-token">Auth Token</label>
                            <input type="text" class="form-control" id="data-add-item-auth-token" value="auto"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addData()">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Edit Modal -->
    <div class="modal fade" id="data-edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <p class="text-danger" id="data-edit-modal-error"></p>
                        <input type="text" class="form-control"  id="data-edit-item-id" hidden/>
                        <div class="form-group">
                            <label for="data-edit-item-user-id">User Id</label>
                            <input type="text" class="form-control" id="data-edit-item-user-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-user-type">User type</label>
                            <select class="form-select" id="data-edit-item-user-type" aria-label="User type">
                                <option selected value="student">student</option>
                                <option value="teacher">teacher</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-request-email">Email</label>
                            <input type="email" class="form-control" id="data-edit-item-email"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-auth-token">Auth Token</label>
                            <input type="text" class="form-control" id="data-edit-item-auth-token" value="auto"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateData()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Details Modal -->
    <div class="modal fade" id="data-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="data-item-id">Id</label>
                    <p type="text" class="form-control" id="data-item-id"></p>
                    <label for="data-item-user-id">User Id</label>
                    <p type="text" class="form-control" id="data-item-user-id"></p>
                    <label for="data-item-user-type">User Type</label>
                    <p type="text" class="form-control" id="data-item-user-type"></p>
                    <label for="data-item-email">Email</label>
                    <p type="text" class="form-control" id="data-item-email"></p>
                    <label for="data-item-auth-token">Auth Token</label>
                    <p type="text" class="form-control" id="data-item-auth-token"></p>
                    <label for="data-item-created">Created At</label>
                    <p type="text" class="form-control" id="data-item-created"></p>
                    <label for="data-item-updated">Updated At</label>
                    <p type="text" class="form-control" id="data-item-updated"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function generateResetLink() {
        let user_id = $('#url-input-user-id').val();
        let user_type = $('#url-input-user-type').val();
        let auth_token = $('#url-input-auth-token').val();

        $('#url-user-id').html(user_id);
        $('#url-user-type').html(user_type);
        $('#url-auth-token').html(auth_token);
    }

    var currentPage = 1;
    var data = [];
    $(document).ready(function() {
        loadData(currentPage);
    });

    function loadPrevious() {
        if(currentPage > 1) currentPage--;
        loadData(currentPage);
    }

    function loadNext() {
        currentPage++;
        loadData(currentPage);
    }

    function loadPage(page) {
        currentPage = page;
        loadData(currentPage);
    }

    function loadData(page) {
        $.ajax({
            url: `${baseUrl}password_reset.php?call=getAll`,
            data: { page : page, limit : 10 },
            type:'get',
            success:function(response) {
                console.log(response);
                $('#page-number').html(`Showing results for Page ${page}`);
                $('#data-table tbody').empty();
                data = JSON.parse(response);
                for (i = 0; i < data.length; i++) {
                    $('#data-table > tbody:last-child').append(generateTr(data[i]));
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function generateTr(item) {
        var param = item.id;
        var deleted = item.deleted !== 0;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-id='${param}'><i class="far fa-edit"></i></button>`;
        var btnDetails = `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#data-details-modal" data-id='${param}'><i class="far fa-file-alt"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreData(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteData(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.user_id}</td>` +
        `<td>${item.user_type}</td>` +
        `<td>${item.email}</td>` +
        `<td>${item.auth_token}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${btnDetails} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function addData() {
        var user_id = $('#data-add-item-user-id').val();
        var user_type = $('#data-add-item-user-type').val();
        var email = $('#data-add-item-email').val();
        var auth_token = $('#data-add-item-auth-token').val();
        var data = { 
            user_id: user_id, 
            user_type: user_type, 
            email: email, 
            auth_token: auth_token,
        }
        $.ajax({
            url: `${baseUrl}password_reset.php?call=add`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadData(currentPage);
                    $('#data-add-modal').modal('hide');
                } else {
                    $('#data-add-modal-error').text(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $('#data-add-modal-error').text(err.Message);
                console.log(err);
            }
        });
    }

    function updateData() {
        var id = $('#data-edit-item-id').val();
        var user_id = $('#data-edit-item-user-id').val();
        var user_type = $('#data-edit-item-user-type').val();
        var email = $('#data-edit-item-email').val();
        var auth_token = $('#data-edit-item-auth-token').val();
        var data = { 
            id: id,
            user_id: user_id, 
            user_type: user_type, 
            email: email, 
            auth_token: auth_token,
        }
        $.ajax({
            url: `${baseUrl}password_reset.php?call=update`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadData(currentPage);
                    $('#data-edit-modal').modal('hide');
                } else {
                    $('#data-edit-modal-error').text(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $('#data-edit-modal-error').text(err.Message);
                console.log(err);
            }
        });
    }

    function restoreData(id) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}password_reset.php?call=restore`,
            type:'post',
            data: { id: id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadData(currentPage);
                } else {
                    console.log(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function deleteData(id) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}password_reset.php?call=delete`,
            type:'post',
            data: { id: id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadData(currentPage);
                } else {
                    console.log(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function deletePermanent(id) {
        if(!confirm("Are you sure you want to delete this PERMANENTLY? It cannot be restored again.")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}password_reset.php?call=deletePermanent`,
            type:'post',
            data: { id: id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadData(currentPage);
                } else {
                    console.log(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    $('#data-add-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        var modal = $(this);
        modal.find('#data-add-modal-error').html('');
        modal.find('#data-add-item-user-id').val('');
        modal.find('#data-add-item-user-type').val('student');
        modal.find('#data-add-item-email').val('');
        modal.find('#data-add-item-auth-token').val('auto');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getItem(id);

        var modal = $(this);
        modal.find('#data-edit-modal-error').html('');
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-user-id').val(item.user_id);
        modal.find('#data-edit-item-user-type').val(item.user_type);
        modal.find('#data-edit-item-email').val(item.email);
        modal.find('#data-edit-item-auth-token').val(item.auth_token);
    });

    $('#data-details-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getItem(id);

        var modal = $(this);
        modal.find('#data-item-id').text(item.id);
        modal.find('#data-item-user-id').text(item.user_id);
        modal.find('#data-item-user-type').text(item.user_type)
        modal.find('#data-item-email').text(item.email);
        modal.find('#data-item-auth-token').text(item.auth_token);
        modal.find('#data-item-created').text(item.created_at);
        modal.find('#data-item-updated').text(item.updated_at);
    });

    function getItem(id) {
        return data.find(item => item.id == id);
    }
</script>
<?php include('./footer.php'); ?>
