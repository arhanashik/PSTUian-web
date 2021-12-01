<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">User Inquiry</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">User Inquiry</li>
        </ol>
        <div class="mb-4">
            <table class="table table-bordered table-hover" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Type</th>
                        <th scope="col">Query</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Updated At</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
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
            </table>
        </div>
    </div>
</main>
<script>
    var currentPage = 1;
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
            url:`${baseUrl}user_query.php?call=getAll`,
            data: { page : page },
            type:'get',
            success:function(response) {
                $('#page-number').html(`Showing results for Page ${page}`);
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
        var btnReply = `<a class="btn btn-primary" href="user_inquiry_details.php?id=${item.id}"><i class="far fa-comment-dots"></i></a>`;
        var btnRestore = `<button class="btn btn-secondary" onclick='restoreQuery(` + param + `)'><i class="fas fa-trash-restore-alt"></i></button>`;
        var btnDelete = `<button class="btn btn-danger" onclick='deleteQuery(` + param + `)'><i class="far fa-trash-alt"></i></button>`;
        var btnDeletePermanent = `<button class="btn btn-danger <?php echo ($role == 'super_admin')? 'visible' : 'invisible';?>" onclick='deletePermanent(` + param + `)'><i class="far fa-minus-square"></i></button>`;
        return `<tr id="${item.id}">` + 
        `<th scope="row">${item.id}</th>` +
        `<td>${item.name}</td>` +
        `<td>${item.email}</td>` +
        `<td>${item.type}</td>` +
        `<td>${item.query}</td>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.updated_at}</td>` +
        `<td id="td-action-${item.id}">${btnReply} ${deleted? btnRestore : btnDelete} ${btnDeletePermanent}</td>` +
        `</tr>`;
    }

    function restoreQuery(query) {
        if(!confirm("Are you sure you want to restore this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}user_query.php?call=restore`,
            type:'post',
            data: { id: query.id},
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    query.deleted = 0;
                    $(`table#data-table tr#${query.id}`).replaceWith(generateTr(query));
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

    function deleteQuery(query) {
        if(!confirm("Are you sure you want to delete this?")){
            return false;
        }
        $.ajax({
            url: `${baseUrl}user_query.php?call=delete`,
            type:'post',
            data: { id: query.id },
            success:function(response){
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    query.deleted = 1;
                    $(`table#data-table tr#${query.id}`).replaceWith(generateTr(query));
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
            url: `${baseUrl}user_query.php?call=deletePermanent`,
            type:'post',
            data: { id: item.id},
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
</script>
<?php include('./footer.php'); ?>
