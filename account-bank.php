<?php include 'Master/head.php';?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php';?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php';?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>Bank Account</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>

          <li class="breadcrumb-item active">Bank Account</li>
        </ol>
      </nav>

      <?php
$conn = OpenCon();
$vehicles = mysqli_query($conn, "SELECT * FROM vehicles");
$customers = mysqli_query($conn, "SELECT * FROM customers");
?>

      <div class="card">
        <div class="card-body">
            <!-- Floating Labels Form -->
            <form class="row g-3 mt-5" action="Controllers/BankController.php" method="post">
                <div class="col-md-3">

                    <div class="form-floating mb-3">
                         <select class="form-select" id="customer" name="customer_id" aria-label="customers">
                            <?php
while ($customer = mysqli_fetch_array($customers)) {?>
                            <option value="<?php echo $customer['id'] ?>"><?php echo $customer['name'] ?></option>
                            <?php }?>
                            <option Selected hidden>Select Customer</option>
                         </select>
                         <label for="vehicle">Customers</label>
                     </div>

                </div>

                <div class="col-md-3">
                        <div class="form-floating">
                             <input type="text" class="form-control" id="amount"name="amount"
                                 placeholder="Amount">
                            <label for="amount">Amount</label>
                        </div>
                </div>

                <div class="col-md-3">
                <div class="form-floating">
                  <input type="text" class="form-control" id="bank" name="bank" placeholder="Bank">
                  <label for="bank">Bank</label>
                </div>
              </div>

                <div class="col-md-3">
                <div class="form-floating">
                  <input type="text" class="form-control" id="reference_no" name="reference_no" placeholder="Reference No.">
                  <label for="reference_no">Reference no</label>
                </div>
              </div>



                <div class="col-md-3">
                        <div class="form-floating">
                             <input type="date" class="form-control" id="date"name="date"
                                 placeholder="date">
                            <label for="date">Date</label>
                        </div>
                </div>

                <div class="col-md-3">

                    <div class="text-center">
                        <button type="submit" name="create_customer_transaction" class="btn btn-dark  btn-lg">Submit</button>

                    </div>

                </div>

            </form>
            <!-- Floating Labels Form -->

        </div>
      </div>

      <div class="card">
        <div class="card-body mt-3">
          <div class="row">
              <div class="d-flex justify-content-between ">



                <div class="col-md-3 d-grid gap-2">
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cashWithdrawal">
                    Cash Withdrawal
                  </button>
                </div>


                <div class="col-md-3 d-grid gap-2">
                  <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#returnAmount">
                    Return Amount
                  </button>
                </div>

            </div>
          </div>

        </div>
      </div>

    </div><!-- End Page Title -->

    <!-- Modal Cash widh draw -->
    <div class="modal fade" id="cashWithdrawal" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Cash Withdrawal</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    <form class="row g-3 mt-5" action="Controllers/BankController.php" method="post">

                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
                        <label for="amount">Amount</label>
                      </div>
                    </div>

                    

                    <div class="col-md-12">
                      <div class="form-floating">
                        <input type="date" class="form-control" id="date" name="date" placeholder="date">
                        <label for="date">Date</label>
                      </div>
                    </div>


                    <div class="col-md-12">
                <div class="form-floating">
                  <input type="text" class="form-control" id="bank" name="bank" placeholder="Bank">
                  <label for="bank">Bank</label>
                </div>
              </div>

                <div class="col-md-12">
                <div class="form-floating">
                  <input type="text" class="form-control" id="reference_no" name="reference_no" placeholder="Reference No.">
                  <label for="reference_no">Reference no</label>
                </div>
              </div>

                    <div class="modal-footer">
                      <button type="submit" name="cash_withdrawal"
                    class="btn btn-dark  btn-lg">Submit</button>
                    </div>
                    </form>
                    </div>
                  </div>
                </div>
              </div>

      </div>
    <!-- end modals -->
    <!--Return cash -->
    <div class="modal fade" id="returnAmount" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Return Cash</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form class="row g-3" action="Controllers/BankController.php" method="post">

<div class="col-md-12">
    <div class="form-floating mb-3">
        <select class="form-select" id="customer" name="customer_id" aria-label="customers">
            <?php 
$customers = mysqli_query($conn, "SELECT * FROM customers");
while ($customer = mysqli_fetch_array($customers)) {?>
            <option value="<?php echo $customer['id'] ?>"><?php echo $customer['name'] ?>
            </option>
            <?php }?>
            <option Selected hidden>Select Customer</option>
        </select>
        <label for="vehicle">Customers</label>
    </div>
</div>

<div class="col-md-12">
    <div class="form-floating">
        <input type="text" class="form-control" id="amount" name="amount"
            placeholder="Amount">
        <label for="amount">Amount</label>
    </div>
</div>

<div class="col-md-12">
    <div class="form-floating">
        <input type="date" class="form-control" id="date" name="date" placeholder="date">
        <label for="date">Date</label>
    </div>
</div>

<div class="col-md-12">
                <div class="form-floating">
                  <input type="text" class="form-control" id="bank" name="bank" placeholder="Bank">
                  <label for="bank">Bank</label>
                </div>
              </div>

                <div class="col-md-12">
                <div class="form-floating">
                  <input type="text" class="form-control" id="reference_no" name="reference_no" placeholder="Reference No.">
                  <label for="reference_no">Reference no</label>
                </div>
              </div>
<div class="col-md-12">

    <div class="text-center">
        <button type="submit" name="return_cash"
            class="btn btn-dark  btn-lg">Submit</button>

    </div>

</div>

</form>
                    </div>
                     
                  </div>
                </div>
              </div>

      </div>
    <!-- end modals -->

     <section class="section">
            <?php
if (@$_GET['successMessage']) {
    ?>
                          <div class="alert alert-success bg-success text-light alert-dismissible fade show" role="alert">
                              <?php echo $_GET['successMessage']; ?>
                              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                  aria-label="Close"></button>
                          </div>

                          <?php
}
?>


            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Cash Book</h5>

                            <?php
$conn = OpenCon();
$cashbooks = mysqli_query($conn, "SELECT * FROM bank_account");
?>
                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Consume from</th>
                                        <th scope="col">Operation for</th>
                                        <th scope="col">Bank</th>
                                        <th scope="col">Reference No.</th>
                                        <th scope="col">Cash In</th>
                                        <th scope="col">Cash Out</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Notes</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$i = 1;
while ($bank_account = mysqli_fetch_array($cashbooks)) {
    $operation_for = $bank_account['operation_for'];

    ?>

                                    <tr>
                                        <th scope="row"><?php echo $i++; ?></th>


                                        <?php
if ($operation_for == 'Given to vehicle' || $operation_for == 'Recieved from vehicle') {
        $vehicle_id = $bank_account['consume_id'];
        $vehicle = mysqli_query($conn, "SELECT * FROM vehicles  WHERE id = $vehicle_id");

        if (mysqli_num_rows($vehicle) > 0) {;
            $vehicle_array = mysqli_fetch_array($vehicle);
            ?>
                                                              <td> <?php echo 'Vehicle # ' . $vehicle_array['number']; ?> </td>
                                                              <?php
}

        ?>


                                        <?php
} else if ($operation_for == 'customer' || $operation_for == 'Cash return') {
        $customer_id = $bank_account['consume_id'];
        $customer = mysqli_query($conn, "SELECT * FROM customers  WHERE id = $customer_id");

        if (mysqli_num_rows($customer) > 0) {;
            $customer_array = mysqli_fetch_array($customer);
            ?>
                                                                      <td><?php echo $customer_array['name']; ?></td>
                                                                      <?php
}

    } else if ($operation_for == 'Payed to pump') {
        $pump_id = $bank_account['consume_id'];
        $pump = mysqli_query($conn, "SELECT * FROM pumps  WHERE id = $pump_id");

        if (mysqli_num_rows($pump) > 0) {;
            $pump_array = mysqli_fetch_array($pump);
            ?>
                                                                    <td><?php echo $pump_array['name']; ?></td>
                                                                    <?php
}

    }
    ?>


                                        <td><?php echo $bank_account['operation_for']; ?></td>
                                        <td><?php echo $bank_account['bank']; ?></td>
                                        <td><?php echo $bank_account['reference_no']; ?></td>
                                        <td>
                                            <?php echo $bank_account['cash_in']; ?>
                                        </td>
                                        <td>
                                            <?php echo $bank_account['cash_out']; ?>
                                        </td>
                                        <td><?php echo $bank_account['date']; ?></td>
                                        <td><?php echo $bank_account['notes']; ?></td>

                                        <td>

                                            <a href="Controllers/CashBookController.php?cashbook_id=<?php echo $bank_account['id']; ?>&process=edit"
                                                type="button" class="btn btn-sm fs-6" title="Edit"><i
                                                    class="bi bi-pencil-square"></i></a>
                                            <a href="Controllers/CashBookController.php?cashbook_id=<?php echo $bank_account['id']; ?>&process=delete"
                                                type="button" class="btn btn-sm fs-6" title="Delete"><i
                                                    class="bi bi-trash"></i></a>

                                        </td>


                                    </tr>

                                    <?php }?>

                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

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

  <!-- custom style -->
  <style>
    .gap-2
    {
      margin-left:2px;
    }
  </style>
</body>

</html>