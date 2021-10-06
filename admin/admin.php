<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Admin</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Admin</li>
        </ol>
        <div class="mb-4">
        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
        Add New Admin
        </button>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Email</th>
                        <th scope="col">Password</th>
                        <th scope="col">Role</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
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
                        <p id="data-add-modal-error"></p>
                        <div class="form-group">
                            <label for="data-add-item-email">Email</label>
                            <input type="text" class="form-control" id="data-add-item-email"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-password">Password</label>
                            <input type="text" class="form-control" id="data-add-item-password"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-role">Role</label>
                            <select class="form-select" id="data-add-item-role" aria-label="Select Role">
                                <option selected value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
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
                        <p id="data-edit-modal-error"></p>
                        <input type="text" class="form-control"  id="data-edit-item-id" hidden/>
                        <div class="form-group">
                            <label for="data-edit-item-email">Email</label>
                            <input type="text" class="form-control" id="data-edit-item-email"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-password">Password</label>
                            <input type="text" class="form-control" id="data-edit-item-password"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-role">Role</label>
                            <select class="form-select" id="data-edit-item-role" aria-label="Select Role">
                                <option selected value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
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
</main>
<script>
    $(document).ready(function() {
        loadAdmins();
    });

    function loadAdmins() {
        $.ajax({
            url:'/PSTUian-web/admin/api/admin.php?call=getAll',
            type:'get',
            success:function(response){
                $('#data-table tbody').empty();
                var list = JSON.parse(response);
                for (i = 0; i < list.length; i++) {
                    $('#data-table > tbody:last-child').append(generateTr(list[i]));
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function generateTr(item) {
        var param = JSON.stringify(item);
        var deleted = item.deleted !== 0;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-json='${param}'><i class="far fa-edit"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreAdmin(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteAdmin(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.email}</td>` +
        `<td>${item.password}</td>` +
        `<td>${item.role}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${deleted? btnRestore : btnDelete}</td>` +
        `</tr>`;
    }

    function addData() {
        var email = $('#data-add-item-email').val();
        var password = $('#data-add-item-password').val();
        var role = $('#data-add-item-role').val();
        var data = { 
            email: email, 
            password: password,
            role: role
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/admin.php?call=add',
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadAdmins();
                    $('#data-add-modal').modal('hide');
                } else {
                    $('#data-add-modal-error').text(data['message']);
                    console.log(data['message']);
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
        var email = $('#data-edit-item-email').val();
        var password = $('#data-edit-item-password').val();
        var role = $('#data-edit-item-role').val();
        var data = { 
            id: id,
            email: email, 
            password: password,
            role: role
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/admin.php?call=update',
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadAdmins();
                    $('#data-edit-modal').modal('hide');
                } else {
                    $('#data-edit-modal-error').text(data['message']);
                    console.log(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $('#data-edit-modal-error').text(err.Message);
                console.log(err);
            }
        });
    }

    function restoreAdmin(admin) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/admin.php?call=restore',
            type:'post',
            data: { id: admin.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    admin.deleted = 0;
                    $(`table#data-table tr#${admin.id}`).replaceWith(generateTr(admin));
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

    function deleteAdmin(admin) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/admin.php?call=delete',
            type:'post',
            data: { id: admin.id },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    admin.deleted = 1;
                    $(`table#data-table tr#${admin.id}`).replaceWith(generateTr(admin));
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
        modal.find('#data-add-item-email').val('');
        modal.find('#data-add-item-password').val('');
        modal.find('#data-add-item-role').val('admin');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var item = button.data('json');

        var modal = $(this);
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-email').val(item.email);
        modal.find('#data-edit-item-password').val(item.password);
        modal.find('#data-edit-item-role').val(item.role);
    });
</script>
<?php include('./footer.php'); ?>
