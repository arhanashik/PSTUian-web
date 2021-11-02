<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                Welcome to PSTUian
            </div>
            <div class="card-body">
                This Admin Panel is okay enough to provide necessary support for the data of 
                PSTUian app and website. You can see some shortcuts in the Dashboard. Expand <strong>Tables</strong> from the left navigation bar to
                see more options. Enjoy!
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Faculty</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="faculty.php">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">Students</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="student.php">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Admin</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="admin.php">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">Settings</div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="settings.php">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <h4>Current Config</h4>
            <div class="mb-4 col-4">
                <table class="table table-bordered table-hover" id="data-table-1">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="mb-4">
                <button type="button" class="btn btn-success float-end <?php if($role != 'super_admin') echo 'invisible'; ?>" data-bs-toggle="modal" data-bs-target="#data-add-modal">
                Add New Config
                </button>
                <h4>All Config</h4>
                <table class="table table-bordered table-hover" id="data-table">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Android</th>
                            <th scope="col">iOS</th>
                            <th scope="col">Data</th>
                            <th scope="col">Api</th>
                            <th scope="col">Admin Api</th>
                            <th scope="col">Force Refresh</th>
                            <th scope="col">Force Update</th>
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
                            <label for="data-add-item-android">Android Version</label>
                            <input type="text" class="form-control" id="data-add-item-android"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-ios">iOS Version</label>
                            <input type="text" class="form-control" id="data-add-item-ios"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-api">Api Version</label>
                            <input type="text" class="form-control" id="data-add-item-api"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-data-refresh">Data Refresh Version</label>
                            <input type="text" class="form-control" id="data-add-item-data-refresh"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-admin-api">Admin Api Version</label>
                            <input type="text" class="form-control" id="data-add-item-admin-api"/>
                        </div>
                        <div class="form-group">
                            <label>Force</label>
                            <div class="form-control custom-checkbox">
                                <label class="custom-control-label" for="data-add-item-frefresh">Refresh</label>
                                <input type="checkbox" class="custom-control-input" id="data-add-item-frefresh">

                                <label class="custom-control-label" for="data-add-item-fupdate">Update</label>
                                <input type="checkbox" class="custom-control-input" id="data-add-item-fupdate">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="data-add-btn" onclick="addData()">Add</button>
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
                            <label for="data-edit-item-android">Android Version</label>
                            <input type="text" class="form-control" id="data-edit-item-android"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-ios">iOS Version</label>
                            <input type="text" class="form-control" id="data-edit-item-ios"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-data-refresh">Data Refresh Version</label>
                            <input type="text" class="form-control" id="data-edit-item-data-refresh"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-api">Api Version</label>
                            <input type="text" class="form-control" id="data-edit-item-api"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-admin-api">Admin Api Version</label>
                            <input type="text" class="form-control" id="data-edit-item-admin-api"/>
                        </div>
                        <div class="form-group">
                            <label>Force</label>
                            <div class="form-control custom-checkbox">
                                <label class="custom-control-label" for="data-edit-item-frefresh">Refresh</label>
                                <input type="checkbox" class="custom-control-input" id="data-edit-item-frefresh">

                                <label class="custom-control-label" for="data-edit-item-fupdate">Update</label>
                                <input type="checkbox" class="custom-control-input" id="data-edit-item-fupdate">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="data-edit-btn" onclick="updateData()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    var latestConfig = null;
    $(document).ready(function() {
        loadLatest();
        loadAll();
    });

    function loadLatest() {
        $.ajax({
            url: `${baseUrl}config.php?call=getLatest`,
            type:'get',
            success:function(response) {
                var data = JSON.parse(response);
                if(!data || !data['success']) {
                    $('#toast-title').text("Error");
                    $('#toast-message').text("No Current Config Found");
                    $('#toast').toast('show');
                } else {
                    var item = data['data'];
                    $('#data-table-1 tbody').empty();
                    for (const [key, value] of Object.entries(item)) {
                        var tr = `<tr><th scope="row">${key}</th><td>${value}</td></tr>`;
                        if(key === 'deleted') {
                            tr = `<tr><th scope="row">${key}</th><td>${value == 0}</td></tr>`;
                        }
                        if(key === 'force_refresh' || key === 'force_update') {
                            tr = `<tr><th scope="row">${key}</th><td>${value == 1}</td></tr>`;
                        }
                        $('#data-table-1 > tbody:last-child').append(tr);
                    }
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $('#toast-title').text("Ops, something's wrong");
                $('#toast-message').text(err);
                $('#toast').toast('show');
            }
        });
    }

    function loadAll() {
        $.ajax({
            url: `${baseUrl}config.php?call=getAll`,
            type:'get',
            success:function(response) {
                var list = JSON.parse(response);
                if(!list || list.length <= 0) {
                    $('#toast-title').text("No Config");
                    $('#toast-message').text("Add some config first");
                    $('#toast').toast('show');
                } else {
                    $('#data-table tbody').empty();
                    for (i = 0; i < list.length; i++) {
                        $('#data-table > tbody:last-child').append(generateTr(list[i]));
                    }
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
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreConfig(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteConfig(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.android_version}</td>` +
        `<td>${item.ios_version}</td>` +
        `<td>${item.data_refresh_version}</td>` +
        `<td>${item.api_version}</td>` +
        `<td>${item.admin_api_version}</td>` +
        `<td>${item.force_refresh == 1}</td>` +
        `<td>${item.force_update == 1}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function addData() {
        var loadingStateBtn = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
        $('#data-add-btn').html(loadingStateBtn);
        var android_version = $('#data-add-item-android').val();
        var ios_version = $('#data-add-item-ios').val();
        var api_version = $('#data-add-item-api').val();
        var data_refresh_version = $('#data-add-item-data-refresh').val();
        var admin_api_version = $('#data-add-item-admin-api').val();
        var force_refresh = $('#data-add-item-frefresh').prop('checked') ? 1 : 0;
        var force_update = $('#data-add-item-fupdate').prop('checked') ? 1 : 0;
        var data = { 
            android_version: android_version,
            ios_version: ios_version, 
            api_version: api_version,
            data_refresh_version: data_refresh_version,
            admin_api_version: admin_api_version,
            force_refresh: force_refresh,
            force_update: force_update,
        }
        $.ajax({
            url: `${baseUrl}config.php?call=add`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadAll();
                    $('#data-add-btn').html('Done');
                    $('#data-add-modal').modal('hide');
                } else {
                    $('#data-add-btn').html('Add');
                    $('#data-add-modal-error').text(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $('#data-add-btn').html('Add');
                $('#data-add-modal-error').text(err.Message);
                console.log(err);
            }
        });
    }

    function updateData() {
        var loadingStateBtn = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        $('#data-edit-btn').html(loadingStateBtn);
        var id = $('#data-edit-item-id').val();
        var android_version = $('#data-edit-item-android').val();
        var ios_version = $('#data-edit-item-ios').val();
        var api_version = $('#data-edit-item-api').val();
        var data_refresh_version = $('#data-edit-item-data-refresh').val();
        var admin_api_version = $('#data-edit-item-admin-api').val();
        var force_refresh = $('#data-edit-item-frefresh').prop('checked') ? 1 : 0;
        var force_update = $('#data-edit-item-fupdate').prop('checked') ? 1 : 0;
        var data = { 
            id: id,
            android_version: android_version,
            ios_version: ios_version, 
            data_refresh_version: data_refresh_version,
            api_version: api_version,
            admin_api_version: admin_api_version,
            force_refresh: force_refresh,
            force_update: force_update,
        }
        $.ajax({
            url: `${baseUrl}config.php?call=update`,
            type:'post',
            data: data,
            success:function(response) {
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadAll();
                    $('#data-edit-btn').html('Save Changes');
                    $('#data-edit-modal').modal('hide');
                } else {
                    $('#data-edit-btn').html('Save Changes');
                    $('#data-edit-modal-error').text(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                    $('#data-edit-btn').html('Save Changes');
                $('#data-edit-modal-error').text(err.Message);
                console.log(err);
            }
        });
    }

    function restoreConfig(slider) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}config.php?call=restore`,
            type:'post',
            data: { id: slider.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadAll();
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

    function deleteConfig(slider) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}config.php?call=delete`,
            type:'post',
            data: { id: slider.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadAll();
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

    function deletePermanent(item) {
        if(!confirm("Are you sure you want to delete this PERMANENTLY? It cannot be restored again.")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}config.php?call=deletePermanent`,
            type:'post',
            data: { id: item.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadAll();
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
        modal.find('#data-add-item-android').val('');
        modal.find('#data-add-item-ios').val('');
        modal.find('#data-add-item-data-refresh').val('');
        modal.find('#data-add-item-api').val('');
        modal.find('#data-add-item-admin-api').val('');
        modal.find('#data-add-item-frefresh').prop("checked", false);
        modal.find('#data-add-item-fupdate').prop("checked", false);
        modal.find('#data-add-btn').html('Add');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var item = button.data('json');

        var modal = $(this);
        modal.find('#data-edit-modal-error').html('');
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-android').val(item.android_version);
        modal.find('#data-edit-item-ios').val(item.ios_version);
        modal.find('#data-edit-item-data-refresh').val(item.data_refresh_version);
        modal.find('#data-edit-item-api').val(item.api_version);
        modal.find('#data-edit-item-admin-api').val(item.admin_api_version);
        modal.find('#data-edit-item-frefresh').prop("checked", item.force_refresh === 1);
        modal.find('#data-edit-item-fupdate').prop("checked", item.force_update === 1);
    });
</script>
<?php include('./footer.php'); ?>