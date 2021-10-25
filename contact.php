<?php include('./header.php'); ?>
<div class="site-section bg-light">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="section-title mb-5">
          <h2>Contact Us</h2>
        </div>
          <div class="row">
              <div class="col-md-6 form-group">
                  <label for="name">Name</label>
                  <input type="text" id="name" class="form-control form-control-lg">
              </div>
              <div class="col-md-6 form-group">
                  <label for="email">Email Address</label>
                  <input type="text" id="email" class="form-control form-control-lg">
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 form-group">
                  <label for="message">Message</label>
                  <textarea name="" id="message" cols="30" rows="8" class="form-control"></textarea>
              </div>
          </div>

          <div class="row">
              <div class="col-12">
                  <input type="submit" value="Send Message" onclick="addData()" class="btn btn-primary py-3 px-5">
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="detailsModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="details-dialog-title">Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="details-dialog-message"></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-secondary" href="index.php">Home</a>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Send Again</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function addData() {
    var name = $('#name').val();
    var email = $('#email').val();
    var message = $('#message').val();
    if(name == null || name == '' || email == null || email == '' ||  message == null || message == '') {
      $('#details-dialog-title').text('Error');
      $('#details-dialog-message').text('Sorry, all fields are required.');
      $('#details-modal').modal('show');
      return;
    }
    $.ajax({
        url: `${baseUrl}user_query.php?call=add`,
        type:'post',
        data: { name: name, email: email, type: 'query', query: message },
        success:function(response) {
          console.log(response);
          var data = JSON.parse(response);
          if(data['success'] === true) {
            $('#details-dialog-title').text('Success');
          } else {
            $('#details-dialog-title').text('Error');
          }
          $('#details-dialog-message').text(data['message']);
          $('#details-modal').modal('show');
        },
        error: function(xhr, status, error) {
          $('#details-dialog-title').text('Error');
          try {
            var err = JSON.parse(xhr.responseText);
            $('#details-dialog-message').text(err.Message);
          } catch (error) {
            $('#details-dialog-message').text("Sorry, failed to send your query. Please try again");
            console.error(error);
          }
          $('#details-modal').modal('show');
        }
    });
  }
</script>
<?php include './footer.php'; ?>