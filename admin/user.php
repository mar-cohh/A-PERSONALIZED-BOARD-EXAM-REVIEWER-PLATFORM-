<?php
include("../includes/header.php");
include("../includes/navbar.php");
include("../includes/topbar.php");
?>

<!-- Button trigger modal -->
<div class="container">
  <div class="page-inner">
  <div style="margin-bottom: -30px; margin-top: -10px;" class=" pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Manage Users</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card card-round">
          <div class="card-header">
            <div class="row">
              <div class="col-md-10">
                <h3 class="panel-title"></h3>
              </div>

              <div class="col-md-2" align="right" style="margin-left: -17px;">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#registerModal"
                  title="Add User">
                  <i class="fi fi-br-plus"></i>
                </button>
              </div>

            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="add-row" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Status</th>
                    <th>Details</th>
                    <th>Action</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- User Details Modal -->
        <div id="userModal" class="modal fade" tabindex="-1" aria-labelledby="userModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>Created</th>
                    </tr>
                  </thead>
                  <tbody id="userList">
                  </tbody>

                </table>
              </div>
            </div>
          </div>
        </div>
      
        <!-- Register Modal -->
        <div id="registerModal" class="modal fade" tabindex="-1" aria-labelledby="registerModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="reg_action.php" method="post">
                  <div class="mb-3">
                    <label for="first_name" class="form-label">First name:</label>
                    <input type="text" class="form-control" name="first_name" required>
                  </div>
                  <div class="mb-3">
                    <label for="last_name" class="form-label">Last name:</label>
                    <input type="text" class="form-control" name="last_name" required>
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Courses:</label>
                    <select name="course" class="form-control form-control-lg bg-light fs-6" required>
                        <option value="" disabled selected></option>
                        <?php
                        // Connect to the database and fetch course options
                        $query = "SELECT * FROM exam_course WHERE id = 1"; // assuming exam_course has columns `id` and `course_name`
                        $result = $db->query($query);

                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='".$row['id']."'>".$row['course']."</option>";
                        }
                        ?>
                    </select>
                </div>
                 
                  <div class="modal-footer">
                    <input type="hidden" name="userId" id="userId" />
                    <input type="hidden" name="action" id="action" value="" />
                    <button type="submit" class="btn btn-primary">Register</button>
				        	</div>

                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php
include("../includes/scripts.php");
include("../includes/footer.php");
?>
<script src="./JSFILE/user.js"></script>