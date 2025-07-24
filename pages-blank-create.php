<?php include('Master/head.php');?>

<body>
  
  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>New User</h1>
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
    ?>
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">New User Form</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="Controllers/UserController.php" method="post">
                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="floatingName" name="user_name" placeholder="Your Name">
                    <label for="floatingName">User Name</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="email" class="form-control" id="floatingEmail" name="user_email" placeholder="Your Email">
                    <label for="floatingEmail">User Email</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="user_password" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                  </div>
                </div>
                  
                <div class="text-center">
                  <button type="submit" name="create_user" class="btn btn-dark">Submit</button>
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