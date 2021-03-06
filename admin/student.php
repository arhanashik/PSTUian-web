<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Student</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Student</li>
        </ol>
        <div class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="faculties">Faculty</label>
                    <select class="form-select float-start" id="faculties" aria-label="Select Faculty">
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="batches">Batch</label>
                    <select class="form-select float-start" id="batches" aria-label="Select Batch">
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
                    Add New Student
                    </button>
                </div>
            </div>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Reg</th>
                        <th scope="col">Session</th>
                        <th scope="col">Email</th>
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
                            <label for="data-add-item-id">ID</label>
                            <input type="text" class="form-control" id="data-add-item-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-name">Name</label>
                            <input type="text" class="form-control" id="data-add-item-name"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-reg">Registration No</label>
                            <input type="text" class="form-control" id="data-add-item-reg"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-faculty">Faculty</label>
                            <select class="form-select" id="data-add-item-faculty" aria-label="Select Faculty" disabled>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-batch">Batch</label>
                            <select class="form-select" id="data-add-item-batch" aria-label="Select Batch">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-session">Session</label>
                            <input type="text" class="form-control" id="data-add-item-session"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-email">Email</label>
                            <input type="text" class="form-control" id="data-add-item-email"/>
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
                        <input type="text" class="form-control" id="data-edit-item-old-id" hidden/>
                        <div class="form-group">
                            <label for="data-edit-item-id">ID</label>
                            <input type="text" class="form-control" id="data-edit-item-id"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-name">Name</label>
                            <input type="text" class="form-control" id="data-edit-item-name"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-reg">Registration No</label>
                            <input type="text" class="form-control" id="data-edit-item-reg"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-faculty">Faculty</label>
                            <select class="form-select" id="data-edit-item-faculty" aria-label="Select Faculty" disabled>
                                <option selected>--Select--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-batch">Batch</label>
                            <select class="form-select" id="data-edit-item-batch" aria-label="Select Batch">
                                <option selected>--Select--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-session">Session</label>
                            <input type="text" class="form-control" id="data-edit-item-session"/>
                        </div>
                        <input type="text" class="form-control" id="data-edit-item-old-email" hidden/>
                        <div class="form-group">
                            <label for="data-edit-item-email">Email</label>
                            <input type="text" class="form-control" id="data-edit-item-email"/>
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
                    <label for="data-item-name">Name</label>
                    <p type="text" class="form-control" id="data-item-name"></p>
                    <label for="data-item-reg">Registration No.</label>
                    <p type="text" class="form-control" id="data-item-reg"></p>
                    <label for="data-item-faculty">Faculty</label>
                    <p type="text" class="form-control" id="data-item-faculty"></p>
                    <label for="data-item-batch">Batch</label>
                    <p type="text" class="form-control" id="data-item-batch"></p>
                    <label for="data-item-session">Session</label>
                    <p type="text" class="form-control" id="data-item-session"></p>
                    <label for="data-item-blood">Blood group</label>
                    <p type="text" class="form-control" id="data-item-blood"></p>
                    <label for="data-item-phone">Phone</label>
                    <p type="text" class="form-control" id="data-item-phone"></p>
                    <label for="data-item-email">Email</label>
                    <p type="text" class="form-control" id="data-item-email"></p>
                    <label for="data-item-address">Address</label>
                    <p type="text" class="form-control" id="data-item-address"></p>
                    <label for="data-item-image-url">Image url</label>
                    <p type="text" class="form-control" id="data-item-image-url"></p>
                    <label for="data-item-cv">CV</label>
                    <p type="text" class="form-control" id="data-item-cv"></p>
                    <label for="data-item-linkedin">LinkedIn</label>
                    <p type="text" class="form-control" id="data-item-linkedin"></p>
                    <label for="data-item-facebook">Facebook</label>
                    <p type="text" class="form-control" id="data-item-facebook"></p>
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
    var faculties = [];
    var batches = [];
    var students = [];
    var selectedFaculty;
    var selectedBatch;
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
                $('#data-table tbody').empty();
                batches = JSON.parse(response);
                addBatchesToDropdown(batches, $('#batches'));
                if(batches && batches.length > 0) {
                    $('#batches').val(batches[0].id).change();
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function loadStudents(faculty_id, batch_id) {
        $.ajax({
            url: `${baseUrl}student.php?call=getAll`,
            type:'get',
            data: { faculty_id: faculty_id, batch_id: batch_id },
            success:function(response) {
                $('#data-table tbody').empty();
                students = JSON.parse(response);
                for (i = 0; i < students.length; i++) {
                    $('#data-table > tbody:last-child').append(generateTr(students[i]));
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
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreStudent(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteStudent(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.name}</td>` +
        `<td>${item.reg}</td>` +
        `<td>${item.session}</td>` +
        `<td>${item.email}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${btnDetails} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function addData() {
        var id = $('#data-add-item-id').val();
        var name = $('#data-add-item-name').val();
        var reg = $('#data-add-item-reg').val();
        var faculty_id = $('#data-add-item-faculty').val();
        var batch_id = $('#data-add-item-batch').val();
        var session = $('#data-add-item-session').val();
        var email = $('#data-add-item-email').val();
        var data = { 
            id: id,
            name: name, 
            reg: reg, 
            faculty_id: faculty_id, 
            batch_id: batch_id, 
            session: session,
            email: email,
        }
        $.ajax({
            url: `${baseUrl}student.php?call=add`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadStudents(selectedFaculty, selectedBatch);
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
        var old_id = $('#data-edit-item-old-id').val();
        var id = $('#data-edit-item-id').val();
        var name = $('#data-edit-item-name').val();
        var reg = $('#data-edit-item-reg').val();
        var faculty_id = $('#data-edit-item-faculty').val();
        var batch_id = $('#data-edit-item-batch').val();
        var session = $('#data-edit-item-session').val();
        var old_email = $('#data-edit-item-old-email').val();
        var email = $('#data-edit-item-email').val();
        var data = { 
            old_id: old_id,
            id: id,
            name: name, 
            reg: reg, 
            faculty_id: faculty_id, 
            batch_id: batch_id, 
            session: session,
            old_email: old_email,
            email: email,
        }
        $.ajax({
            url: `${baseUrl}student.php?call=update`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadStudents(selectedFaculty, selectedBatch);
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

    function restoreStudent(id) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}student.php?call=restore`,
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

    function deleteStudent(id) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}student.php?call=delete`,
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

    function deletePermanent(id) {
        if(!confirm("Are you sure you want to delete this PERMANENTLY? It cannot be restored again.")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}student.php?call=deletePermanent`,
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

    $('#data-add-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        var modal = $(this);
        modal.find('#data-add-modal-error').html('');
        modal.find('#data-add-item-id').val('');
        modal.find('#data-add-item-name').val('');
        modal.find('#data-add-item-reg').val('');
        modal.find('#data-add-item-session').val('');
        modal.find('#data-add-item-email').val('');

        addFacultiesToDropdown(faculties, $('#data-add-item-faculty'));
        addBatchesToDropdown(batches, $('#data-add-item-batch'));
        modal.find('#data-add-item-faculty').val(selectedFaculty).change();
        modal.find('#data-add-item-batch').val(selectedBatch).change();
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getStudent(id);
        addFacultiesToDropdown(faculties, $('#data-edit-item-faculty'));
        addBatchesToDropdown(batches, $('#data-edit-item-batch'));

        var modal = $(this);
        modal.find('#data-edit-modal-error').html('');
        modal.find('#data-edit-item-old-id').val(item.id);
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-name').val(item.name);
        modal.find('#data-edit-item-reg').val(item.reg);
        modal.find('#data-edit-item-faculty').val(item.faculty_id).change();
        modal.find('#data-edit-item-batch').val(item.batch_id).change();
        modal.find('#data-edit-item-batch').val(item.batch_id);
        modal.find('#data-edit-item-session').val(item.session);
        modal.find('#data-edit-item-old-email').val(item.email);
        modal.find('#data-edit-item-email').val(item.email);
    });

    $('#data-details-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var item = getStudent(id);

        var modal = $(this);
        modal.find('#data-item-id').text(item.id);
        modal.find('#data-item-name').text(item.name);
        modal.find('#data-item-reg').text(item.reg);
        modal.find('#data-item-faculty').text(item.faculty);
        modal.find('#data-item-batch').text(item.batch);
        modal.find('#data-item-session').text(item.session);
        modal.find('#data-item-blood').text(item.blood);
        modal.find('#data-item-phone').text(item.phone);
        modal.find('#data-item-email').text(item.email);
        modal.find('#data-item-address').text(item.address);
        modal.find('#data-item-image-url').text(item.image_url);
        modal.find('#data-item-cv').text(item.cv_link);
        modal.find('#data-item-linkedin').text(item.linked_in);
        modal.find('#data-item-facebook').text(item.fb_link);
        modal.find('#data-item-created').text(item.created_at);
        modal.find('#data-item-updated').text(item.updated_at);
    });

    function addFacultiesToDropdown(faculties, dropdown) {
        dropdown.empty();
        for (i = 0; i < faculties.length; i++) {
            var faculty = faculties[i];
            var item = `<option value="${faculty.id}">${faculty.short_title}</option>`;
            dropdown.append(item);
        }
    }

    function addBatchesToDropdown(batches, dropdown) {
        dropdown.empty();
        for (i = 0; i < batches.length; i++) {
            var batch = batches[i];
            var item = `<option value="${batch.id}">${batch.name}</option>`;
            dropdown.append(item);
        }
    }

    $('#faculties').change(function() {
        selectedFaculty = $(this).val();
        loadBatches(selectedFaculty);
    });

    $('#batches').change(function() {
        selectedBatch = $(this).val();
        loadStudents(selectedFaculty, selectedBatch);
    });

    function getStudent(id) {
        return students.find(student => student.id == id);
    }
</script>
<?php include('./footer.php'); ?>
