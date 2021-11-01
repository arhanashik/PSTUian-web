<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Notification</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Notification</li>
        </ol>
        <div class="mb-4">
            <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
            Send Notification
            </button>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Device</th>
                        <th scope="col">Type</th>
                        <th scope="col">Title</th>
                        <th scope="col">Message</th>
                        <th scope="col">Result</th>
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
    <div class="modal fade" id="data-add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <p class="text-danger" id="data-add-modal-error"></p>
                        <input type="text" class="form-control" id="data-add-item-device-id" hidden/>
                        <div class="form-group">
                            <label for="data-add-item-type">Type</label>
                            <select class="form-select" id="data-add-item-type" aria-label="Select Type">
                                <option selected value="default">Default</option>
                                <option value="blood_donation">Blood Donation</option>
                                <option value="news">News</option>
                                <option value="help">Help</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-title">Title</label>
                            <input type="text" class="form-control" id="data-add-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-message">Message</label>
                            <textarea type="text" rows="3" class="form-control" id="data-add-item-message"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="data-add-btn" onclick="addData()">
                    Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
        loadNotifications();
    });

    function loadNotifications() {
        $.ajax({
            url:`${baseUrl}notification.php?call=getAll`,
            type:'get',
            success:function(response) {
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
        var deleted = item.deleted !== 0;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreNotification(` + item.id + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteQuery(` + item.id + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + item.id + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.device_id}</td>` +
        `<td>${item.type}</td>` +
        `<td>${item.title}</td>` +
        `<td>${item.message}</td>` +
        `<td>${item.data}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function addData() {
        var device_id = $('#data-add-item-device-id').val();
        var type = $('#data-add-item-type').val();
        var title = $('#data-add-item-title').val();
        var message = $('#data-add-item-message').val();
        var data = { 
            device_id: device_id,
            type: type, 
            title: title,
            message: message
        }
        $.ajax({
            url:`${baseUrl}notification.php?call=send`,
            type:'post',
            data: data,
            success:function(response) {
                var data = JSON.parse(response);
                if(data['success'] === false) {
                    $('#data-add-modal-error').text(data['message']);
                    return;
                }

                loadNotifications();
                $('#data-add-modal').modal('hide');
                $('#toast-title').text('Success');
                $('#toast-message').text(data['message']);
                $('#toast').toast('show');
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $('#data-add-modal-error').text(err.Message);
                console.log(err);
            }
        });
    }

    function restoreNotification(id) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}notification.php?call=restore`,
            type:'post',
            data: { id: id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadNotifications();
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

    function deleteQuery(id) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}notification.php?call=delete`,
            type:'post',
            data: { id: id },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadNotifications();
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
            url: `${baseUrl}notification.php?call=deletePermanent`,
            type:'post',
            data: { id: id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadNotifications();
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
        // all means send to all devices 
        modal.find('#data-add-item-device-id').val('all');
        modal.find('#data-add-item-type').val('default');
        modal.find('#data-add-item-title').val('');
        modal.find('#data-add-item-message').val('');
    });
</script>
<?php include('./footer.php'); ?>
