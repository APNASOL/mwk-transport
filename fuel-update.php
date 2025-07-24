<?php include('Master/head.php');?>

<body>

    <!-- ======= Header ======= -->
    <?php include('Master/header.php');?>
    <!-- ======= Sidebar ======= -->
    <?php include('Master/aside.php');?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Update fuel</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Fuel</li>
                    <li class="breadcrumb-item active">Update fuel</li>
                </ol>
            </nav>
        
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
    $fuel_id = @$_GET['fuel_id'];
     
    $fuel_object = mysqli_query($conn,"SELECT * FROM fuel WHERE id = $fuel_id");
    $fuel = mysqli_fetch_array($fuel_object);
    

    $vehicles = mysqli_query($conn, "SELECT * FROM vehicles");
    $pumps = mysqli_query($conn, "SELECT * FROM pumps");
      
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

                    

                        <input type="hidden" name="id" value="<?php echo $fuel_id;?>">

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                            <input type="text" readonly class="form-control" value="<?php echo $current_vehicle_number;?>" >
                                     <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id;?>">
                                <label for="vehicle">Vehicle</label>
                            </div>
                        </div>
                        

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                            <input type="hidden" name="old_pump"
                                     value="<?php echo $fuel['pump_id'];?>">
                                <select class="form-select" id="pump_id" name="pump_id" aria-label="vehicles">
                                    <?php 
                                        while($pump = mysqli_fetch_array($pumps))
                                    { ?>

                                    <?php if($pump['id'] ==  $fuel['pump_id']){?>
                                    <option selected value="<?php echo $pump['id'];?>">
                                        <?php echo $pump['name'];?>
                                    </option>
                                    <?php }else{?>

                                     <option value="<?php echo $pump['id'];?>">
                                        <?php echo $pump['name'];?>
                                    </option> 

                                    <?php }}    ?>
                                    <option hidden>Please select pump from the list</option>
                                </select>
                                <label for="vehicle">Pump</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="date" name="date" placeholder="Date"
                                    value="<?php echo $fuel['date'];?>">
                                <label for="date">Date</label>
                            </div>
                        </div>

                        <!-- <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="litres" name="litres" placeholder="Litres"
                                    value="<?php //echo $fuel['litres'];?>">
                                <label for="litres">Litres</label>
                            </div>
                        </div> -->
 

                         

                        <div class="col-md-6">
                            <div class="form-floating">
                            <input type="hidden" name="old_balance"
                                     value="<?php echo $fuel['balance'];?>">
                            <input type="text" class="form-control" id="balance" name="bill"
                                     value="<?php echo $fuel['balance'];?>">
                                <label for="balnce">Total bill</label>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" name="update" class="btn btn-dark">Submit</button>
                            <button  type="submit" name="fuel_delete"
                            class="btn btn-danger" onclick="return confirmDelete()">Delete</button>
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

    <script>
       function confirmDelete() {
        return confirm("Are you sure you want to delete this record?");
    }
    </script>

</body>

</html>