<?php include('Master/head.php');?>

<body>

    <!-- ======= Header ======= -->
    <?php include('Master/header.php');?>
    <!-- ======= Sidebar ======= -->
    <?php include('Master/aside.php');?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Update pump</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Pump</li>
                    <li class="breadcrumb-item active">Update pump</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-end">
                <a class="btn btn-dark" href="pages-blank-index.php"><i class="bi bi-back"></i> Go to all pumps
                </a>
            </div>
        </div><!-- End Page Title -->

        <section class="section">
            <?php
      if (@$_GET['errorMessage']) {
          ?>
            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                <?php echo $_GET['errorMessage']; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>


            <?php
      }
    //   here going to fetch the record according to pump_id
    $conn = OpenCon();
    $pump_id = @$_GET['pump_id'];
     
    $vehicle_object = mysqli_query($conn,"SELECT * FROM pumps WHERE id = $pump_id");
    $pumps_details  = mysqli_query($conn,"SELECT * FROM pumps_details WHERE pump_id = $pump_id AND note = 'Old due amount'");

    $pump_detail = mysqli_fetch_array($pumps_details);
    $pump_details_debit = $pump_detail['debit'];
    $pump_details_current_recod_id = $pump_detail['id'];
    $pump = mysqli_fetch_array($vehicle_object);
    ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Update pump</h5>

                    <!-- Floating Labels Form --> 
                    <form class="row g-3" action="Controllers/PumpController.php" method="post" onsubmit="handleSubmit(event, this)">
                        <input type="hidden" name="pump_id" value="<?php echo $pump['id']; ?>">

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="pump_name" name="pump_name"
                                    required placeholder="Pump name" value="<?php echo $pump['name']; ?>">
                                <label for="pump_name">Pump name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" readonly class="form-control" id="balance" name="balance"
                                    required placeholder="Pump name" value="<?php echo $pump['balance']; ?>">
                                <label for="pump_name">Current Balance</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="address" name="address"
                                    required placeholder="Pump address" value="<?php echo $pump['address']; ?>">
                                <label for="address">Pump address</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="hidden" name="old_balance"
                                  value="<?php echo $pump_details_debit; ?>">
                                <input type="hidden" name="pump_details_table_id"
                                  value="<?php echo $pump_details_current_recod_id; ?>">
                                <input type="text" class="form-control" id="new_balance" name="new_balance"
                                    required placeholder="Balance" value="<?php echo $pump_details_debit; ?>">
                                <label for="balnce">Old Balance</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="date" name="date" required placeholder="Date" value="<?php echo $pump['date']; ?>">
                                <label for="name">Date</label>
                            </div>
                            </div>

                        <div class="text-center">
                            <input type="hidden" name="update" value="1">
                            <button type="submit" class="btn btn-dark">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form><!-- End floating Labels Form -->
                    <!-- End floating Labels Form -->

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include('Master/footer.php');?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <?php include('Master/scripts.php');?>
    <!-- Scripts -->
    
    <script>
        
        function handleSubmit(event, form) {
    //event.preventDefault(); // Prevent the form from submitting immediately

        var button = form.querySelector("button[type='submit']");
        button.disabled = true;
        button.innerText = 'Loading...'; // Optional: Change button text to indicate loading

    // Simulate an async operation (e.g., an API call or some processing)
    
    }  
        
    </script>

</body>

</html>