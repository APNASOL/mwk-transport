<?php include('Master/head.php');?>
<body>

    <!-- ======= Header ======= -->
    <?php include('Master/header.php');?>
    <!-- ======= Sidebar ======= -->
    <?php include('Master/aside.php');?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
 
            <h1>Fuel new entry </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Fuel</li>
                    <li class="breadcrumb-item active">Fuel entry create</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-end">
                <a class="btn btn-dark" href="fuel-index.php"><i class="bi bi-back"></i> Go to all fuel
                    entries
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
    ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Fuel Information</h5>
                    <?php 
                        $conn = OpenCon();
                        $pumps = mysqli_query($conn, "SELECT * FROM pumps where vehicle_id = '$current_vehicle_id'");
                    ?>
                    <!-- Floating Labels Form -->
                    <form class="row g-3" action="Controllers/FuelController.php" method="post">

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                            <input type="text" readonly class="form-control" value="<?php echo $current_vehicle_number;?>" >
                                     <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id;?>">
                                <label for="vehicle">Vehicle</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="pump" name="pump_id" aria-label="vehicles" required>
                                    <?php 
                                        while($pump = mysqli_fetch_array($pumps))
                                    {?>
                                    <option value="<?php echo $pump['id']?>"><?php echo $pump['name']?></option>
                                    <?php }?> 
                                    
                                </select>
                                <label for="vehicle">Pumps</label>
                            </div>
                        </div> 
                        
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="date" name="date"
                                    required placeholder="Date">
                                <label for="date">Date</label>
                            </div>
                        </div>

                         
                                <input type="hidden" class="form-control" id="litres" name="litres"
                                   value="<?php echo "0"?>">
                            
                            

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="balance" name="bill"
                                    required placeholder="Balance">
                                <label for="balnce">Total bill</label>
                            </div>
                        </div>

                        <!-- <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="paid" name="paid"
                                    required placeholder="Paid">
                                <label for="paid">Paid</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="due" name="due"
                                    required placeholder="Due">
                                <label for="due">Due</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="payment_type" aria-label="State" name="payment_type">
                                    <option selected value='online'>Online</option> 
                                    <option value="cash">Cash</option>
                                </select>
                                <label for="payment type">Payment type</label>
                            </div>
                        </div> -->

                        <div class="text-center">
                            <button type="submit" name="create" class="btn btn-dark">Submit</button>
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

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <?php include('Master/scripts.php');?>
    <!-- Scripts -->

</body>

</html>