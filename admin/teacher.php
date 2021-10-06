<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Teacher</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Teacher</li>
        </ol>
        <div class="mb-4">
        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
        Add New Teacher
        </button>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Designation</th>
                        <th scope="col">Faculty</th>
                        <!-- <th scope="col">Department</th> -->
                        <!-- <th scope="col">Address</th> -->
                        <!-- <th scope="col">Phone</th> -->
                        <!-- <th scope="col">Email</th> -->
                        <!-- <th scope="col">ImageUrl</th> -->
                        <!-- <th scope="col">LinkedIn</th> -->
                        <!-- <th scope="col">Facebook</th> -->
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
                            <label for="data-add-item-name">Name</label>
                            <input type="text" class="form-control" id="data-add-item-name"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-designation">Designation</label>
                            <input type="text" class="form-control" id="data-add-item-designation"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-faculty">Faculty</label>
                            <select class="form-select" id="data-add-item-faculty" aria-label="Select Faculty">
                                <option selected>--Select--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-department">Department</label>
                            <input type="text" class="form-control" id="data-add-item-department"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-address">Address</label>
                            <input type="text" class="form-control" id="data-add-item-address"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-phone">Phone</label>
                            <input type="text" class="form-control" id="data-add-item-phone"/>
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
                        <p id="data-edit-modal-error"></p>
                        <input type="text" class="form-control"  id="data-edit-item-id" hidden/>
                        <div class="form-group">
                            <label for="data-edit-item-name">Name</label>
                            <input type="text" class="form-control" id="data-edit-item-name"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-designation">Designation</label>
                            <input type="text" class="form-control" id="data-edit-item-designation"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-faculty">Faculty</label>
                            <select class="form-select" id="data-edit-item-faculty" aria-label="Select Faculty">
                                <option selected>--Select--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-department">Department</label>
                            <input type="text" class="form-control" id="data-edit-item-department"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-address">Address</label>
                            <input type="text" class="form-control" id="data-edit-item-address"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-phone">Phone</label>
                            <input type="text" class="form-control" id="data-edit-item-phone"/>
                        </div>
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
                    <label for="data-item-name">Name</label>
                    <p type="text" class="form-control" id="data-item-name"></p>
                    <label for="data-item-designation">Designation</label>
                    <p type="text" class="form-control" id="data-item-designation"></p>
                    <label for="data-item-faculty">Faculty</label>
                    <p type="text" class="form-control" id="data-item-faculty"></p>
                    <label for="data-item-department">Department</label>
                    <p type="text" class="form-control" id="data-item-department"></p>
                    <label for="data-item-address">Address</label>
                    <p type="text" class="form-control" id="data-item-address"></p>
                    <label for="data-item-phone">Phone</label>
                    <p type="text" class="form-control" id="data-item-phone"></p>
                    <label for="data-item-email">Email</label>
                    <p type="text" class="form-control" id="data-item-email"></p>
                    <label for="data-item-image-url">Image url</label>
                    <p type="text" class="form-control" id="data-item-image-url"></p>
                    <label for="data-item-linkedin">LinkedIn</label>
                    <p type="text" class="form-control" id="data-item-linkedin"></p>
                    <label for="data-item-facebook">Facebook</label>
                    <p type="text" class="form-control" id="data-item-facebook"></p>
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
    $(document).ready(function() {
        loadFaculties();
        loadTeachers();
    });

    function loadTeachers() {
        $.ajax({
            url:'/PSTUian-web/admin/api/teacher.php?call=getAll',
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

    function loadFaculties() {
        $.ajax({
            url:'/PSTUian-web/admin/api/faculty.php?call=getAll',
            type:'get',
            success:function(response){
                faculties = JSON.parse(response);
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
        var btnDetails = `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#data-details-modal" data-json='${param}'><i class="far fa-file-alt"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreTeacher(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteTeacher(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.name}</td>` +
        `<td>${item.designation}</td>` +
        `<td>${item.short_title}</td>` +
        // `<td>${item.department}</td>` +
        // `<td>${item.address}</td>` +
        // `<td>${item.phone}</td>` +
        // `<td>${item.email}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${btnDetails} ${deleted? btnRestore : btnDelete}</td>` +
        `</tr>`;
    }

    function addData() {
        var name = $('#data-add-item-name').val();
        var designation = $('#data-add-item-designation').val();
        var faculty_id = $('#data-add-item-faculty').val();
        var department = $('#data-add-item-department').val();
        var address = $('#data-add-item-address').val();
        var phone = $('#data-add-item-phone').val();
        var email = $('#data-add-item-email').val();
        var data = { 
            name: name, 
            designation: designation, 
            faculty_id: faculty_id, 
            department: department, 
            address: address,
            phone: phone,
            email: email
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/teacher.php?call=add',
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadTeachers();
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
        var designation = $('#data-edit-item-designation').val();
        var faculty_id = $('#data-edit-item-faculty').val();
        var department = $('#data-edit-item-department').val();
        var address = $('#data-edit-item-address').val();
        var phone = $('#data-edit-item-phone').val();
        var email = $('#data-edit-item-email').val();
        var data = { 
            id: id,
            name: name, 
            designation: designation, 
            faculty_id: faculty_id, 
            department: department, 
            address: address,
            phone: phone,
            email: email
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/teacher.php?call=update',
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadTeachers();
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

    function restoreTeacher(teacher) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/teacher.php?call=restore',
            type:'post',
            data: { id: teacher.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    teacher.deleted = 0;
                    $(`table#data-table tr#${teacher.id}`).replaceWith(generateTr(teacher));
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

    function deleteTeacher(teacher) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/teacher.php?call=delete',
            type:'post',
            data: { id: teacher.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    teacher.deleted = 1;
                    $(`table#data-table tr#${teacher.id}`).replaceWith(generateTr(teacher));
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
        modal.find('#data-add-item-name').val('');
        modal.find('#data-add-item-designation').val('');
        modal.find('#data-add-item-department').val('');
        modal.find('#data-add-item-address').val('');
        modal.find('#data-add-item-phone').val('');
        modal.find('#data-add-item-email').val('');

        addFacultiesToDropdown(faculties, $('#data-add-item-faculty'));
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var teacher = button.data('json');
        addFacultiesToDropdown(faculties, $('#data-edit-item-faculty'));

        var modal = $(this);
        modal.find('#data-edit-item-id').val(teacher.id);
        modal.find('#data-edit-item-name').val(teacher.name);
        modal.find('#data-edit-item-designation').val(teacher.designation);
        // use .change() to trigger change event
        // modal.find('#data-edit-item-faculty').val(teacher.faculty_id).change();
        modal.find('#data-edit-item-faculty').val(teacher.faculty_id)
        modal.find('#data-edit-item-department').val(teacher.department);
        modal.find('#data-edit-item-address').val(teacher.address);
        modal.find('#data-edit-item-phone').val(teacher.phone);
        modal.find('#data-edit-item-email').val(teacher.email);
    });

    $('#data-details-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var teacher = button.data('json');

        var modal = $(this);
        modal.find('#data-item-name').text(teacher.name);
        modal.find('#data-item-designation').text(teacher.designation);
        modal.find('#data-item-faculty').text(teacher.short_title)
        modal.find('#data-item-department').text(teacher.department);
        modal.find('#data-item-address').text(teacher.address);
        modal.find('#data-item-phone').text(teacher.phone);
        modal.find('#data-item-email').text(teacher.email);
        modal.find('#data-item-image-url').text(teacher.image_url);
        modal.find('#data-item-linkedin').text(teacher.linked_in);
        modal.find('#data-item-facebook').text(teacher.fb_link);
    });

    function addFacultiesToDropdown(faculties, dropdown) {
        dropdown.empty();
        dropdown.append('<option selected value="-1">--Select--</option>');
        for (i = 0; i < faculties.length; i++) {
            var faculty = faculties[i];
            var item = `<option value="${faculty.id}">${faculty.short_title}</option>`;
            dropdown.append(item);
        }
    }

    // function validate(str) {
    //     return (typeof str == 'undefined' || str !== null && !str.length)? str : '~';
    // }
</script>
<?php include('./footer.php'); ?>
