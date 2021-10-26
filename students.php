<?php include('./header.php'); ?>
<div class="site-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-9">
        <div class="section-title">
          <span class="caption d-block small">Students</span>
          <h2 id="section-title">Loading...</h2>
        </div>
        <div id="list-data">
          <!-- <div class="post-entry-2 d-flex" style="height:140px;">
            <div class="thumbnail order-md-2" style="background-image: url('images/person_1.jpg')"></div>
            <div class="contents order-md-1 pl-0">
              <h2><a href="blog-single.html">Md Hasnain</a></h2>
              <p class="mb-3">Product Owner and Team leader. Find my bio here: https://arhanashik.github.io/</p>
              <div class="post-meta">
                <span class="d-block">ID: 1302018<span class="mx-1">&bullet;</span>Reg: 04192</span>
                <span class="d-block">Phone: (+81)80-5854-8514<span class="mx-1">&bullet;</span>Email: ashik.pstu.cse@gmail.com</span>
                <span class="d-block">Blood Group: B+<span class="mx-1">&bullet;</span>Address: Tokyo, Japan</span>
                <span class="date-read">
                  <a target="_blank" href="#" download>Download CV</a><span class="mx-1">&bullet;</span>
                  <a target="_blank" href="#">LinkedIn</a><span class="mx-1">&bullet;</span>
                  <a target="_blank" href="#">Facebook</a></span>
              </div>
            </div>
          </div> -->
        </div>
      </div>

      <div class="col-lg-3">
        <div class="section-title">
          <h2>Filter</h2>
        </div>

        <form method="post">
          <div class="row">
              <div class="col-md-12">
                  <select class="form-control form-control-lg bg-light float-start" id="faculties" aria-label="Select Faculty">
                  </select>
              </div>
          </div>
          <div class="row" style="margin-top:10px;">
              <div class="col-md-12">
                  <select class="form-control form-control-lg bg-light float-start" id="batches" aria-label="Select Batch">
                  </select>
              </div>
          </div>
          <br>
          <div class="row">
              <div class="col-12">
                  <input type="submit" value="Filter" class="btn btn-primary form-control">
              </div>
          </div>
        </form>
      </div>
    </div>

    <!-- <div class="row">
      <div class="col-lg-6">
        <ul class="custom-pagination list-unstyled">
          <li><a href="#">1</a></li>
          <li class="active">2</li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
        </ul>
      </div>
    </div> -->
  </div>
</div>
<script>
    var faculties = [];
    var batches = [];
    var selectedFaculty;
    var selectedBatch;
    $(document).ready(function() {
        loadFaculties();
    });

    function loadFaculties() {
        $.ajax({
            url: `${baseUrl}faculty.php?call=getAll`,
            type:'get',
            success:function(response){
                var json = JSON.parse(response);
                if(json && json['success'] === false) {
                  return false;
                }
                faculties = json['data'];
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

    function loadBatches(faculty_id) {
      $.ajax({
          url: `${baseUrl}batch.php?call=getAll`,
          type:'get',
          data: {faculty_id: faculty_id},
          success:function(response){
            $('#list-data').empty();
            var json = JSON.parse(response);
            if(json && json['success'] === false) {
              return false;
            }
            batches = json['data'];
            addBatchesToDropdown(batches, $('#batches'));
            if(batches && batches.length > 0) {
                $('#batches').val(batches[0].id).change();
            }
          },
          error: function(xhr, status, error) {
              var err = JSON.parse(xhr.responseText);
              console.log(err);
          }
      });
    }

    function loadStudents(faculty_id, batch_id) {
        $.ajax({
            url: `${baseUrl}student.php?call=getAll`,
            type:'get',
            data: {faculty_id: faculty_id, batch_id: batch_id},
            success:function(response){
              $('#list-data').empty();
              var json = JSON.parse(response);
                if(json && json['success'] === false) {
                  return false;
                }
              var list = json['data'];
              for (i = 0; i < list.length; i++) {
                  $('#list-data').append(generateDiv(list[i]));
              }
            },
            error: function(xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
            }
        });
    }

    function generateDiv(item) {
      var hasBio = item.bio && item.bio.length > 0;
      var phone = filterValue(item.phone);
      var email = filterValue(item.email);
      var blood = filterValue(item.blood);
      var address = filterValue(item.address);
      var bio = hasBio? `<p class="mb-3">${item.bio}</p>` : ``;
      return `<div class="post-entry-2 d-flex">` +
          `<div class="thumbnail order-md-2" style="background-image: url('${item.image_url}')"></div>` +
          `<div class="contents order-md-1 pl-0">` +
          `<h2><a href="#">${item.name}</a></h2>` +
          `${bio}` +
          `<div class="post-meta">` +
          `<span class="d-block">ID: ${item.id}<span class="mx-1">&bullet;</span>Reg: ${item.reg}</span>` +
          `<span class="d-block">Phone: ${phone}<span class="mx-1">&bullet;</span>Email: ${email}</span>` +
          `<span class="d-block">Bloood Group: ${blood}<span class="mx-1">&bullet;</span>Address: ${address}</span>` +
          `<span class="date-read">` +
          `<a target="_blank" href="${item.cv_link}" download>Download CV</a><span class="mx-1">&bullet;</span>` +
          `<a target="_blank" href="${item.linked_in}">LinkedIn</a><span class="mx-1">&bullet;</span>` +
          `<a target="_blank" href="${item.fb_link}">Facebook</a></span>` +
          `</div>` +
          `</div>` +
          `</div>`;
    }

    function filterValue(value) {
      return value == null || value.length === 0? "~" : value;
    }

    function addFacultiesToDropdown(faculties, dropdown) {
        dropdown.empty();
        for (i = 0; i < faculties.length; i++) {
            var faculty = faculties[i];
            var item = `<option value="${faculty.id}">${faculty.short_title}</option>`;
            dropdown.append(item);
        }
    }

    function addBatchesToDropdown(batches, dropdown) {
        dropdown.empty();
        for (i = 0; i < batches.length; i++) {
            var batch = batches[i];
            var item = `<option value="${batch.id}">${batch.name}</option>`;
            dropdown.append(item);
        }
    }

    $('#faculties').change(function() {
        selectedFaculty = $(this).val();
        loadBatches(selectedFaculty);
    });

    $('#batches').change(function() {
        selectedBatch = $(this).val();
        var filteredBatch = batches.filter(batch => batch.id == selectedBatch);
        if(filteredBatch && filteredBatch.length > 0) {
          $('#section-title').text(filteredBatch[0].name);
        }
        loadStudents(selectedFaculty, selectedBatch);
    });
</script>
<?php include './footer.php'; ?>