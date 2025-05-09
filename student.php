<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <title>PHP CRUD using jquery ajax without page reload</title>

  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
</head>

<body>

  <!-- Add Student -->
  <div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveStudent" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="mb-3">
              <label for="">Name</label>
              <input type="text" name="name" class="form-control" />
            </div>
            <div class="mb-3">
              <label for="">Email</label>
              <input type="text" name="email" class="form-control" />
            </div>
            <div class="mb-3">
              <label for="">Phone</label>
              <input type="text" name="phone" class="form-control" />
            </div>
            <div class="mb-3">
              <label for="">Course</label>
              <input type="text" name="course" class="form-control" />
            </div>
            <div class="mb-3">
              <label for="student_image">Student Image</label>
              <input type="file" name="student_image" class="form-control" accept=".png, .jpeg, .jpg" />
              <small class="form-text text-muted">Upload a profile image (JPG, PNG)</small>
            </div>
            <div class="mb-3">
              <input type="hidden" name="IndexNumber" value="" />
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Student</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Student Modal -->
  <div class="modal fade" id="studentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateStudent" enctype="multipart/form-data">
          <div class="modal-body">

            <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

            <input type="hidden" name="student_id" id="student_id">

            <div class="mb-3">
              <label for="">Name</label>
              <input type="text" name="name" id="name" class="form-control" />
            </div>
            <div class="mb-3">
              <label for="">Email</label>
              <input type="text" name="email" id="email" class="form-control" />
            </div>
            <div class="mb-3">
              <label for="">Phone</label>
              <input type="text" name="phone" id="phone" class="form-control" />
            </div>
            <div class="mb-3">
              <label for="">Course</label>
              <input type="text" name="course" id="course" class="form-control" />
            </div>
            <div class="mb-3">
              <label for="student_image">Student Image</label>
              <input type="file" name="student_image" class="form-control" accept=".png, .jpeg, .jpg" />
              <small class="form-text text-muted">Upload a new image or leave empty to keep current image</small>
              <div id="current_image_container" class="mt-2">

              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Student</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Student Modal -->
  <div class="modal fade" id="studentViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">View Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="">Name</label>
            <p id="view_name" class="form-control"></p>
          </div>
          <div class="mb-3">
            <label for="">Email</label>
            <p id="view_email" class="form-control"></p>
          </div>
          <div class="mb-3">
            <label for="">Phone</label>
            <p id="view_phone" class="form-control"></p>
          </div>
          <div class="mb-3">
            <label for="">Course</label>
            <p id="view_course" class="form-control"></p>
          </div>
          <div class="mb-3">
            <label>Student Image</label>
            <div id="view_image" class="form-control" style="min-height: 100px;"></div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="container mt-4">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>PHP Ajax CRUD without page reload using Bootstrap Modal

              <button type="button" class="btn btn-primary addNewStudent float-end" data-bs-toggle="modal" data-bs-target="#studentAddModal">
                Add Student
              </button>
            </h4>
          </div>
          <div class="card-body">

            <table id="myTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Index Number</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Course</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                require 'dbcon.php';

                $query = "SELECT * FROM students where is_deleted = '0'";
                $query_run = mysqli_query($con, $query);

                if (mysqli_num_rows($query_run) > 0) {
                  foreach ($query_run as $student) {
                ?>
                    <tr>
                      <td><?= $index = isset($index) ? $index + 1 : 1 ?></td>
                      <td><?= $student['IndexNumber'] ?></td>
                      <td><?php if (!empty($student['image'])): ?>
                          <img src="uploads/<?= $student['image'] ?>" width="50" height="50" class="rounded-circle" onerror="this.src='uploads/no-image.jpg'">
                        <?php else: ?>
                          <img src="uploads/no-image.jpg" width="50" height="50" class="rounded-circle">
                          <?php endif; ?><?= $student['name'] ?>
                      </td>
                      <td><?= $student['email'] ?></td>
                      <td><?= $student['phone'] ?></td>
                      <td><?= $student['course'] ?></td>


                      <td>
                        <button type="button" value="<?= $student['id']; ?>" class="viewStudentBtn btn btn-info btn-sm">View</button>
                        <button type="button" value="<?= $student['id']; ?>" class="editStudentBtn btn btn-success btn-sm">Edit</button>
                        <button type="button" value="<?= $student['id']; ?>" class="deleteStudentBtn btn btn-danger btn-sm">Delete</button>
                      </td>
                    </tr>
                <?php
                  }
                }
                ?>

              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>





  <script>
    $(document).on('submit', '#saveStudent', function(e) {
      e.preventDefault();

      swal({
        title: "Are you sure?",
        text: "Are you sure you want to Add this student?",
        icon: "warning",
        buttons: ["No, cancel!", "Yes, do it!"],
        dangerMode: true,
      }).then((willAdd) => {
        if (willAdd) {
          var formData = new FormData(this);
          formData.append("save_student", true);

          $.ajax({
            type: "POST",
            url: "code.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

              var res = jQuery.parseJSON(response);
              if (res.status == 422) {
                // $('#errorMessage').removeClass('d-none');
                // $('#errorMessage').text(res.message);
                if (res.status == 422) {
                  swal("Error!", res.message, "error");
                }

              } else if (res.status == 200) {

                $('#errorMessage').addClass('d-none');
                $('#studentAddModal').modal('hide');
                $('#saveStudent')[0].reset();

                swal("Success!", res.message, "success");

                $('#myTable').load(location.href + " #myTable");

              } else if (res.status == 500) {
                // alert(res.message);
                swal("Error!", res.message, "error");
              }
            }
          });
        }
      })
    });



    $(document).on('click', '.editStudentBtn', function() {

      var student_id = $(this).val();

      $.ajax({
        type: "GET",
        url: "code.php?student_id=" + student_id,
        success: function(response) {

          var res = jQuery.parseJSON(response);
          if (res.status == 404) {

            alert(res.message);
          } else if (res.status == 200) {

            $('#student_id').val(res.data.id);
            $('#name').val(res.data.name);
            $('#email').val(res.data.email);
            $('#phone').val(res.data.phone);
            $('#course').val(res.data.course);

            if (res.data.image) {
              $('#current_image_container').html('<p>Current image:</p><img src="uploads/' + res.data.image + '" class="img-fluid rounded" style="max-height: 100px;">');
            } else {
              $('#current_image_container').html('<p>No current image</p>');
            }

            $('#studentEditModal').modal('show');
          }

        }
      });

    });

    $(document).on('submit', '#updateStudent', function(e) {
      e.preventDefault();
      swal({
        title: "Are you sure?",
        text: "Are you sure you want to Update this student?",
        icon: "warning",
        buttons: ["No, cancel!", "Yes, do it!"],
        dangerMode: true,
      }).then((willAdd) => {
        if (willAdd) {
          var formData = new FormData(this);
          formData.append("update_student", true);

          $.ajax({
            type: "POST",
            url: "code.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

              var res = jQuery.parseJSON(response);
              if (res.status == 422) {
                // $('#errorMessageUpdate').removeClass('d-none');
                // $('#errorMessageUpdate').text(res.message);
                swal("Error!", res.message, "error");

              } else if (res.status == 200) {

                $('#errorMessageUpdate').addClass('d-none');

                swal("Success!", res.message, "success");
                $('#studentEditModal').modal('hide');
                $('#updateStudent')[0].reset();

                $('#myTable').load(location.href + " #myTable");

              } else if (res.status == 500) {
                alert(res.message);
              }
            }
          });
        }
      })
    });

    $(document).on('click', '.viewStudentBtn', function() {

      var student_id = $(this).val();
      $.ajax({
        type: "GET",
        url: "code.php?student_id=" + student_id,
        success: function(response) {

          var res = jQuery.parseJSON(response);
          if (res.status == 404) {

            alert(res.message);
          } else if (res.status == 200) {

            $('#view_name').text(res.data.name);
            $('#view_email').text(res.data.email);
            $('#view_phone').text(res.data.phone);
            $('#view_course').text(res.data.course);

            if (res.data.image) {
              $('#view_image').html('<img src="uploads/' + res.data.image + '" class="img-fluid rounded" style="max-height: 200px;">');
            } else {
              $('#view_image').html('<p>No image available</p>');
            }

            $('#studentViewModal').modal('show');
          }
        }
      });
    });

    $(document).on('click', '.deleteStudentBtn', function(e) {
      e.preventDefault();

      swal({
          title: "Are you sure?",
          text: "Are you sure you want to Delete this student?",
          icon: "warning",
          buttons: ["No, cancel!", "Yes, do it!"],
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            var student_id = $(this).val();
            $.ajax({
              type: "POST",
              url: "code.php",
              data: {
                'delete_student': true,
                'student_id': student_id
              },
              success: function(response) {
                var res = jQuery.parseJSON(response);
                if (res.status == 500) {
                  swal("Error!", res.message, "error");
                } else {
                  swal("Success!", res.message, "success");
                  $('#myTable').load(location.href + " #myTable");
                }
              }
            });
          }
        });
    });
  </script>

</body>

</html>