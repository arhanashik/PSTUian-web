<?php include('./header.php'); ?>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Email</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
            <li class="breadcrumb-item active">Email</li>
        </ol>
        
        <div class="card">
			<div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="sender">Sender</label>
                        <select class="form-select" id="sender" aria-label="Sender">
                            <option selected value="admin@pstuian.com">admin@pstuian.com</option>
                            <option value="info@pstuian.com">info@pstuian.com</option>
                            <option value="test@pstuian.com">test@pstuian.com</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="receiver">Receiver</label>
                        <input type="email" class="form-control" id="receiver"/>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title"/>
                    </div>
                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea class="form-control" rows='5' id="body" type="text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p class="text-danger" id="error-message"></p>
                <button type="button" class="btn btn-primary" onclick="addData()">Send</button>
            </div>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
        
    });

    function addData() {
        let sender = $('#sender').val();
        let receiver = $('#receiver').val();
        let title = $('#title').val();
        let body = $('#body').val();
        if(sender == null || sender == '' || receiver == null || receiver == '' || title == null || title == '' || body == null || body == '') {
            $('#error-message').html('All fields are required!');
            return;
        }
        $('#error-message').html('');
        $.ajax({
            url: `${baseUrl}email.php?call=send`,
            type:'post',
            data: { 
                sender: sender, 
                receiver: receiver, 
                title: title, 
                body: body, 
            },
            success:function(response) {
                try {
                    let result = JSON.parse(response);
                    if(result['success'] === true) {
                        $('#title').val('');
                        $('#body').val('');
                    }
                    $('#error-message').html(result['message']);
                } catch (error) {
                    console.log(response);
                    $('#error-message').html('Invalid server response.');
                }
            },
            error: function(xhr, status, error) {
                $('#error-message').html('Server error: ' + error);
            }
        });
    }
</script>
<?php include('./footer.php'); ?>
