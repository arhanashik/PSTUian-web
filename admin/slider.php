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
    <div class="modal fade" id="data-add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                            <label for="data-add-item-title">Title</label>
                            <input type="text" class="form-control" id="data-add-item-title"/>
                        </div>
                        <!-- <div class="input-group mb-3">
                            <label for="data-add-item-image">Image Url</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="data-add-item-image">
                                <label class="custom-file-label" for="data-add-item-image">Choose file</label>
                            </div>
                        </div> -->
                        <label for="data-add-item-image">Image</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="data-add-item-image" accept=".jpg,.jpeg,.png">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="data-add-btn" onclick="addData()">
                    Add
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
                        <p id="data-edit-modal-error"></p>
                        <input type="text" class="form-control"  id="data-edit-item-id" hidden/>
                        <div class="form-group">
                            <label for="data-edit-item-title">Course Title</label>
                            <input type="text" class="form-control" id="data-edit-item-title"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-image">Image Url</label>
                            <input type="text" class="form-control" id="data-edit-item-image" disabled/>
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
            url: `${baseUrl}slider.php?call=getAll`,
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
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.title}</td>` +
        `<td>${item.image_url}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function addData() {
        var loadingStateBtn = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
        $('#data-add-btn').html(loadingStateBtn);
        var title = $('#data-add-item-title').val();
        var image = $('#data-add-item-image')[0].files[0];
        var formData = new FormData();
        formData.append('title', title);
        formData.append('image', image);
        $.ajax({
            url: `${baseUrl}slider.php?call=add`,
            type:'post',
            data: formData,
            // tell jQuery not to process the data
            processData: false,
            // tell jQuery not to set contentType
            contentType: false,  
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadSliders();
                    $('#data-add-btn').html('Done');
                    $('#data-add-modal').modal('hide');
                } else {
                    $('#data-add-btn').html('Add');
                    $('#data-add-modal-error').text(data['message']);
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $('#data-add-btn').html('Add');
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
            url: `${baseUrl}slider.php?call=update`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadSliders();
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

    function restoreSlider(slider) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}slider.php?call=restore`,
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
            url: `${baseUrl}slider.php?call=delete`,
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

    function deletePermanent(item) {
        if(!confirm("Are you sure you want to delete this PERMANENTLY? It cannot be restored again.")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}slider.php?call=deletePermanent`,
            type:'post',
            data: { id: item.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadSliders();
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
        modal.find('#data-add-item-title').val('');
        modal.find('#data-add-item-image').val('');
        modal.find('#data-add-btn').html('Add');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var item = button.data('json');

        var modal = $(this);
        modal.find('#data-edit-item-id').val(item.id);
        modal.find('#data-edit-item-title').val(item.title);
        modal.find('#data-edit-item-image').val(item.image_url);
    });

    //restrict file size
    const maxAllowedSize = 1 * 1024 * 1024;
    $('#data-add-item-image').change( function() {
        if(this.files[0].size > maxAllowedSize){
            alert("File is too big!");
            this.value = "";
        };
    });
</script>
<?php include('./footer.php'); ?>
