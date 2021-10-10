<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Course</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Course</li>
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
                    Add New Course
                    </button>
                </div>
            </div>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Code</th>
                        <th scope="col">Title</th>
                        <th scope="col">Credit</th>
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
                            <label for="data-add-item-code">Course Code</label>
                            <input type="text" class="form-control" id="data-add-item-code"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-title">Course Title</label>
                            <input type="text" class="form-control" id="data-add-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-credit-hr">Credit Hour</label>
                            <input type="text" class="form-control" id="data-add-item-credit-hr"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-faculty">Faculty</label>
                            <select class="form-select" id="data-add-item-faculty" aria-label="Select Faculty">
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
                            <label for="data-edit-item-code">Course Code</label>
                            <input type="text" class="form-control" id="data-edit-item-code"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-title">Course Title</label>
                            <input type="text" class="form-control" id="data-edit-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-credit-hr">Credit Hour</label>
                            <input type="text" class="form-control" id="data-edit-item-credit-hr"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-faculty">Faculty</label>
                            <select class="form-select" id="data-edit-item-faculty" aria-label="Select Faculty">
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

    function loadCourses(faculty_id) {
        $.ajax({
            url: `${baseUrl}course.php?call=getAll`,
            type:'get',
            data: {faculty_id: faculty_id},
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

    function generateTr(item) {
        var param = JSON.stringify(item);
        var deleted = item.deleted !== 0;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-json='${param}'><i class="far fa-edit"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreCourse(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteCourse(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.course_code}</td>` +
        `<td>${item.course_title}</td>` +
        `<td>${item.credit_hour}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${deleted? btnRestore : btnDelete}</td>` +
        `</tr>`;
    }

    function addData() {
        var code = $('#data-add-item-code').val();
        var title = $('#data-add-item-title').val();
        var creditHr = $('#data-add-item-credit-hr').val();
        var faculty_id = $('#data-add-item-faculty').val();
        var data = { 
            course_code: code, 
            course_title: title,
            credit_hour: creditHr,
            faculty_id: faculty_id
        }
        $.ajax({
            url: `${baseUrl}course.php?call=add`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadCourses(selectedFaculty);
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
        var id = $('#data-edit-item-id').val();
        var code = $('#data-edit-item-code').val();
        var title = $('#data-edit-item-title').val();
        var creditHr = $('#data-edit-item-credit-hr').val();
        var faculty_id = $('#data-edit-item-faculty').val();
        var data = { 
            id: id,
            course_code: code, 
            course_title: title,
            credit_hour: creditHr,
            faculty_id: faculty_id
        }
        $.ajax({
            url: `${baseUrl}course.php?call=update`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadCourses(selectedFaculty);
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

    function restoreCourse(course) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}course.php?call=restore`,
            type:'post',
            data: { id: course.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    course.deleted = 0;
                    $(`table#data-table tr#${course.id}`).replaceWith(generateTr(course));
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

    function deleteCourse(course) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}course.php?call=delete`,
            type:'post',
            data: { id: course.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    course.deleted = 1;
                    $(`table#data-table tr#${course.id}`).replaceWith(generateTr(course));
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
        modal.find('#data-add-item-code').val('');
        modal.find('#data-add-item-title').val('');
        modal.find('#data-add-item-credit-hr').val('');

        addFacultiesToDropdown(faculties, $('#data-add-item-faculty'));
        modal.find('#data-add-item-faculty').val(selectedFaculty).change();
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var course = button.data('json');
        addFacultiesToDropdown(faculties, $('#data-edit-item-faculty'));

        var modal = $(this);
        modal.find('#data-edit-item-error').html('');
        modal.find('#data-edit-item-id').val(course.id);
        modal.find('#data-edit-item-code').val(course.course_code);
        modal.find('#data-edit-item-title').val(course.course_title);
        modal.find('#data-edit-item-credit-hr').val(course.credit_hour);
        modal.find('#data-edit-item-faculty').val(course.faculty_id);
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
        loadCourses(selectedFaculty);
    });
</script>
<?php include('./footer.php'); ?>
