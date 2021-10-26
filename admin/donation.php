<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Donation</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Donation</li>
        </ol>
        <div class="mb-4">
        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#data-add-modal">
        Add New Donation
        </button>
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Reference</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col">Confirmation</th>
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
                            <label for="data-add-item-info">Info</label>
                            <input type="textarea" class="form-control" id="data-add-item-info"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-email">Email</label>
                            <input type="text" class="form-control" id="data-add-item-email"/>
                        </div>
                        <div class="form-group">
                            <label for="data-add-item-reference">Referance</label>
                            <input type="text" class="form-control" id="data-add-item-reference"/>
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
                            <label for="data-edit-item-info">Info</label>
                            <input type="textarea" class="form-control" id="data-edit-item-info"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-email">Email</label>
                            <input type="text" class="form-control" id="data-edit-item-email"/>
                        </div>
                        <div class="form-group">
                            <label for="data-edit-item-reference">Referance</label>
                            <input type="text" class="form-control" id="data-edit-item-reference"/>
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
                    <label for="data-item-info">Info</label>
                    <p type="text" class="form-control" id="data-item-info"></p>
                    <label for="data-item-email">Email</label>
                    <p type="text" class="form-control" id="data-item-email"></p>
                    <label for="data-item-reference">Referance</label>
                    <p type="text" class="form-control" id="data-item-reference"></p>
                    <label for="data-item-confirmed">Confirmed</label>
                    <p type="text" class="form-control" id="data-item-confirmed"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
        loadDonations();
    });

    function loadDonations() {
        $.ajax({
            url: `${baseUrl}donation.php?call=getAll`,
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
        var confirmed = item.confirmed !== 0;
        var deleted = item.deleted !== 0;
        var btnUnConfirm = `<button class="btn btn-success" onclick='unconfirmDonation(` + param + `)'><i class="fas fa-check-circle"></i></button>`;
        var btnConfirm = `<button class="btn btn-secondary" onclick='confirmDonation(` + param + `)'><i class="far fa-check-circle"></i></button>`;
        var btnEdit = `<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#data-edit-modal" data-json='${param}'><i class="far fa-edit"></i></button>`;
        var btnDetails = `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#data-details-modal" data-json='${param}'><i class="far fa-file-alt"></i></button>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreDonation(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteDonation(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.name}</td>` +
        `<td>${item.reference}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td>${confirmed? btnUnConfirm : btnConfirm}</td>` +
        `<td id="td-action-${item.id}">${btnEdit} ${btnDetails} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function addData() {
        var name = $('#data-add-item-name').val();
        var info = $('#data-add-item-info').val();
        var email = $('#data-add-item-email').val();
        var reference = $('#data-add-item-reference').val();
        var data = { 
            name: name, 
            info: info, 
            email: email, 
            reference: reference
        }
        $.ajax({
            url: `${baseUrl}donation.php?call=add`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadDonations();
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
        var name = $('#data-edit-item-name').val();
        var info = $('#data-edit-item-info').val();
        var email = $('#data-edit-item-email').val();
        var reference = $('#data-edit-item-reference').val();
        var data = { 
            id: id,
            name: name, 
            info: info, 
            email: email, 
            reference: reference
        }
        $.ajax({
            url: `${baseUrl}donation.php?call=update`,
            type:'post',
            data: data,
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadDonations();
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

    function confirmDonation(donation) {
        if(!confirm("Are you sure you want to confirm this donation?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}donation.php?call=confirm`,
            type:'post',
            data: { id: donation.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadDonations();
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

    function unconfirmDonation(donation) {
        if(!confirm("Are you sure you want to unconfirm this donation?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}donation.php?call=unconfirm`,
            type:'post',
            data: { id: donation.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadDonations();
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

    function restoreDonation(donation) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}donation.php?call=restore`,
            type:'post',
            data: { id: donation.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    donation.deleted = 0;
                    $(`table#data-table tr#${donation.id}`).replaceWith(generateTr(donation));
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

    function deleteDonation(donation) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}donation.php?call=delete`,
            type:'post',
            data: { id: donation.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    donation.deleted = 1;
                    $(`table#data-table tr#${donation.id}`).replaceWith(generateTr(donation));
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
            url: `${baseUrl}donation.php?call=deletePermanent`,
            type:'post',
            data: { id: item.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadDonations();
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
        modal.find('#data-add-item-info').val('');
        modal.find('#data-add-item-email').val('');
        modal.find('#data-add-item-reference').val('');
    });

    $('#data-edit-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var donation = button.data('json');

        var modal = $(this);
        modal.find('#data-edit-item-id').val(donation.id);
        modal.find('#data-edit-item-name').val(donation.name);
        modal.find('#data-edit-item-info').val(donation.info);
        modal.find('#data-edit-item-email').val(donation.email)
        modal.find('#data-edit-item-reference').val(donation.reference);
    });

    $('#data-details-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var item = button.data('json');

        var modal = $(this);
        modal.find('#data-item-name').text(item.name);
        modal.find('#data-item-info').text(item.info);
        modal.find('#data-item-email').text(item.email)
        modal.find('#data-item-reference').text(item.reference);
        modal.find('#data-item-confirmed').text(item.confirmed !== 0);
    });
</script>
<?php include('./footer.php'); ?>
