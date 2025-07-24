<?php include('Master/head.php');

$conn = OpenCon();
 

?>

<body>

    <!-- ======= Header ======= -->
    <?php include('Master/header.php');?>
    <!-- ======= Sidebar ======= -->
    <?php include('Master/aside.php');?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <?php if($current_vehicle_id != "No vehicle added")
    {?>
        <section class="section dashboard">
            <?php
      if (@$_GET['successMessage']) {
          ?>
            <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
                <?php 
                if($current_vehicle_id)
                {
                  echo $_GET['successMessage'] ." ".$current_vehicle_number;  
                }else
                {
                  echo $_GET['successMessage'];  
                }?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>


            <?php
      }
    ?>
            <div class="row">

                <?php 

              $current_month = date('m');
              $current_year = date('Y'); 
              $QueryFortrips = mysqli_query($conn,"SELECT COUNT(id) AS total_trips,SUM(total_bill) AS total_sell FROM trips WHERE MONTH(end_date) = '$current_month' AND YEAR(end_date) = '$current_year' AND vehicle_id = '$current_vehicle_id'");

              $trips_count = mysqli_fetch_array($QueryFortrips);
              $trips_count_sell_show = $trips_count['total_sell'];
              $trips_count_trip_show = $trips_count['total_trips'];

              $total_profit = 0;
              


              ?>

                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Revenue Card -->
                        <div class="col-xxl-4 col-md-4">
                            <div class="card info-card revenue-card">


                                <div class="card-body">
                                    <h5 class="card-title">Revenue <span>| This Month</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
    <i class="bi bi-currency-dollar"></i>
</div>
                                        <div class="ps-3">
                                            <h6><?php echo $trips_count_sell_show ?? 0;?></h6>


                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Revenue Card -->

                        <!-- expense Card -->
                        <div class="col-xxl-4 col-md-4">
                            <?php   
$QueryFortripsExpenses = mysqli_query($conn,"SELECT SUM(expense) AS total_expenses FROM trips WHERE MONTH(end_date) = '$current_month' AND YEAR(end_date) = '$current_year' AND vehicle_id = '$current_vehicle_id'");

$trips_expenses = mysqli_fetch_array($QueryFortripsExpenses);
$trips_all_expenses_current_month = $trips_expenses['total_expenses']; 
 

                  $QueryForExp = mysqli_query($conn,"SELECT SUM(total_expenses) AS total_expense FROM vehicle_expenses WHERE MONTH(end_date) = '$current_month' AND YEAR(end_date) = '$current_year' AND vehicle_id = '$current_vehicle_id'");

                  $expenses_count = mysqli_fetch_array($QueryForExp);
                  $expenses_count_show = $trips_all_expenses_current_month + $expenses_count['total_expense'];
                ?>
                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">Expenses <span>| This Month</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info">
                                            <i class="bi bi-cart-x-fill text-white"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $expenses_count_show ?? 0;?></h6>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End expense Card -->

                        <!-- fuel Card -->
                        <div class="col-xxl-4 col-md-4">
                            <?php
                  $QueryForFuel = mysqli_query($conn,"SELECT SUM(balance) AS total_bill FROM fuel WHERE MONTH(date) = '$current_month' AND YEAR(date) = '$current_year' AND vehicle_id = '$current_vehicle_id'");

                  $fuel_count = mysqli_fetch_array($QueryForFuel);
                  $fuel_count_show = $fuel_count['total_bill'];

                  $total_profit = $trips_count_sell_show - $expenses_count_show - $fuel_count_show;
                ?>
                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">Fuel Expense <span>| This Month</span></h5>

                                    <div class="d-flex align-items-center ">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger">
                                            <i class="bi bi-droplet-fill text-white"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $fuel_count_show  ?? 0?></h6>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End fule Card -->

                        <!-- Trip Card -->
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card sales-card">

                                <div class="card-body">
                                    <h5 class="card-title">Trips <span>| This Month</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-truck"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $trips_count_trip_show ?? 0;?></h6>
                                            <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Trip Card -->


                        <!-- Profit Card -->
                        <div class="col-xxl-6 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">Profit <span>| This Month</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $total_profit;?></h6>
                                            <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Profit Card -->


                        <!-- Customers Card -->
                        <div class="col-xxl-4 col-xl-6">
                            <?php
                    $QueryForCustomer = mysqli_query($conn,"SELECT COUNT(id) AS total_customers FROM customers WHERE  vehicle_id = '$current_vehicle_id'");

                    $customer_array = mysqli_fetch_array($QueryForCustomer);
                  ?>
                            <div class="card info-card customers-card">

                                <div class="card-body">
                                    <h5 class="card-title">Customers </h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $customer_array['total_customers']?></h6>


                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div><!-- End Customers Card -->

                        <!-- pumps Card -->
                        <div class="col-xxl-4 col-xl-6">
                            <?php
                    $QueryForPumps = mysqli_query($conn,"SELECT COUNT(id) AS total_pumps FROM pumps WHERE  vehicle_id = '$current_vehicle_id'");

                    $pumps_array = mysqli_fetch_array($QueryForPumps);
                  ?>
                            <div class="card info-card customers-card">

                                <div class="card-body">
                                    <h5 class="card-title">Pumps </h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-post"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6><?php echo $pumps_array['total_pumps']?></h6>


                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div><!-- End pumps Card -->


                        <!-- code started for last 3 boxs -->
                        <div class="row">

                            <?php 

        $current_month = date('m');
        $current_year = date('Y');
        // echo $current_month. "  / ".$current_year. " / ".$current_vehicle_id; exit();
        $QueryForCashbook = mysqli_query($conn,"SELECT SUM(cash_out) AS cash_out,SUM(cash_in) AS cash_in FROM  cashbook WHERE vehicle_id = '$current_vehicle_id'");

        $cashbook_cash = mysqli_fetch_array($QueryForCashbook);
        $cash_in_show = $cashbook_cash['cash_in'];
        $cash_out_show = $cashbook_cash['cash_out'];

        $total_balance = $cash_in_show - $cash_out_show;
        


        ?>

                            <!-- Left side columns -->
                            <div class="col-lg-12">
                                <div class="row">

                                    <!-- Revenue Card -->
                                    <div class="col-xxl-4 col-md-4">
                                        <div class="card info-card revenue-card">


                                            <div class="card-body">
                                                <h5 class="card-title">Cash in</h5>

                                                <div class="d-flex align-items-center">

                                                    <div class="font-weight">
                                                        <h4><?php echo $cash_in_show ?? 0;?></h4>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- End Revenue Card -->

                                    <!-- expense Card -->
                                    <div class="col-xxl-4 col-md-4">


                                        <div class="card info-card revenue-card">

                                            <div class="card-body">
                                                <h5 class="card-title">Cash out</h5>

                                                <div class="d-flex align-items-center">

                                                    <div class="font-weight">
                                                        <h4><?php echo $cash_out_show ?? 0;?></h4>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- End expense Card -->

                                    <!-- fuel Card -->
                                    <div class="col-xxl-4 col-md-4">

                                        <div class="card info-card revenue-card">

                                            <div class="card-body">
                                                <h5 class="card-title">Balance </h5>

                                                <div class="d-flex align-items-center ">

                                                    <div class="font-weight">
                                                        <h4><?php echo $total_balance ?? 0?></h4>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- End fule Card -->
                                </div>
                            </div>
                        </div>
                        <!-- CODE ENDED for last boxs -->



                        <!-- Recent Sales -->
                        <div class="col-12">
                            <div class="card recent-sales">

                                <?php 
              $conn = OpenCon();
              $trips_array = mysqli_query($conn, "SELECT * FROM trips where vehicle_id = '$current_vehicle_id' AND YEAR(end_date) = '$current_year' AND MONTH(end_date) = '$current_month' AND vehicle_id = '$current_vehicle_id' ORDER BY end_date");
            ?>

                                <div class="card-body">
                                    <h5 class="card-title">Recent Trips <span>| This Month</span></h5>

                                    <table class="table table-borderless datatable" id="example">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Start date</th>
                                                <th scope="col">End date</th>
                                                <th scope="col">Total Bill</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  
                  $i = 1;
                  while($trip = mysqli_fetch_array($trips_array))
                  {
                    $vehicle_id = $trip['vehicle_id'];  
                    $customer_id = $trip['customer_id'];
                    $vehicle = mysqli_query($conn, "SELECT * FROM vehicles  WHERE id = $vehicle_id ");
                    
                    if(mysqli_num_rows($vehicle) > 0)
                    { ;
                      $vehicle_array = mysqli_fetch_array($vehicle); 
                    } 

                    $customer = mysqli_query($conn, "SELECT * FROM customers WHERE id = $customer_id " );
                    
                    if(mysqli_num_rows($customer) > 0)
                    {
                      $customer_array = mysqli_fetch_array($customer); 
                    }
                    
                ?>
                                            <tr>
                                                <th scope="row"><?php echo $i++;?></th>
                                                <td><?php echo $customer_array['name'];?></td>
                                                <td><?php echo $trip['load_type'];?></td>
                                                <td><?php echo $trip['start_date'];?></td>
                                                <td><?php echo $trip['end_date'];?></td>
                                                <td><?php echo $trip['total_bill'];?></td>

                                            </tr>
                                            <?php }?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div><!-- End Recent Sales -->


                    </div>
                </div><!-- End Left side columns -->



            </div>
        </section>
        <?php }else{?>
        <div class="card-title"> Please first add vehicle</div>
        <a class="btn btn-dark" href="vehicle-create.php">Add vehicle</a>
        <?php }?>
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

<style>
    .card-title {
        font-weight: 600;
        color: #000000; /* pure black */
    }

    .card-icon {
        background-color: #000000 !important;
        color: #ffffff !important;
        width: 48px;
        height: 48px;
    }

    .dashboard h6 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #000000;
    }

    .breadcrumb-item.active {
        font-weight: 600;
        color: #000000 !important;
    }

    .datatable thead th {
        background-color: #000000;
        color: #ffffff;
    }

    .alert-success {
        background-color: #000000 !important;
        color: #ffffff !important;
        font-weight: 600;
    }

    .btn-dark {
        background-color: #000000 !important;
        border-color: #000000 !important;
    }

    .btn-dark:hover {
        background-color: #222222 !important;
    }
</style>
