<?php include 'Master/head.php';?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Vehicles Expenses Transactions</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Expenses transactions</li>
                    <li class="breadcrumb-item active">Index</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-end">
                <a class="btn btn-dark" href="vehicle-create.php"><i class="bi bi-plus-lg"></i> Add new vehicle
                </a>
            </div>
        </div><!-- End Page Title -->

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
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Vehicle Expenses Transaction</h5>
                    <hr>
                    <!-- Floating Labels Form -->
                    
                    <form class="row g-3 mt-2" action="Controllers/VehicleController.php" method="post">
                            <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id;?>">
                      <div class="row">
                        <div class="col-md-3" id="expense_default_section">
                            <label for="expense_name">Common Expenses</label>
                            <select name="default_expense_name" class="form-control" id="default_expense_name">
                                <?php 
                                            $common_expenses = mysqli_query($conn,"SELECT * FROM common_expenses_types");
                                            while($expense = mysqli_fetch_array($common_expenses))
                                            {
                                        ?>
                                <option value="<?php echo $expense['name'];?>"><?php echo $expense['name'];?>
                                </option>
                                <?php }?>
                                <option value="" selected hidden> Please select expense </option>
                            </select>
                        </div>
                        <div class="col-md-3" id="expense_custom_section">
                            <label for="expense_name">Custom Expense name</label>
                            <input type="text" class="form-control" id="expense_name" name="expense_name"
                                placeholder="Expense expense name" >
                        </div>
                        <div class="col-md-3">
                            <label for="amount">Amount</label>
                            <input type="text" required class="form-control" id="amount" name="amount"
                                placeholder="Amount">
                        </div>
                        <div class="col-md-2 text-center mt-4">
                            <button type="submit" name="temprory_vehicle_expense_storing"
                                class="btn btn-dark btn-sm">Add</button>

                                <button type="reset" id="reset_id" class="btn btn-warning  btn-sm">Change</button>

                        </div>
                         
                      </div>
                    </form>
                </div>
                <hr>
                <!-- Floating Labels Form -->
                <?php
                $conn = OpenCon();
                $expenses = mysqli_query($conn, "SELECT * FROM temprory_vehicle_expense_storage WHERE vehicle_id = '$current_vehicle_id'");

                $total = 0;
            ?>
                <!-- Table with stripped rows -->
                <table class="table table-striped">
                    <thead>

                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Action</th>
                        </tr>

                    </thead>
                    <tbody>
                        <form class="row g-3 mt-2" action="Controllers/VehicleController.php" method="post">
                            <?php
$i = 1;
while ($expense = mysqli_fetch_array($expenses)) {
    ?>
                            <tr>
                                <th scope="row"><?php echo $i++; ?></th>
                                <td>
                                    <input type="hidden" name="expense_names[]"
                                        value="<?php echo $expense['expense_name']; ?>">
                                    <?php echo $expense['expense_name']; ?>
                                </td>

                                <?php $total = $total + $expense['amount'];?>

                                <td>
                                    <input type="hidden" name="amounts[]" value="<?php echo $expense['amount']; ?>">
                                    <?php echo $expense['amount']; ?>
                                </td>
                                <td>

                                    <a href="Controllers/VehicleController.php?single_expense_id=<?php echo $expense['id']; ?>&process=single_expens_delete"
                                        type="button" class="btn btn-sm fs-6" title="Delete"><i
                                            class="bi bi-trash"></i></a>
                                </td>
                            </tr>

                            <?php }?>
                            <tr class="p-5">

                                <td>
                                    <input type="date" required class="form-control" name="date">
                                    <input type="hidden" value="<?php echo $current_vehicle_id;?>" name="vehicle_id">
                                </td>
                                <td>Total</td>
                                <td>
                                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                                    <?php echo $total;?>
                                </td>


                                <td>
                                    <button type="submit" name="vehicle_expense_transaction"
                                        class="btn btn-dark btn-sm">Save</button>
                                </td>

                            </tr>
                        </form>
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
    <script>
        $(document).ready(function () {

            
            $("#reset_id").click(function () {
                $("#expense_default_section").show();
                $("#expense_custom_section").hide();
            });
            
            $("#reset_id").click(function () {
                $("#expense_default_section").toggle();
                $("#expense_custom_section").toggle();
            });
            $("#default_expense_name").change(function () {
                $("#expense_custom_section").hide();
                $("#expense_default_section").show();
            });
            $("#expense_name").focus(function () {
                $("#expense_default_section").hide();
                
            });


        });
    </script>
</body>

</html>