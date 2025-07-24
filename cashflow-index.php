<?php include 'Master/head.php';?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php';?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php';?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>Cash Flow</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Cashflow</li>
          <li class="breadcrumb-item active">Index</li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
        <a class="btn btn-dark" href="cashflow-create.php"><i class="bi bi-plus-lg"></i> Add Cash Entries
        </a>
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      <?php
if (@$_GET['successMessage']) {
    ?>
      <div class="alert alert-success bg-success text-light alert-dismissible fade show" role="alert">
        <?php echo $_GET['successMessage']; ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <?php
}
?>
      <div class="card">
        <div class="card-body">
          <br>
          
          <div class="row text-center ">
        <?php
$conn = OpenCon();
$cashflow_totals = mysqli_query($conn, "SELECT COUNT(id) AS total_entries,SUM(cash_in) AS value_sum FROM cashflow order by date");
        $row1 = mysqli_fetch_array($cashflow_totals);
        $total_due = $row1["value_sum"];
        $total_entries = $row1["total_entries"];
?>
            <div class="col">
                <div class="card bg-white text-warning mb-3" style="">

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total Entries</b></h5><hr>
                <h3 class="card-title"><?php echo $total_entries; ?></h3>

                </div>
                </div>
            </div>

            <div class="col">
                <div class="card bg-white text-warning mb-3" style="">

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total Cash Received</b></h5><hr>
                <h3 class="card-title"><b><?php echo $total_due; ?></b></h3>
                </div>
                </div>
            </div>

        </div>

          <?php
$conn = OpenCon();
$cashs = mysqli_query($conn, "SELECT * FROM cashflow order by date");
?>
          <!-- Table with stripped rows -->
          <table class="table table-striped datatable" id="example">
            <thead>

              <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col">Customer</th>
                <th scope="col">Vehicle</th>
                <th scope="col">Cash Received</th>
                
                <th scope="col">Balance</th>
                <th scope="col">Action</th>
              </tr>

            </thead>
            <tbody>
            <?php
$i = 1;
$balance = 0;
while ($cash = mysqli_fetch_array($cashs)) {

    $id = $cash["id"];
    $customer_id = $cash["consumer"];
    $vehicle_id = $cash["vehicle_id"];
    $date = $cash["date"];
    $cash_received = $cash["cash_in"];
    $balance = $balance + $cash_received;

    $vehicle = mysqli_query($conn, "SELECT * FROM vehicles  WHERE id = $vehicle_id");

    if (mysqli_num_rows($vehicle) > 0) {;
        $vehicle_array = mysqli_fetch_array($vehicle);
    }

    $customer = mysqli_query($conn, "SELECT * FROM customers WHERE id = $customer_id");

    if (mysqli_num_rows($customer) > 0) {
        $customer_array = mysqli_fetch_array($customer);
    }

    ?>
                <tr>
                  <th scope="row"><?php echo $i++; ?></th>
                  
                  <td><?php echo $date; ?></td>
                  <td><?php echo $customer_array['name']; ?></td>
                  <td><?php echo $vehicle_array['number']; ?></td>
                  
                  <td><?php echo $cash_received ?></td>
                  <td><?php echo $balance ?></td>
                  <td>

                    <a href="cashflow-update.php?id=<?php echo $id;?>" type="button"
                        class="btn btn-sm fs-6"
                        title="Edit"><i class="bi bi-pencil-square"></i></a>
                  </td>
                </tr>
              <?php }?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include 'Master/footer.php';?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Scripts -->
  <?php include 'Master/scripts.php';?>
  <!-- Scripts -->

</body>

</html>