<?php include('Master/head.php');?>

<body>

    <!-- ======= Header ======= -->
    <?php include('Master/header.php');?>
    <!-- ======= Sidebar ======= -->
    <?php include('Master/aside.php');?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Update Cash Entry</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Cashflow</li>
                    <li class="breadcrumb-item active">Update Cash</li>
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
    $id = @$_GET['id'];
     
    $entry = mysqli_query($conn,"SELECT * FROM cashflow WHERE id = $id");


    $entry_detail = mysqli_fetch_array($entry);
    $id = $entry_detail["id"];
    $customer_id = $entry_detail["consumer"];
    $vehicle_id = $entry_detail["vehicle_id"];
    $date = $entry_detail["date"];
    $cash_received = $entry_detail["cash_in"];

    $vehicles = mysqli_query($conn, "SELECT * FROM vehicles");
    $customers = mysqli_query($conn, "SELECT * FROM customers");
    
    ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Update Cashflow</h5>

                    <!-- Floating Labels Form -->
                    <form class="row g-3" action="Controllers/TripsController.php" method="post" onsubmit="handleSubmit(event, this)">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="old_customer_id" value="<?php echo $customer_id; ?>">
                        <input type="hidden" name="old_vehicle_id" value="<?php echo $vehicle_id; ?>">
                        <input type="hidden" name="old_date" value="<?php echo $date; ?>">
                        <input type="hidden" name="old_amount" value="<?php echo $cash_received; ?>">

                        <div class="col-md-6">
                            <div class="form-floating">
                            <select class="form-select" id="customer" name="customer_id" aria-label="customers">
                                    <?php 
                                        while($customer = mysqli_fetch_array($customers))
                                    { ?>

                                    <?php if($customer['id'] == $customer_id){?>
                                    <option selected value="<?php echo $customer['id'];?>">
                                        <?php echo $customer['name'];?>
                                    </option>
                                    <?php }else{?>

                                    <option value="<?php echo $customer['id'];?>">
                                        <?php echo $customer['name'];?>
                                    </option>

                                    <?php }}    ?>
                                    <option hidden>Please select customer from the list</option>
                                </select>
                                <label for="pump_name">Customer</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                            <select class="form-select" id="vehicle" name="vehicle_id" aria-label="customers">
                                    <?php 
                                        while($vehicle = mysqli_fetch_array($vehicles))
                                    { ?>

                                    <?php if($vehicle['id'] == $vehicle_id){?>
                                    <option selected value="<?php echo $vehicle['id'];?>">
                                        <?php echo $vehicle['number'];?>
                                    </option>
                                    <?php }else{?>

                                    <option value="<?php echo $vehicle['id'];?>">
                                        <?php echo $vehicle['number'];?>
                                    </option>

                                    <?php }}    ?>
                                    <option hidden>Please select customer from the list</option>
                                </select>
                                <label for="pump_name">Vehicle</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                            <input type="date" class="form-control" id="date" name="date" required placeholder="Date" value="<?php echo $date; ?>">
                                <label for="address">Date</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="new_balance" name="new_balance"
                                    required placeholder="Balance" value="<?php echo $cash_received; ?>">
                                <label for="balnce">Cash</label>
                            </div>
                        </div>

                

                        <div class="text-center">
                            <input type="hidden" name="update_cash" value="1">
                            <button type="submit" class="btn btn-dark">Update</button>
                            <button type="submit" name="delete_cash" class="btn btn-danger" onclick="return confirmDelete()">Delete</button>
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

function confirmDelete() {
        return confirm("Are you sure you want to delete this record?");
    }

    </script>

</body>

</html>