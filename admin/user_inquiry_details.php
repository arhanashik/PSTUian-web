<?php include('./header.php'); ?>
<?php
if(!isset($_GET['id']) || strlen($_GET['id']) <= 0) {
   exit();
}

$id = $_GET['id'];
?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Details</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="user_inquiry.php">User Inquiry</a></li>
            <li class="breadcrumb-item active">Details</li>
        </ol>
        
        <div class="card">
			<div class="row">
                <div class="chat-messages p-4" id="chat-messages">
                </div>
            </div>
        </div>
        <div class="flex-grow-0 py-3 px-4 border-top">
            <div class="input-group">
                <textarea id="message" cols="30" rows="3" class="form-control" placeholder="Type your message"></textarea>
                <button class="btn btn-primary" onclick="addData()">Send</button>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
        var query_id = <?php echo $id; ?>;
        loadUserQuery(query_id);
        loadUserQueryReplies(query_id);
    });

    function loadUserQuery(query_id) {
        $.ajax({
            url:`${baseUrl}user_query.php?call=get`,
            type:'get',
            data: {id: query_id},
            success:function(response){
                $('#chat-messages').empty();
                var query = JSON.parse(response);
                if(query && query !== '') {
                    $('#chat-messages').append(generateChatMsgLeft(query));
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function loadUserQueryReplies(query_id) {
        $.ajax({
            url:`${baseUrl}user_query_reply.php?call=getAll`,
            type:'get',
            data: {query_id: query_id},
            success:function(response) {
                var list = JSON.parse(response);
                if(list['code'] && list['code'] !== 200) {
                    $('#toast-title').text('Failed');
                    $('#toast-message').text(list['message']);
                    $('#toast').toast('show');
                    return;
                }
                for (i = 0; i < list.length; i++) {
                    $('#chat-messages').append(generateChatMsgRight(list[i]));
                }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function generateChatMsgLeft(item) {
        return `<div class="chat-message-left pb-4">` +
            `<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">` +
            `<div class="font-weight-bold mb-1">${item.name}</div>` +
            item.query +
            `</div>` +
            `<div class="text-muted small text-nowrap mt-2">${item.created_at}</div>` +
            `</div>`;
    }

    function generateChatMsgRight(item) {
        return `<div class="chat-message-right pb-4">` +
            `<div class="flex-shrink-1 bg-light rounded py-2 px-3 ml-3">` +
            `<div class="font-weight-bold mb-1">${item.admin_email}</div>` +
            item.reply +
            `</div>` +
            `<div class="text-muted small text-nowrap mt-2">${item.created_at}</div>` +
            `</div>`;
    }

    function addData() {
        var message = $('#message').val();
        if(message == null || message == '') {
            $('#toast-title').text('Error');
            $('#toast-message').text('Type a messge first');
            $('#toast').toast('show');
        return;
        }
        var query_id = <?php echo $id; ?>;
        var admin_id = <?php echo $admin['id']; ?>;
        $.ajax({
            url: `${baseUrl}user_query_reply.php?call=add`,
            type:'post',
            data: { query_id: query_id, admin_id: admin_id, reply: message },
            success:function(response) {
                var data = JSON.parse(response);
                if(data['success'] === true) {
                    loadUserQueryReplies(query_id);
                    $('#toast-title').text('Success');
                } else {
                    $('#toast-title').text('Error');
                }
                $('#toast-message').text(data['message']);
                $('#toast').toast('show');
            },
            error: function(xhr, status, error) {
                $('#toast-title').text('Error');
                $('#toast-message').text('Somthing went wrong, please try again.');
                $('#toast').toast('show');
            }
        });
    }
</script>
<?php include('./footer.php'); ?>
