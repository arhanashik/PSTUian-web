<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Batch</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Batch</li>
        </ol>
        <div class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="faculties">Faculty</label>
                    <select class="form-select float-start" id="faculties" aria-label="Select Faculty">
                    </select>
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
                    Add New Batch
                    </button>
                </div>
            </div>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Title</th>
                        <th scope="col">Session</th>
                        <th scope="col">Students</th>
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
                        <p class="text-danger" id="data-add-modal-error"></p>
                        <div class="form-group">
                            <label for="data-add-item-name">Name</label>
                            <input type="text" class="form-control" id="data-add-item-name"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-title">Title</label>
                            <input type="text" class="form-control" id="data-add-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-session">Session</label>
                            <input type="text" class="form-control" id="data-add-item-session"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-faculty">Faculty</label>
                            <select class="form-select" id="data-add-item-faculty" aria-label="Select Faculty">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-students">Total Students</label>
                            <input type="number" class="form-control" id="data-add-item-students"/>
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
                            <label for="data-edit-item-name">Name</label>
                            <input type="text" class="form-control" id="data-edit-item-name"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-title">Title</label>
                            <input type="text" class="form-control" id="data-edit-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-session">Session</label>
                            <input type="text" class="form-control" id="data-edit-item-session"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-faculty">Faculty</label>
                            <select class="form-select" id="data-edit-item-faculty" aria-label="Select Faculty">
                                <option selected>--Select--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-students">Total Students</label>
                            <input type="number" class="form-control" id="data-edit-item-students"/>
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
    var faculties = [];
    var selectedFaculty;
    $(document).ready(function() {
        loadFaculties();
    });

    function loadFaculties() {
        $.ajax({
            url: `${baseUrl}faculty.php?call=getAll`,
            type:'get',
            success:function(response){
                faculties = JSON.parse(response);
                addFacultiesToDropdown(faculties, $('#faculties'));
                if(faculties && faculties.length > 0) {
                    $('#faculties').val(faculties[0].id).change();
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function loadBatches(faculty_id) {
        $.ajax({
            url: `${baseUrl}batch.php?call=getAll`,
            type:'get',
            data: {faculty_id: faculty_id},
            success:function(response){
                console.log(response);
                $('#data-table tbody').empty();
                var batches = JSON.parse(response);
                for (i = 0; i < batches.length; i++) {
                    $('#data-table > tbody:last-child').append(generateTr(batches[i]));
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function generateTr(batch) {
        // console.log(JSON.stringify(faculty));
        var param = JSON.stringify(batch);
        var deleted = batch.deleted !== 0;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-json='${param}'><i class="far fa-edit"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreBatch(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteBatch(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${batch.id}">` + 
        `<th scope="row">${batch.id}</th>` +
        `<td>${batch.name}</td>` +
        `<td>${batch.title}</td>` +
        `<td>${batch.session}</td>` +
        `<td>${batch.total_student}</td>` +
        `<td>${batch.created_at}</td>` +
        `<td>${batch.updated_at}</td>` +
        `<td id="td-action-${batch.id}">${btnEdit} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function addData() {
        var name = $('#data-add-item-name').val();
        var title = $('#data-add-item-title').val();
        var session = $('#data-add-item-session').val();
        var faculty_id = $('#data-add-item-faculty').val();
        var total_student = $('#data-add-item-students').val();
        $.ajax({
            url: `${baseUrl}batch.php?call=add`,
            type:'post',
            data: { name: name, title: title, session: session, faculty_id: faculty_id, total_student: total_student },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadBatches(selectedFaculty);
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
        var name = $('#data-edit-item-name').val();
        var title = $('#data-edit-item-title').val();
        var session = $('#data-edit-item-session').val();
        var faculty_id = $('#data-edit-item-faculty').val();
        var total_student = $('#data-edit-item-students').val();
        $.ajax({
            url: `${baseUrl}batch.php?call=update`,
            type:'post',
            data: { id: id, name: name, title: title, session: session, faculty_id: faculty_id, total_student: total_student },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadBatches(selectedFaculty);
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

    function restoreBatch(batch) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}batch.php?call=restore`,
            type:'post',
            data: { id: batch.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    batch.deleted = 0;
                    $(`table#data-table tr#${batch.id}`).replaceWith(generateTr(batch));
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

    function deleteBatch(batch) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}batch.php?call=delete`,
            type:'post',
            data: { id: batch.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    batch.deleted = 1;
                    $(`table#data-table tr#${batch.id}`).replaceWith(generateTr(batch));
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
            url: `${baseUrl}batch.php?call=deletePermanent`,
            type:'post',
            data: { id: item.id},
            success:function(response){
                console.log(response);
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadBatches(selectedFaculty);
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
        modal.find('#data-add-item-error').html('');
        modal.find('#data-add-item-name').val('');
        modal.find('#data-add-item-title').val('');
        modal.find('#data-add-item-session').val('');
        modal.find('#data-add-item-students').val('');

        addFacultiesToDropdown(faculties, $('#data-add-item-faculty'));
        modal.find('#data-add-item-faculty').val(selectedFaculty).change();
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var batch = button.data('json');
        addFacultiesToDropdown(faculties, $('#data-edit-item-faculty'));

        var modal = $(this);
        modal.find('#data-edit-item-error').html('');
        modal.find('#data-edit-item-id').val(batch.id);
        modal.find('#data-edit-item-name').val(batch.name);
        modal.find('#data-edit-item-title').val(batch.title);
        modal.find('#data-edit-item-session').val(batch.session);
        modal.find('#data-edit-item-faculty').val(batch.faculty_id);
        // use .change() to trigger change event
        // modal.find('#data-edit-item-faculty').val(batch.faculty_id).change();
        modal.find('#data-edit-item-students').val(batch.total_student);
    });

    function addFacultiesToDropdown(faculties, dropdown) {
        dropdown.empty();
        for (i = 0; i < faculties.length; i++) {
            var faculty = faculties[i];
            var item = `<option value="${faculty.id}">${faculty.short_title}</option>`;
            dropdown.append(item);
        }
    }

    $('#faculties').change(function() {
        selectedFaculty = $(this).val();
        loadBatches(selectedFaculty);
    });
</script>
<?php include('./footer.php'); ?>
