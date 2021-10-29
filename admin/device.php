<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Device</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Device</li>
        </ol>
        <div class="mb-4">
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">FCM Token</th>
                        <!-- <th scope="col">Model</th>
                        <th scope="col">Android</th>
                        <th scope="col">iOS</th> -->
                        <th scope="col">Version Code</th>
                        <th scope="col">Version Name</th>
                        <!-- <th scope="col">IP Address</th> -->
                        <!-- <th scope="col">Lat</th>
                        <th scope="col">Lng</th> -->
                        <!-- <th scope="col">Locale</th> -->
                        <!-- <th scope="col">Created At</th>
                        <th scope="col">Updated At</th> -->
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
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
                        <div class="form-group">
                            <label for="data-edit-item-id">ID</label>
                            <input type="text" class="form-control" id="data-edit-item-id" disabled/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-token">FCM Token</label>
                            <textarea type="text" rows="4" class="form-control" id="data-edit-item-token"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-model">Modle</label>
                            <input type="text" class="form-control" id="data-edit-item-model"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-android">Android Version</label>
                            <input type="text" class="form-control" id="data-edit-item-android"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-ios">iOS Version</label>
                            <input type="text" class="form-control" id="data-edit-item-ios"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-version-code">App Version Code</label>
                            <input type="text" class="form-control" id="data-edit-item-version-code"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-version-name">App Version Name</label>
                            <input type="text" class="form-control" id="data-edit-item-version-name"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-ip">IP Address</label>
                            <input type="text" class="form-control" id="data-edit-item-ip"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-lat">Latitude</label>
                            <input type="text" class="form-control" id="data-edit-item-lat"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-lng">Longitude</label>
                            <input type="text" class="form-control" id="data-edit-item-lng"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-locale">Locale</label>
                            <input type="text" class="form-control" id="data-edit-item-locale"/>
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
                    <label for="data-item-id">ID</label>
                    <p type="text" class="form-control" id="data-item-id"></p>
                    <label for="data-item-token">FCM Token</label>
                    <p type="text" class="form-control" id="data-item-token"></p>
                    <label for="data-item-model">Model</label>
                    <p type="text" class="form-control" id="data-item-model"></p>
                    <label for="data-item-android">Android Version</label>
                    <p type="text" class="form-control" id="data-item-android"></p>
                    <label for="data-item-ios">iOS Version</label>
                    <p type="text" class="form-control" id="data-item-ios"></p>
                    <label for="data-item-version-code">App Version Code</label>
                    <p type="text" class="form-control" id="data-item-version-code"></p>
                    <label for="data-item-version-name">App Version Name</label>
                    <p type="text" class="form-control" id="data-version-name"></p>
                    <label for="data-item-ip">IP Address</label>
                    <p type="text" class="form-control" id="data-item-ip"></p>
                    <label for="data-item-lat">Latitude</label>
                    <p type="text" class="form-control" id="data-item-lat"></p>
                    <label for="data-item-lng">Longitude</label>
                    <p type="text" class="form-control" id="data-item-lng"></p>
                    <label for="data-item-locale">Locale</label>
                    <p type="text" class="form-control" id="data-item-locale"></p>
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
    var devices = [];
    $(document).ready(function() {
        loadDevices();
    });

    function loadDevices() {
        $.ajax({
            url: `${baseUrl}device.php?call=getAll`,
            type:'get',
            success:function(response){
                $('#data-table tbody').empty();
                devices = JSON.parse(response);
                for (i = 0; i < devices.length; i++) {
                    $('#data-table > tbody:last-child').append(generateTr(devices[i]));
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function generateTr(item) {
        var param = JSON.stringify(item.id);
        var deleted = item.deleted !== 0;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-id='${item.id}'><i class="far fa-edit"></i></button>`;
        var btnDetails = `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#data-details-modal" data-id='${item.id}'><i class="far fa-file-alt"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreDevice(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteDevice(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.fcm_token}</td>` +
        // `<td>${item.model}</td>` +
        // `<td>${item.android_version}</td>` +
        // `<td>${item.ios_version}</td>` +
        `<td>${item.app_version_code}</td>` +
        `<td>${item.app_version_name}</td>` +
        // `<td>${item.ip_address}</td>` +
        // `<td>${item.lat}</td>` +
        // `<td>${item.lng}</td>` +
        // `<td>${item.locale}</td>` +
        // `<td>${item.created_at}</td>` +
        // `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${btnDetails} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function updateData() {
        var id = $('#data-edit-item-id').val();
        var fcm_token = $('#data-edit-item-token').val();
        var model = $('#data-edit-item-model').val();
        var android_version = $('#data-edit-item-android').val();
        var ios_version = $('#data-edit-item-ios').val();
        var app_version_code = $('#data-edit-item-version-code').val();
        var app_version_name = $('#data-edit-item-version-name').val();
        var ip_address = $('#data-edit-item-ip').val();
        var lat = $('#data-edit-item-lat').val();
        var lng = $('#data-edit-item-lng').val();
        var locale = $('#data-edit-item-locale').val();
        var data = { 
            id: id,
            fcm_token: fcm_token, 
            model: model, 
            android_version: android_version, 
            ios_version: ios_version, 
            app_version_code: app_version_code,
            app_version_name: app_version_name,
            ip_address: ip_address,
            lat: lat,
            lng: lng,
            locale: locale,
        }
        $.ajax({
            url: `${baseUrl}device.php?call=update`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadDevices();
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

    function restoreDevice(id) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}device.php?call=restore`,
            type:'post',
            data: { id: id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadDevices();
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

    function deleteDevice(id) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}device.php?call=delete`,
            type:'post',
            data: { id: id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadDevices();
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
            url: `${baseUrl}device.php?call=deletePermanent`,
            type:'post',
            data: { id: id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadStudents(selectedFaculty, selectedBatch);
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

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getDevice(id);

        var modal = $(this);
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-token').val(item.fcm_token);
        modal.find('#data-edit-item-model').val(item.model);
        modal.find('#data-edit-item-android').val(item.android_version);
        modal.find('#data-edit-item-ios').val(item.ios_version);
        modal.find('#data-edit-item-version-code').val(item.app_version_code);
        modal.find('#data-edit-item-version-name').val(item.app_version_name);
        modal.find('#data-edit-item-ip').val(item.ip_address);
        modal.find('#data-edit-item-lat').val(item.lat);
        modal.find('#data-edit-item-lng').val(item.lng);
        modal.find('#data-edit-item-locale').val(item.locale);
    });

    $('#data-details-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getDevice(id);

        var modal = $(this);
        modal.find('#data-item-id').text(item.id);
        modal.find('#data-item-token').text(item.fcm_token);
        modal.find('#data-item-model').text(item.model);
        modal.find('#data-item-android').text(item.android_version);
        modal.find('#data-item-ios').text(item.ios_version);
        modal.find('#data-item-version-code').text(item.app_version_code);
        modal.find('#data-item-version-name').text(item.app_version_name);
        modal.find('#data-item-ip').text(item.ip_address);
        modal.find('#data-item-lat').text(item.lat);
        modal.find('#data-item-lng').text(item.lng);
        modal.find('#data-item-locale').text(item.locale);
        modal.find('#data-item-created').text(item.created_at);
        modal.find('#data-item-updated').text(item.updated_at);
    });

    function getDevice(id) {
        return devices.find(device => device.id == id);
    }
</script>
<?php include('./footer.php'); ?>
