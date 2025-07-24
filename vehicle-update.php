<?php include('Master/head.php');?>

<body>
  
  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Update vehicle</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Vehicle</li>
          <li class="breadcrumb-item active">Update vehicle</li>
        </ol>
      </nav>
      
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
    //   here going to fetch the record according to vehicle_id
    $conn = OpenCon();
    $vehicle_id = @$_GET['vehicle_id'];
     
    $vehicle_object = mysqli_query($conn,"SELECT * FROM vehicles WHERE id = $vehicle_id");
    $vehicle = mysqli_fetch_array($vehicle_object);
    ?>
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Update vehicle</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="Controllers/VehicleController.php" method="post">
              <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
                <div class="col-md-4">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="owner_name" name="owner_name" required placeholder="Owner Name" value="<?php echo $vehicle['owner_name']; ?>">
                      <label for="owner_name">Owner name</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="number" name="number" required placeholder="Vehicle number" value="<?php echo $vehicle['number']; ?>">
                      <label for="number">Vehicle number</label>
                    </div>
                  </div>


                  <div class="col-md-4">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="date" name="date" required placeholder="Date" value="<?php echo $vehicle['date']; ?>">
                    <label for="name">Date</label>
                  </div>
                </div>
                    
                  <div class="text-center">
                    <button type="submit" name="update" class="btn btn-dark">Update</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                  </div>
                  </form>
                  <!-- End floating Labels Form -->

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