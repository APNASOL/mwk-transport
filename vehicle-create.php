<?php include('Master/head.php');?>

<body>
  
  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>New vehicle</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Vehicle</li>
          <li class="breadcrumb-item active">New vehicle create</li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
            <a class="btn btn-dark" href="pages-blank-index.php"
            ><i class="bi bi-back"></i> Go to all vehicles
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
              <h5 class="card-title">New vehicle</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="Controllers/VehicleController.php" method="post">

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="owner_name" name="owner_name" required placeholder="Owner Name">
                    <label for="owner_name">Owner name</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="number" name="number" required placeholder="Vehicle number">
                    <label for="number">Vehicle number</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="date" name="date" required placeholder="Date">
                    <label for="name">Date</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="balance" name="balance" required placeholder="Balance">
                    <label for="balnce">Balance</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="company_balance" name="company_balance" required placeholder="Balance">
                    <label for="balnce">Company Balance</label>
                  </div>
                </div>

                  
                <div class="text-center">
                  <button type="submit" name="create_vehicle" class="btn btn-dark">Add</button>
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