<?php include('./header.php'); ?>
<div class="site-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-9">
        <div class="section-title">
          <span class="caption d-block small">Employees</span>
          <h2 id="section-title">Loading...</h2>
        </div>
        <div id="list-data">
          <!-- <div class="post-entry-2 d-flex" style="height:140px;">
            <div class="thumbnail order-md-2" style="background-image: url('images/person_1.jpg')"></div>
            <div class="contents order-md-1 pl-0">
              <h2><a href="blog-single.html">Employee Name</a></h2>
              <p class="mb-3">Employee Details</p>
              <div class="post-meta">
                <span class="d-block">Officer 1<span class="mx-1">&bullet;</span>Mathematics</span>
                <span class="d-block">Phone: (+81)80-5854-8514
                <span class="d-block">Address: Tokyo, Japan</span>
                <span class="date-read">
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
                  <select class="form-control form-control-lg bg-light float-start" id="faculties" aria-label="Select Faculty"></select>
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
    var selectedFaculty;
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

    function loadEmployees(faculty_id) {
        $.ajax({
            url: `${baseUrl}employee.php?call=getAll`,
            type:'get',
            data: {faculty_id: faculty_id},
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
      return `<div class="post-entry-2 d-flex">` +
          `<div class="thumbnail order-md-2" style="background-image: url('${item.image_url}')"></div>` +
          `<div class="contents order-md-1 pl-0">` +
          `<h2><a href="#">${item.name}</a></h2>` +
          `<div class="post-meta">` +
          `<span class="d-block">${item.designation}<span class="mx-1">&bullet;</span>${item.department}</span>` +
          `<span class="d-block">Phone: ${item.phone}</span>` + 
          `<span class="d-block">Address: ${item.address}</span>` +
          `<span class="date-read">` +
          `<a target="_blank" href="#">Facebook</a></span>` +
          `</div>` +
          `</div>` +
          `</div>`;
    }

    function addFacultiesToDropdown(faculties, dropdown) {
        dropdown.empty();
        for (i = 0; i < faculties.length; i++) {
            var faculty = faculties[i];
            var item = `<option value="${faculty.id}">${faculty.short_title}</option>`;
            dropdown.append(item);
        }
    }

    $('#faculties').change(function() {
        selectedFaculty = $(this).val();
        var filteredFaculty = faculties.filter(faculty => faculty.id == selectedFaculty);
        if(filteredFaculty && filteredFaculty.length > 0) {
          $('#section-title').text(filteredFaculty[0].title);
        }
        loadEmployees(selectedFaculty);
    });
</script>
<?php include './footer.php'; ?>