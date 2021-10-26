<?php include('./header.php'); ?>
<div class="site-section ">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="post-entry-1">
          <h2 style="color:black;">How to donate</h2>
          <p id="donation-opiton">
            We are greateful for your support. No matter how small it is, for us it is 
            an inspiration to continue our work.</br></br>
            You can send a donation to the following accounts.
          </p>
          <div class="card" style="width: 18rem;">
            <div class="card-header">
              Accounts
            </div>
            <ul id="donation-options" class="list-group list-group-flush">
            </ul>
          </div>
          <p id="donation-opiton">
            After sending the donation please use "Save donation" form to save the info.
            When your donation is approved by our admins, you will find your donation info 
            in the bellow list.
          </p>
        </div>
      </div>
      <div class="col-lg-6 bg-light">
        <div class="section-title mb-2">
          <h2>Save Donation</h2>
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
                  <label for="name">Referance(e.g. TrxID/TxnId)</label>
                  <input type="text" id="reference" class="form-control form-control-lg">
              </div>
          </div>
          <div class="row">
              <div class="col-md-12 form-group">
                  <label for="message">Message</label>
                  <textarea id="message" cols="30" rows="3" class="form-control" placeholder="e.g. CSE 11th Batch."></textarea>
              </div>
          </div>

          <div class="row">
              <div class="col-12">
                  <input type="submit" value="Send" onclick="addData()" class="btn btn-primary py-3 px-5">
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="site-section">
  <div class="container">
    <div class="row">
      <div class="section-title mb-3">
        <h2>Donation</h2>
      </div>
      <div class="mb-4">
        <table class="table table-bordered table-hover" id="data-table">
            <thead>
                <tr>
                    <th scope="col">Sl</th>
                    <th scope="col">Date</th>
                    <th scope="col">Name</th>
                    <th scope="col">Reference</th>
                    <th scope="col">Message</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
        <button type="button" class="btn btn-primary" data-dismiss="modal">Send Again</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  var donors = [];
  $(document).ready(function() {
      getDonationOptions();
      loadDonations();
  });

  function getDonationOptions() {
    $.ajax({
        url: `${baseUrl}donation.php?call=option`,
        type:'get',
        success:function(response){
          $('#donation-options').empty();
          var json = JSON.parse(response);
          if(json && json['success'] === false) {
            return false;
          }
          var data = json['data'];
          if(data !== null || data !== '') {
            var options = data.split('<br>');
            for (i = 0; i < options.length; i++) {
              var li = `<li class="list-group-item">${options[i]}</li>`;
              $('#donation-options').append(li);
            }
          }
        },
        error: function(xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            console.log(err);
        }
    });
  }

  function loadDonations() {
    $.ajax({
        url: `${baseUrl}donation.php?call=donors`,
        type:'get',
        success:function(response){
          $('#data-table tbody').empty();
          var json = JSON.parse(response);
          if(json && json['success'] === false) {
            return false;
          }
          donors = json['data'];
          for (i = 0; i < donors.length; i++) {
              $('#data-table > tbody:last-child').append(generateTr(i + 1, donors[i]));
          }
        },
        error: function(xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            console.log(err);
        }
    });
  }

  function generateTr(sl, item) {
        return `<tr id="${sl}">` + 
        `<th scope="row">${sl}</th>` +
        `<td>${item.created_at}</td>` +
        `<td>${item.name}</td>` +
        `<td>${item.reference}</td>` +
        `<td>${item.info}</td>` +
        `</tr>`;
    }

  function addData() {
    var reference = $('#reference').val();
    if(reference == null || reference == '') {
      $('#details-dialog-title').text('Error');
      $('#details-dialog-message').text('Sorry, reference is required.');
      $('#details-modal').modal('show');
      return;
    }
    var name = $('#name').val();
    var email = $('#email').val();
    var message = $('#message').val();
    if(name == null || name == '') {
      name = 'anonymous';
    }
    if(email == null || email == '') {
      email = 'anonymous';
    }
    if(message == null || message == '') {
      message = 'anonymous';
    }

    $.ajax({
        url: `${baseUrl}donation.php?call=save`,
        type:'post',
        data: { name: name, email: email, reference: reference, info: message },
        success:function(response) {
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
            $('#details-dialog-message').text("Sorry, failed to save your donation. Please try again");
            console.error(error);
          }
          $('#details-modal').modal('show');
        }
    });
  }
</script>
<?php include './footer.php'; ?>