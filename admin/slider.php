<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Slider</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Slider</li>
        </ol>
        <div class="mb-4">
        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
        Add New Slider
        </button>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Title</th>
                        <th scope="col">Image Url</th>
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
                            <label for="data-add-item-title">Title</label>
                            <input type="text" class="form-control" id="data-add-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-image">Image Url</label>
                            <input type="text" class="form-control" id="data-add-item-image"/>
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
                            <label for="data-edit-item-title">Course Title</label>
                            <input type="text" class="form-control" id="data-edit-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-image">Image Url</label>
                            <input type="text" class="form-control" id="data-edit-item-image"/>
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
        loadSliders();
    });

    function loadSliders() {
        $.ajax({
            url:'/PSTUian-web/admin/api/slider.php?call=getAll',
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

    function generateTr(item) {
        var param = JSON.stringify(item);
        var deleted = item.deleted !== 0;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-json='${param}'><i class="far fa-edit"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreSlider(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteSlider(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.title}</td>` +
        `<td>${item.image_url}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${deleted? btnRestore : btnDelete}</td>` +
        `</tr>`;
    }

    function addData() {
        var title = $('#data-add-item-title').val();
        var image = $('#data-add-item-image').val();
        var data = { 
            title: title, 
            image_url: image
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/slider.php?call=add',
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadSliders();
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
        var title = $('#data-edit-item-title').val();
        var image = $('#data-edit-item-image').val();
        var data = { 
            id: id,
            title: title, 
            image_url: image
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/slider.php?call=update',
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadSliders();
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

    function restoreSlider(slider) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/slider.php?call=restore',
            type:'post',
            data: { id: slider.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    slider.deleted = 0;
                    $(`table#data-table tr#${slider.id}`).replaceWith(generateTr(slider));
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

    function deleteSlider(slider) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url:'/PSTUian-web/admin/api/slider.php?call=delete',
            type:'post',
            data: { id: slider.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    slider.deleted = 1;
                    $(`table#data-table tr#${slider.id}`).replaceWith(generateTr(slider));
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
        modal.find('#data-add-item-title').val('');
        modal.find('#data-add-item-image').val('');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var item = button.data('json');

        var modal = $(this);
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-title').val(item.title);
        modal.find('#data-edit-item-image').val(item.image_url);
    });
</script>
<?php include('./footer.php'); ?>
