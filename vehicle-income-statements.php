<?php include 'Master/head.php';
$conn = OpenCon();
session_start();
$v_name = $_SESSION['current_vehicle_number'];
?>
<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Vehicle Income statements</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Vehicle</li>
                    <li class="breadcrumb-item active">Income statements</li>
                </ol>
            </nav>

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
                    <h5 class="card-title">Income statement</h5>
                    <?php
$yearsQuery = "SELECT * FROM years ORDER BY id";
$years = mysqli_query($conn, $yearsQuery);
$monthQuery = "SELECT * FROM months ORDER BY id";
$months = mysqli_query($conn, $monthQuery);

$current_year = date('Y');
$current_month = date('m');

?>

                <form action="Controllers/VehicleController.php" method="post">

                    <div class="row">
                        
                            <div class="col-md-1"></div>
                            <div class="col-md-5 card-title">Select year and month</div>
                            <div class="col-md-2">
                                <label>Select year</label>
                                <select name="year" class="form-control" required>
                                    <?php while ($year = mysqli_fetch_array($years)) {
    if ($_GET['year'] == $year['year'] || $current_year == $year['year']) {?>
                                    <option selected value="<?php echo $_GET['year'] ?? $current_year ?>">
                                        <?php echo $_GET['year'] ?? $current_year ?></option>

                                    <?php } else {?>
                                    <option value="<?php echo $year['year'] ?>"><?php echo $year['year'] ?></option>
                                    <?php }}?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Select month</label>
                                <select name="month" class="form-control" required>

                                    <?php while ($month = mysqli_fetch_array($months)) {
    if ($_GET['month'] == $month['code'] || $current_month == $month['code']) {?>
                                    <option selected value="<?php echo $month['code'] ?>"><?php echo $month['name'] ?>
                                    </option>

                                    <?php } else {?>
                                    <option value="<?php echo $month['code'] ?>"><?php echo $month['name'] ?></option>
                                    <?php }}?>

                                </select>
                            </div>
                            <div class="col-md-2">

                                <button type="submit" name="search_income_statement"
                                    class="btn btn-dark mt-4 btn-md">Search</button>


                            </div>
                       
                    </div>
                </form>


                    <?php

// $vehicle_id = $_GET['vehicle_id'];

// $trips = mysqli_query($conn, "SELECT count(trips.id) as total_trips, trips.end_date, SUM(trips.total_bill) as total_bill, SUM(vehicle_expenses.total_expenses) as total_expense FROM trips,vehicle_expenses WHERE trips.vehicle_id = vehicle_expenses.vehicle_id GROUP BY trips.end_date");

if (@$_GET['year'] || @$_GET['month']) {
    $year = $_GET['year'];
    $month = $_GET['month'];
    $trips = mysqli_query($conn, "SELECT count(id) as val, date FROM vehicle_income_operations where vehicle_id = '$current_vehicle_id' AND MONTH(date) = $month AND YEAR(date) = $year GROUP BY date order by date;");

} else {
    $year = date('Y');
    $month = date('m');
    $trips = mysqli_query($conn, "SELECT count(id) as val, date FROM vehicle_income_operations where vehicle_id = '$current_vehicle_id' AND MONTH(date) = $month AND YEAR(date) = $year GROUP BY date order by date;");
}

?>

                </div>

                <!-- Table with stripped rows -->
                <div class="card card-body table-responsive">
                    <table class="table table-striped" id="incomeStatment">
                        <thead>

                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Trips</th>
                                <th scope="col">Total balance</th>
                                <th scope="col">Trip Expenses</th>
                                <th scope="col">Expenses</th>
                                <th scope="col">Total Expenses</th>
                                <th scope="col">Profit</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
$i = 1;
$balance = 0;
$all_trips = 0;
$all_expance = 0;
$all_profit = 0;
$all_expense_of_trips = 0;
$total_expenses_of_trips = 0;
while ($trip = mysqli_fetch_array($trips)) {
    $date = $trip['date'];

    $trips_query = mysqli_query($conn, "SELECT count(id) as trips, SUM(total_bill) as total_bill, SUM(expense) as trip_expense FROM trips where vehicle_id = '$current_vehicle_id' AND end_date='$date';");
    $trips_total = mysqli_fetch_array($trips_query);
    $total_amount = $trips_total['total_bill'];
    $total_trips = $trips_total['trips'];
    $trip_expense = $trips_total['trip_expense'] ?? 0;
    $all_trips = $all_trips + $total_trips;
    $balance = $balance + $total_amount;
    if ($total_amount == "") {
        $total_amount = 0;
    }

    $expance = mysqli_query($conn, "SELECT SUM(total_expenses) as total_expense FROM vehicle_expenses where vehicle_id = '$current_vehicle_id' AND end_date='$date';");
    $expance_total = mysqli_fetch_array($expance);
    $expense_amount = $expance_total['total_expense'] ?? 0;
    $all_expance = $all_expance + $expense_amount;
    $total_expense = $trip_expense + $expense_amount;
    $profit = $total_amount - $total_expense;
    $all_profit = $all_profit + $profit ?? 0;
    $total_expenses_of_trips = $total_expenses_of_trips + $total_expense;
    $all_expense_of_trips = $all_expense_of_trips + $trip_expense;
    if ($expense_amount == "") {
        $expense_amount = 0;
    }

    $months = array(
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July ',
        'August',
        'September',
        'October',
        'November',
        'December',
    );

  
    $currentdate = explode("-", $date);

    $dat = $currentdate[2];
    $month = $currentdate[1];
    $year = $currentdate[0];
    $month_name = $months[$month - 1];

    $res = $dat . ' ' . $month_name . ' , ' . $year;
    ?>
                            <tr>
                            <input type="hidden" id="m_name" value='<?php echo $month_name?>'> 
               <input type="hidden" id="y_name" value='<?php echo $year?>'> 
               <input type="hidden" id="v_name" value='<?php echo $v_name?>'>
                                <td scope="row"><?php echo $i++; ?></td>

                                <td><?php echo $res ?></td>
                                <td class=""><?php echo $total_trips; ?></td>
                                <td class=""><?php echo $total_amount; ?></td>
                                <td class=""><?php echo $trip_expense; ?></td>
                                <td class=""><?php echo $expense_amount; ?></td>
                                <td class=""><?php echo $total_expense; ?></td>
                                <td class=""><?php echo $profit; ?></td>


                            </tr>
                            <?php }?>

                            <tr class="bg-info ">
                                <td class=" text-white"> </td>
                                <td class=" text-white"> Sub Total</tdcolspan=>
                                <td class=" text-white"><?php echo $all_trips ?></td>
                                <td class=" text-white"><?php echo $balance ?></td>
                                <td class=" text-white"><?php echo $all_expense_of_trips ?> </td>
                                <td class=" text-white"><?php echo $all_expance ?></td>
                                <td class=" text-white"> <?php echo $total_expenses_of_trips; ?></td>
                                <td class=" text-white"><?php echo $all_profit ?></td>
                            </tr>
                            <?php

$fuels_query = mysqli_query($conn, "SELECT SUM(balance) as total_bill FROM fuel where vehicle_id = '$current_vehicle_id' AND MONTH(date) = $month AND YEAR(date) = $year;");
$fuel_row = mysqli_fetch_array($fuels_query);
$total_fuel_bill = $fuel_row['total_bill'];

?>
                            <tr class="bg-danger text-white">
                                <td></td>
                                <td class="text-white"> Fuel Expense</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-white"></td>
                                <td class="text-white"><?php echo $total_fuel_bill ?></td>
                            </tr>

                            <tr class="bg-success text-white">
                                <td></td>
                                <td class="text-white">Total Profit</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-white"><?php echo $all_profit - $total_fuel_bill ?></td>
                            </tr>


                        </tbody>

                    </table>
                </div>
                <!-- End Table with stripped rows -->

            </div>
        </section>





        <!-- ======= Footer ======= -->
        <?php include 'Master/footer.php';?>
        <!-- End Footer -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <!-- Scripts -->
        <?php include 'Master/scripts.php';?>
        <!-- Scripts -->
        <style>

        </style>
</body>
<script>

var today = new Date();
var month =document.getElementById("m_name").value;
var year =document.getElementById("y_name").value;
 var vehicle =document.getElementById("v_name").value; 

    $(document).ready(function () {


        var button = document.getElementById("add-user");
        button.addEventListener('click', function (event) {
            event.preventDefault();
            var cln = document.getElementsByClassName("user")[0].cloneNode(true);
            document.getElementById("users").insertBefore(cln, this);
            return false;
        });


        // the below event is for price - expense =  total <-- change in price
        $("#db_amount").change(function () {
            var amount = $('#db_amount').val();
            var balance = $('#db_balance').val();

            if (parseInt(amount) > parseInt(balance)) {
                alert("Selection is more than balance");
                $('#db_amount').val(0)
                return 0;
            } else {
                var total = balance - amount;

                $('#db_balance').val(parseInt(total));
            }
        });


        $("#expense_amount").change(function (e) {
            e.preventDefault();
            alert("teing");
            var expense_amount = $('#expense_amount').val();
            var total_expense = $('#total_expense').val();

            parseInt(total_expense) + parseInt(total_expense);

            var total = total_expense + expense_amount;

            $('#total_expense').val(parseInt(total))


        });

    });

    $('#incomeStatment').DataTable({
            searching: false,
            "paging": false,
            "info": false,
            order: [],

            dom: 'Bfrtip',
            buttons: [{
                className: 'btn btn-dark',
                extend: 'pdfHtml5',
                text: 'Download details',
                title: 'Monthly Income statement \n Vehicle:  '+vehicle+'  ('+month+','+year+')',
                messageTop: 'Print Date:'+today,
                init: function(api, node, config) {
                    $(node).removeClass('btn-primary');
                    $(node).on('click', function() {
                        $(this).addClass('btn-success');
                    });
                },

                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6,7] // export only columns
                },
                customize: function (doc) {
                    // Add a header to the PDF document
                

                    // Add a style for the header text
                    doc.styles.header = {
                        fontSize: 18,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    };

                },

                orientation: 'portrait',
                pageSize: 'A4'
            }]
        });


</script>



<style>
    #type {}

    #amount {
        color: red;
    }
</style>

</html>