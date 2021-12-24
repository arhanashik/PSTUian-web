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
                            <label for="data-edit-item-device-id">Device</label>
                            <input type="text" class="form-control" id="data-edit-item-device-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-type">Type</label>
                            <select class="form-select" id="data-edit-item-type" aria-label="Select Type">
                                <option selected value="default">Default</option>
                                <option value="blood_donation">Blood Donation</option>
                                <option value="news">News</option>
                                <option value="help">Help</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-title">Title</label>
                            <input type="text" class="form-control" id="data-edit-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-message">Message</label>
                            <textarea type="text" rows="3" class="form-control" id="data-edit-item-message"></textarea>
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
            url:`${baseUrl}notification.php?call=getAll`,
            data: { page : page },
            type:'get',
            success:function(response) {
                $('#page-number').html(`Showing results for Page ${page}`);
                $('#data-table tbody').empty();
                var result = JSON.parse(response);
                if(result['code'] && result['code'] !== 200) {
                    $('#toast-title').text('Failed');
                    $('#toast-message').text(result['message']);
                    $('#toast').toast('show');
                    return;
                }
                data = result;
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
        `<td id="td-action-${item.id}">${btnEdit} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
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

                loadData(currentPage);
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

    function updateData() {
        var id = $('#data-edit-item-id').val();
        var device_id = $('#data-edit-item-device-id').val();
        var type = $('#data-edit-item-type').val();
        var title = $('#data-edit-item-title').val();
        var message = $('#data-edit-item-message').val();
        var data = { 
            id: id,
            device_id: device_id,
            type: type, 
            title: title,
            message: message,
        }
        $.ajax({
            url: `${baseUrl}notification.php?call=update`,
            type:'post',
            data: data,
            success:function(response){
                console.log(response);
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
            url: `${baseUrl}notification.php?call=deletePermanent`,
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
        // all means send to all devices 
        modal.find('#data-add-item-device-id').val('all');
        modal.find('#data-add-item-type').val('default');
        modal.find('#data-add-item-title').val('');
        modal.find('#data-add-item-message').val('');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getItem(id);

        var modal = $(this);
        modal.find('#data-edit-modal-error').html('');
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-device-id').val(item.device_id);
        modal.find('#data-edit-item-type').val(item.type).change();
        modal.find('#data-edit-item-title').val(item.title);
        modal.find('#data-edit-item-message').val(item.message);
    });

    function getItem(id) {
        return data.find(item => item.id == id);
    }
</script>
<?php include('./footer.php'); ?>
