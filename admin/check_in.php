<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Check In</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Check In</li>
        </ol>
        <div class="mb-4">
        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
        Add New
        </button>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Location</th>
                        <th scope="col">User</th>
                        <th scope="col">Type</th>
                        <th scope="col">Count</th>
                        <th scope="col">Privacy</th>
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
                            <label for="data-add-item-location-id">Location Id</label>
                            <input type="text" class="form-control" id="data-add-item-location-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-user-id">User Id</label>
                            <input type="text" class="form-control" id="data-add-item-user-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-user-type">User Type</label>
                            <select class="form-select" id="data-add-item-user-type" aria-label="User type">
                                <option selected value="student">student</option>
                                <option value="teacher">teacher</option>
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
                        <p class="text-danger" id="data-edit-modal-error"></p>
                        <input type="text" class="form-control"  id="data-edit-item-id" hidden/>
                        <div class="form-group">
                            <label for="data-edit-item-location-id">Location Id</label>
                            <input type="text" class="form-control" id="data-edit-item-location-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-user-id">User Id</label>
                            <input type="text" class="form-control" id="data-edit-item-user-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-user-type">User Type</label>
                            <select class="form-select" id="data-edit-item-user-type" aria-label="User type">
                                <option selected value="student">student</option>
                                <option value="teacher">teacher</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-count">Check In Count</label>
                            <input type="number" class="form-control" id="data-edit-item-count"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-name">Privacy</label>
                            <select class="form-select" id="data-edit-item-privacy" aria-label="Privacy">
                                <option selected value="public">public</option>
                                <option value="only_me">only_me</option>
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

    <!-- Data Details Modal -->
    <div class="modal fade" id="data-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="data-item-location-id">Location Id</label>
                    <p type="text" class="form-control" id="data-item-location-id"></p>
                    <label for="data-item-user-id">User Id</label>
                    <p type="text" class="form-control" id="data-item-user-id"></p>
                    <label for="data-item-user-type">User Type</label>
                    <p type="text" class="form-control" id="data-item-user-type"></p>
                    <label for="data-item-count">Check In Count</label>
                    <p type="text" class="form-control" id="data-item-count"></p>
                    <label for="data-item-privacy">Privacy</label>
                    <p type="text" class="form-control" id="data-item-privacy"></p>
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
            url: `${baseUrl}check_in.php?call=getAll`,
            data: { page : page, limit : 10},
            type:'get',
            success:function(response) {
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
        var btnUnConfirm = `<button class="btn btn-success" onclick='unconfirmData(` + param + `)'><i class="fas fa-check-circle"></i></button>`;
        var btnConfirm = `<button class="btn btn-secondary" onclick='confirmData(` + param + `)'><i class="far fa-check-circle"></i></button>`;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-id='${param}'><i class="far fa-edit"></i></button>`;
        var btnDetails = `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#data-details-modal" data-id='${param}'><i class="far fa-file-alt"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreData(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteData(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.location_id}</td>` +
        `<td>${item.user_id}</td>` +
        `<td>${item.user_type}</td>` +
        `<td>${item.count}</td>` +
        `<td>${item.privacy}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${btnDetails} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function addData() {
        let location_id = $('#data-add-item-location-id').val();
        let user_id = $('#data-add-item-user-id').val();
        let user_type = $('#data-add-item-user-type').val();
        let data = { 
            location_id: location_id,
            user_id: user_id,
            user_type: user_type,
        }
        $.ajax({
            url: `${baseUrl}check_in.php?call=add`,
            type:'post',
            data: data,
            success:function(response){
                console.log(response);
                let data = JSON.parse(response);
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
        let id = $('#data-edit-item-id').val();
        let location_id = $('#data-edit-item-location-id').val();
        let user_id = $('#data-edit-item-user-id').val();
        let user_type = $('#data-edit-item-user-type').val();
        let count = $('#data-edit-item-count').val();
        let privacy = $('#data-edit-item-privacy').val();
        var data = { 
            id: id,
            location_id: location_id,
            user_id: user_id,
            user_type: user_type,
            count: count,
            privacy: privacy,
        }
        $.ajax({
            url: `${baseUrl}check_in.php?call=update`,
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

    function confirmData(id) {
        if(!confirm("Are you sure you want to confirm this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}check_in.php?call=confirm`,
            type:'post',
            data: { id: id},
            success:function(response){
                console.log(response);
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

    function unconfirmData(id) {
        if(!confirm("Are you sure you want to unconfirm this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}check_in.php?call=unconfirm`,
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

    function restoreData(id) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}check_in.php?call=restore`,
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
            url: `${baseUrl}check_in.php?call=delete`,
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
            url: `${baseUrl}check_in.php?call=deletePermanent`,
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
        modal.find('#data-add-item-location-id').val('');
        modal.find('#data-add-item-user-id').val('');
        modal.find('#data-add-item-user-type').val('student');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getItem(id);

        var modal = $(this);
        modal.find('#data-edit-modal-error').html('');
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-location-id').val(item.location_id);
        modal.find('#data-edit-item-user-id').val(item.user_id);
        modal.find('#data-edit-item-user-type').val(item.user_type);
        modal.find('#data-edit-item-count').val(item.count);
        modal.find('#data-edit-item-privacy').val(item.privacy);
    });

    $('#data-details-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getItem(id);

        var modal = $(this);
        modal.find('#data-item-location-id').text(item.location_id);
        modal.find('#data-item-user-id').text(item.user_id);
        modal.find('#data-item-user-type').text(item.user_type);
        modal.find('#data-item-count').text(item.count);
        modal.find('#data-item-privacy').text(item.privacy);
        modal.find('#data-item-created').text(item.created_at);
        modal.find('#data-item-updated').text(item.updated_at);
    });

    function getItem(id) {
        return data.find(item => item.id == id);
    }
</script>
<?php include('./footer.php'); ?>
