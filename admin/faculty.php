<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Faculty</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Faculty</li>
        </ol>
        <!-- <div class="card mb-4">
            <div class="card-body">
                DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the
                <a target="_blank" href="https://datatables.net/">official DataTables documentation</a>
                .
            </div>
        </div> -->
        <div class="mb-4">
        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
        Add New Faculty
        </button>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Short Title</th>
                        <th scope="col">Title</th>
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
                            <label for="data-add-item-short-title">Short Title</label>
                            <input type="text" class="form-control" id="data-add-item-short-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-title">Title</label>
                            <input type="text" class="form-control" id="data-add-item-title"/>
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
                            <label for="data-edit-item-short-title">Short Title</label>
                            <input type="text" class="form-control" id="data-edit-item-short-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-title">Title</label>
                            <input type="text" class="form-control" id="data-edit-item-title"/>
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
        loadFaculties();
    });

    function loadFaculties() {
        $.ajax({
            url: `${baseUrl}faculty.php?call=getAll`,
            type:'get',
            success:function(response) {
                $('#data-table tbody').empty();
                var faculties = JSON.parse(response);
                if(faculties['code'] && faculties['code'] !== 200) {
                    $('#toast-title').text('Failed');
                    $('#toast-message').text(faculties['message']);
                    $('#toast').toast('show');
                    return;
                }
                for (i = 0; i < faculties.length; i++) {
                    var faculty = faculties[i];
                    $('#data-table > tbody:last-child').append(generateTr(faculty));
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function generateTr(faculty) {
        // console.log(JSON.stringify(faculty));
        var param = JSON.stringify(faculty);
        var deleted = faculty.deleted !== 0;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-id="${faculty.id}" data-short-title="${faculty.short_title}" data-title="${faculty.title}"><i class="far fa-edit"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreFaculty(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteFaculty(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        return `<tr id="${faculty.id}">` + 
        `<th scope="row">${faculty.id}</th>` +
        `<td>${faculty.short_title}</td>` +
        `<td>${faculty.title}</td>` +
        `<td>${faculty.created_at}</td>` +
        `<td>${faculty.updated_at}</td>` +
        `<td id="td-action-${faculty.id}">${btnEdit} ${deleted? btnRestore : btnDelete}</td>` +
        `</tr>`;
    }

    function addData() {
        var shortTitle = $('#data-add-item-short-title').val();
        var title = $('#data-add-item-title').val();
        $.ajax({
            url: `${baseUrl}faculty.php?call=add`,
            type:'post',
            data: { short_title: shortTitle, title: title },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadFaculties();
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
        var shortTitle = $('#data-edit-item-short-title').val();
        var title = $('#data-edit-item-title').val();
        $.ajax({
            url: `${baseUrl}faculty.php?call=update`,
            type:'post',
            data: { id: id, short_title: shortTitle, title: title },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadFaculties();
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

    function restoreFaculty(faculty) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}faculty.php?call=restore`,
            type:'post',
            data: { id: faculty.id},
            success:function(response){
                console.log(response);
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    // $(`table#data-table tr#${id}`).remove();
                    // $(`#td-deleted-${id}`).html('true');
                    faculty.deleted = 0;
                    $(`table#data-table tr#${faculty.id}`).replaceWith(generateTr(faculty));
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

    function deleteFaculty(faculty) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}faculty.php?call=delete`,
            type:'post',
            data: { id: faculty.id},
            success:function(response){
                console.log(response);
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    // $(`table#data-table tr#${id}`).remove();
                    // $(`#td-deleted-${id}`).html('true');
                    faculty.deleted = 1;
                    $(`table#data-table tr#${faculty.id}`).replaceWith(generateTr(faculty));
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
        modal.find('#data-add-item-short-title').val('');
        modal.find('#data-add-item-title').val('');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var shortTitle = button.data('short-title');
        var title = button.data('title');

        var modal = $(this);
        modal.find('#data-edit-item-id').val(id);
        modal.find('#data-edit-item-short-title').val(shortTitle);
        modal.find('#data-edit-item-title').val(title);
    });
</script>
<?php include('./footer.php'); ?>
