<?php include('Master/head.php');?>

<body>
  
  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Update User</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Pages</li>
          <li class="breadcrumb-item active">New User create</li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
            <a class="btn btn-dark" href="pages-blank-index.php"
            ><i class="bi bi-back"></i> Go to all users
          </a>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
    <?php
      if (@$_GET['errorMessage']) {
          ?> 
              <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                <?php echo $_GET['errorMessage']; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              

              <?php
      }
    //   here going to fetch the record according to user_id
    $conn = OpenCon();
    $user_id = @$_GET['user_id'];
    $user_object = mysqli_query($conn,"SELECT * FROM users WHERE id = $user_id");
    $user = mysqli_fetch_array($user_object);
    ?>
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Update User Form</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="Controllers/UserController.php" method="post">
              <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" value="<?php echo $user['name'];?>" name="user_name" placeholder="User Name">
                    <label for="floatingName">User Name</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="email" class="form-control" value="<?php echo $user['email'];?>" name="user_email" placeholder="User Email">
                    <label for="floatingEmail">User Email</label>
                  </div>
                </div>
                 
                  
                <div class="text-center">
                  <button type="submit" name="update_user" class="btn btn-dark">Update</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End floating Labels Form -->

            </div>
          </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include('Master/footer.php');?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <?php include('Master/scripts.php');?>
  <!-- Scripts -->

</body>

</html>