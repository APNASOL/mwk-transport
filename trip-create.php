<?php include 'Master/head.php';?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>New Trip entry</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Trip</li>
                    <li class="breadcrumb-item active">Create new Trip</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-end">
                <a class="btn btn-dark" href="trip-index.php"><i class="bi bi-back"></i> Go to all Trips list
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
                    <h5 class="card-title">Trip Information</h5>
                    <?php
$conn = OpenCon();

$customers = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");
?>
                    <!-- Floating Labels Form -->
                    <form class="row g-3" action="Controllers/TripsController.php" method="post">

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                            <input type="text" readonly class="form-control" value="<?php echo $current_vehicle_number; ?>" >
                                     <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id; ?>">
                                <label for="vehicle">Vehicle</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="customer" name="customer_id" aria-label="customers">
                                    <?php
while ($customer = mysqli_fetch_array($customers)) {?>
                                    <option value="<?php echo $customer['id'] ?>"><?php echo $customer['name'] ?></option>
                                    <?php }?>
                                    <option Selected hidden>Please select customer from the list</option>
                                </select>
                                <label for="vehicle">Customers</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="load_type" name="load_type" aria-label="vehicles">

                                    <option Selected hidden>
                                        Please select load type
                                    </option>
                                    <option Selected value="custom">Custom</option>
                                    <option Selected value="coal">Coal</option>
                                </select>
                                <label for="vehicle">Load type</label>
                            </div>
                        </div>


                        <div class="col-md-6" id="custom_type_block">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="custom_type" name="custom_type"
                                    placeholder="Custome type">
                                <label for="balnce">Custom Type</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    placeholder="Date">
                                <label for="start_date">Start date</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    placeholder="Date">
                                <label for="end_date">End date</label>
                            </div>
                        </div>

                        <div class="col-md-6" id="weight_block">
                            <div class="form-floating">
                                <input type="number" step="0.001" class="form-control" id="weight" name="weight"
                                    placeholder="Weight">
                                <label for="balnce">Weight</label>
                            </div>
                        </div>



                        <div class="col-md-6" id="price_per_ton_block">
                            <div class="form-floating">
                                <input type="number" step="0.001" class="form-control" id="price_per_ton" name="price_per_ton"
                                    placeholder="Price per ton">
                                <label for="balnce">Price per ton</label>
                            </div>
                        </div>

                        <div class="col-md-6" id="main_div_price">
                            <div class="form-floating">
                                <input type="number" step="0.001" readonly class="form-control" id="price" name="price"
                                    placeholder="Price">
                                <label for="balnce">Price</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="0.001" class="form-control" id="expense" name="expense"
                                    placeholder="Expense">
                                <label for="balnce">Expense</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" step="0.001" readonly class="form-control" id="total_bill" name="total_bill"
                                    placeholder="Total" required>
                                <label for="total_bill">Total</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="payment_status" name="payment_status" aria-label="payment_status">

                                    <option Selected hidden>
                                        Select status
                                    </option>
                                    <option Selected value="received">Received</option>
                                    <option Selected value="due">Due</option>
                                </select>
                                <label for="payment_status">Select Payment status</label>
                            </div>
                        </div>




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
    <?php include 'Master/footer.php';?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <?php include 'Master/scripts.php';?>
    <!-- Scripts -->

</body>
    <script>

        $(document).ready(function(){
            $("#custom_type_block").hide();
            $("#price_per_ton_block").show();

            // the below event is for price - expense =  total <-- change in price
            $("#price").change(function(){
                var price = $('#price').val();
                var expense = $('#expense').val();
                var total = parseFloat(price) + parseFloat(expense);
                $('#total_bill').val(total);
            });

            // the below event is for price - expense =  total <-- change in expense
            $("#expense").change(function(){
                var price = $('#price').val();
                var expense = $('#expense').val();
                var total = parseFloat(price) + parseFloat(expense);
                $('#total_bill').val(total);
            });

            // Changing load type to hide or show the fields realted to load type
            $("#load_type").change(function(){

                var load_type = $('#load_type').val();
                if(load_type == 'custom')
                {
                    // var price = $('#price').val();
                    $("#custom_type_block").show();
                    $("#weight_block").hide();
                    $("#main_div_price").hide();
                    $("#price_per_ton_block").hide();
                    $("#price").removeAttr("readonly");
                    $("#total_bill").removeAttr("readonly");
                }
                if(load_type == 'coal')
                {
                    // var price = $('#price').val();
                    $("#weight_block").show();
                    $("#custom_type_block").hide();
                    $("#main_div_price").show();
                    $("#price_per_ton_block").show();
                    $("#price").show();
                    $("#price").attr("readonly","readonly");
                    $("#total_price").attr("readonly","readonly");
                }
            });

            $("#price_per_ton").change(function(){
                var price_per_ton = $('#price_per_ton').val();
                var weight = $('#weight').val();

                var total = parseFloat(price_per_ton) * parseFloat(weight);
                  $('#price').val(parseFloat(total));

            });



        });

    </script>
</html>