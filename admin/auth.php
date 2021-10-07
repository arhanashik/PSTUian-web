<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Authentication</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Authentication</li>
        </ol>
        <div class="mb-4">
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">User Id</th>
                        <th scope="col">User Type</th>
                        <th scope="col">Auth Token</th>
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
        loadAuths();
    });

    function loadAuths() {
        $.ajax({
            url:`${baseUrl}auth.php?call=getAll`,
            type:'get',
            success:function(response){
                console.log(response);
                $('#data-table tbody').empty();
                var list = JSON.parse(response);
                if(list['code'] && list['code'] !== 200) {
                    $('#toast-title').text('Failed');
                    $('#toast-message').text(list['message']);
                    $('#toast').toast('show');
                    return;
                }
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
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreAuth(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteAuth(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.user_id}</td>` +
        `<td>${item.user_type}</td>` +
        `<td>${item.auth_token}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${deleted? btnRestore : btnDelete}</td>` +
        `</tr>`;
    }

    function restoreAuth(auth) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}auth.php?call=restore`,
            type:'post',
            data: { id: auth.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    auth.deleted = 0;
                    $(`table#data-table tr#${auth.id}`).replaceWith(generateTr(auth));
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

    function deleteAuth(auth) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}auth.php?call=delete`,
            type:'post',
            data: { id: auth.id },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    auth.deleted = 1;
                    $(`table#data-table tr#${auth.id}`).replaceWith(generateTr(auth));
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
