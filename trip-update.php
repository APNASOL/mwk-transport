<?php include 'Master/head.php';?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Update trip</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Trip</li>
                    <li class="breadcrumb-item active">Update trip</li>
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
//   here going to fetch the record according to pump_id
$conn = OpenCon();
$trip_id = @$_GET['trip_id'];

$trip_object = mysqli_query($conn, "SELECT * FROM trips WHERE id = $trip_id");
$trip = mysqli_fetch_array($trip_object);

$vehicles = mysqli_query($conn, "SELECT * FROM vehicles");
$customers = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");

?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Trip Information</h5>
                    <!-- Floating Labels Form -->
                    <form class="row g-3" action="Controllers/TripsController.php" method="post" onsubmit="handleSubmit(event, this)">

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="hidden" value="<?php echo $trip['id'];?>" name="id">
                                <input type="text" readonly class="form-control"
                                    value="<?php echo $current_vehicle_number; ?>">
                                <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id; ?>">
                                <label for="vehicle">Vehicle</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <input type="hidden" value="<?php echo $trip['customer_id'];?>" name="old_customer_id">
                         
                            <div class="form-floating mb-3">
                                <select class="form-select" id="customer" name="customer_id" aria-label="customers">
                                    <?php 
                                        while($customer = mysqli_fetch_array($customers))
                                    { ?>

                                    <?php if($customer['id'] ==  $trip['customer_id']){?>
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
                                <label for="vehicle">Customer</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <!-- <select class="form-select" id="load_type" name="load_type" aria-label="load_type">
                                     
                                    <option  Selected hidden>
                                        Please select load type
                                    </option>
                                    <option <?php if($trip['load_type']=="coal") echo 'selected="selected"'; ?>  value="coal">Coal</option>
                                    <option <?php if($trip['load_type']!="coal") echo 'selected="selected"'; ?>  value="custom">Custom</option>
                                </select> -->
                                <input type="text" readonly value="<?php echo $trip['load_type']?>" class="form-control">
                                <label for="vehicle">Load type</label>
                            </div>
                        </div> 


                        <div class="col-md-6" id="custom_type_block">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="custom_type" name="custom_type"
                                    placeholder="Custome type" value="<?php echo $trip['load_type'];?>">
                                <label for="balnce">Custom Type</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="start_date" name="start_date"
                                    placeholder="Date" value="<?php echo $trip['start_date'];?>">
                                <label for="start_date">Start date</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="end_date" name="end_date"
                                    placeholder="Date" value="<?php echo $trip['end_date'];?>">
                                <label for="end_date" >End date</label>
                            </div>
                        </div>

                        <div class="col-md-6" id="weight_block">
                            <div class="form-floating">
                            <input type="hidden" value="<?php echo $trip['weight'];?>" name="old_weight">
                                <input type="number" step="0.001" class="form-control" id="weight" name="weight"
                                    placeholder="Weight" value="<?php echo $trip['weight'];?>">
                                <label for="balnce">Weight</label>
                            </div>
                        </div>

                        <div class="col-md-6" id="price_per_ton_block">
                            <div class="form-floating">
                            <input type="hidden" value="<?php echo $trip['price_per_ton'];?>" name="old_price_per_ton">
                                <input type="number" step="0.001" class="form-control" id="price_per_ton"
                                    name="price_per_ton" placeholder="Price per ton" value="<?php echo $trip['price_per_ton'];?>">
                                <label for="balnce" >Price per ton</label>
                            </div>
                        </div>

                        <div class="col-md-6" id="main_div_price">
                            <div class="form-floating">
                            <input type="hidden" value="<?php echo $trip['price'];?>" name="old_price">
                                <input type="number" step="0.001" readonly class="form-control" id="price" name="price"
                                    placeholder="Price" value="<?php echo $trip['price'];?>">
                                <label for="balnce">Price</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                            <input type="hidden" value="<?php echo $trip['expense'];?>" name="old_expense">
                                <input type="number" step="0.001" class="form-control" id="expense" name="expense"
                                    placeholder="Expense" value="<?php echo $trip['expense'];?>">
                                <label for="balnce" >Expense</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                            <input type="hidden" value="<?php echo $trip['total_bill'];?>" name="old_total_bill">
                                <input type="number" step="0.001" readonly class="form-control" id="total_bill"
                                    name="total_bill" placeholder="Total"  value="<?php echo $trip['total_bill'];?>" required>
                                <label for="total_bill">Total</label>
                            </div>
                        </div>

                        

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <!-- <select class="form-select" id="payment_status" name="payment_status" aria-label="payment_status">
                                     
                                    <option  Selected hidden>
                                        Please select load type
                                    </option>
                                    <option <?php if($trip['payment_status']=="received") echo 'selected="selected"'; ?>  value="received">Received</option>
                                    <option <?php if($trip['payment_status']!="received") echo 'selected="selected"'; ?>  value="due">Due</option>
                                </select> -->
                                <input type="text" class="form-control" readonly name="payment_status" value="<?php echo $trip['payment_status'];?>">
                                <label for="vehicle">Status</label>
                            </div>
                        </div>




                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <input type="hidden" name="update"  value="Update">
                            <button type="submit" class="btn btn-dark  btn-block form-control">Update</button>
                        </div>
                    </form>
                    
                    <form class="row g-3" action="Controllers/TripsController.php" method="post" onsubmit="handleSubmit(event, this)">
                        
                        <input type="hidden" value="<?php echo $trip['id'];?>" name="id">
                        <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id; ?>">
                        <input type="hidden" value="<?php echo $trip['customer_id'];?>" name="old_customer_id">
                        <input type="hidden" value="<?php echo $trip['total_bill'];?>" name="old_total_bill">
                        <input type="hidden" value="<?php echo $trip['expense'];?>" name="old_expense">
                        <input type="hidden" name="payment_status" value="<?php echo $trip['payment_status'];?>">
                        
                     
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                          <input  type="hidden" name="delete" value="Delete">
                          <button type="submit" class="btn btn-danger btn-block form-control" onclick="return confirmDelete()">Delete</button> 
                    </div>
                            
                </form>
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

    $(document).ready(function () {
        $("#custom_type_block").hide();
        $("#price_per_ton_block").show();

        // the below event is for price - expense =  total <-- change in price
        $("#price").change(function () {
            var price = $('#price').val();
            var expense = $('#expense').val();
            var total = parseFloat(price) + parseFloat(expense);
            $('#total_bill').val(total);
        });

        // the below event is for price - expense =  total <-- change in expense
        $("#expense").change(function () {
            var price = $('#price').val();
            var expense = $('#expense').val();
            var total = parseFloat(price) + parseFloat(expense);
            $('#total_bill').val(total);
        });

        // Changing load type to hide or show the fields realted to load type
        $("#load_type").change(function () {

            var load_type = $('#load_type').val();
            if (load_type == 'custom') {
                // var price = $('#price').val();
                $("#custom_type_block").show();
                $("#weight_block").hide();
                $("#main_div_price").hide();
                $("#price_per_ton_block").hide();
                $("#price").removeAttr("readonly");
                $("#total_bill").removeAttr("readonly");
            }
            if (load_type == 'coal') {
                // var price = $('#price').val();
                $("#weight_block").show();
                $("#custom_type_block").hide();
                $("#main_div_price").show();
                $("#price_per_ton_block").show();
                $("#price").show();
                $("#price").attr("readonly", "readonly");
                $("#total_price").attr("readonly", "readonly");
            }
        });

        $("#price_per_ton").change(function () {
            var price_per_ton = $('#price_per_ton').val();
            var weight = $('#weight').val();

            var total = parseFloat(price_per_ton) * parseFloat(weight);
            $('#price').val(parseFloat(total));
            var price = $('#price').val();
            var expense = $('#expense').val();
            var totall = parseFloat(price) + parseFloat(expense);
            $('#total_bill').val(totall);

        });

        $("#weight").change(function () {
            var price_per_ton = $('#price_per_ton').val();
            var weight = $('#weight').val();

            var total = parseFloat(price_per_ton) * parseFloat(weight);
            $('#price').val(parseFloat(total));
            var price = $('#price').val();
            var expense = $('#expense').val();
            var totall = parseFloat(price) + parseFloat(expense);
            $('#total_bill').val(totall);

        });



    });
</script>

</html>