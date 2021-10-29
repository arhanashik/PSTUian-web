<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Authentication</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Authentication</li>
        </ol>
        <div class="mb-4">
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">User Id</th>
                        <th scope="col">Device Id</th>
                        <th scope="col">User Type</th>
                        <th scope="col">Auth Token</th>
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
</main>
<script>
    $(document).ready(function() {
        loadAuths();
    });

    function loadAuths() {
        $.ajax({
            url:`${baseUrl}auth.php?call=getAll`,
            type:'get',
            success:function(response){
                console.log(response);
                $('#data-table tbody').empty();
                var list = JSON.parse(response);
                if(list['code'] && list['code'] !== 200) {
                    $('#toast-title').text('Failed');
                    $('#toast-message').text(list['message']);
                    $('#toast').toast('show');
                    return;
                }
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
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreAuth(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteAuth(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.user_id}</td>` +
        `<td>${item.device_id}</td>` +
        `<td>${item.user_type}</td>` +
        `<td>${item.auth_token}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function restoreAuth(auth) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}auth.php?call=restore`,
            type:'post',
            data: { id: auth.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    auth.deleted = 0;
                    $(`table#data-table tr#${auth.id}`).replaceWith(generateTr(auth));
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

    function deleteAuth(auth) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}auth.php?call=delete`,
            type:'post',
            data: { id: auth.id },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    auth.deleted = 1;
                    $(`table#data-table tr#${auth.id}`).replaceWith(generateTr(auth));
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
            url: `${baseUrl}auth.php?call=deletePermanent`,
            type:'post',
            data: { id: item.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadAuths();
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
</script>
<?php include('./footer.php'); ?>
