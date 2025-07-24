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

            <h1>Vehicle Financial Statement</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Vehicle</li>
                    <li class="breadcrumb-item active">Financial Statement</li>
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
                    <h5 class="card-title">Vehicle Financial Statement</h5>
                    <?php
$yearsQuery = "SELECT * FROM years ORDER BY id";
$years = mysqli_query($conn, $yearsQuery);
$monthQuery = "SELECT * FROM months ORDER BY id";
$months = mysqli_query($conn, $monthQuery);

$current_year = date('Y');
$current_month = date('m');

?>

                <!-- <form action="Controllers/VehicleController.php" method="post">

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

                                <button type="submit" name="search_financial_statement"
                                    class="btn btn-dark mt-4 btn-md">Search</button>


                            </div>
                       
                    </div>
                </form> -->


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

         $vehicle_cash_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id='$current_vehicle_id' ORDER BY date");

         $total_balance = 0;
         $cash_in_show = 0;
         $cash_out_show = 0;

            while ($record = mysqli_fetch_array($vehicle_cash_details)) {

                 if ($record['type'] == 'Trip') {
                    
                        if ($record['note'] == 'Not received') {
                            $cash_in_show = $cash_in_show + 0;
                             $cash_out_show = $cash_out_show + $record['debit'];
                            $total_balance = $total_balance - $record['debit'];
                        } else {
                             $cash_in_show = $cash_in_show + $record['credit'];
                             $cash_out_show = $cash_out_show + $record['debit'];
                             $total_balance = $total_balance + $record['credit'] - $record['debit'];
                         }
                 } else {
                    $cash_in_show = $cash_in_show + $record['credit'];
                    $cash_out_show = $cash_out_show + $record['debit'];
                    $total_balance = $total_balance + $record['credit'] - $record['debit'];
                 }
            }

             
                $QueryForCashbook = mysqli_query($conn, "SELECT SUM(cash_out) AS cash_out,SUM(cash_in) AS cash_in FROM  cashbook WHERE vehicle_id='$current_vehicle_id'");

                $cashbook_cash = mysqli_fetch_array($QueryForCashbook);
                $cash_in_shows = $cashbook_cash['cash_in'];
                $cash_out_shows = $cashbook_cash['cash_out'];

                $total_balances = $cash_in_shows - $cash_out_shows;


                $customers_totals = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");
                
                $dues = 0;

                $differance = 0;

                while ($selectCustomerRow = mysqli_fetch_array($customers_totals)) {
                  $customer_id = $selectCustomerRow["id"];
                  
                  $t_details = "SELECT SUM(credit) AS t_c_credit,SUM(debit) AS t_c_debit FROM customer_details WHERE type='Trip' AND status=1 AND customer_id='$customer_id' ORDER BY date";
                  $t_details_result = mysqli_query($conn, $t_details);
                  $t_details_row = mysqli_fetch_array($t_details_result);
                                                              
                  $t_c_credit = $t_details_row["t_c_credit"];
                    if ($t_c_credit == "") {
                          $t_c_credit = 0.0;
                    }
                  $t_c_debit = $t_details_row["t_c_debit"];
                    if ($t_c_debit == "") {
                          $t_c_debit = 0.0;
                    }
                    
                    $details = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM customer_details WHERE type !='Trip' AND status=1 AND customer_id='$customer_id' ORDER BY date";
                  $details_result = mysqli_query($conn, $details);
                  $details_row = mysqli_fetch_array($details_result);
                                                              
                  $c_credit = $details_row["c_credit"];
                    if ($c_credit == "") {
                          $c_credit = 0.0;
                    }
                  $c_debit = $details_row["c_debit"];
                    if ($c_debit == "") {
                          $c_debit = 0.0;
                    }
                                                              
                  $dues = $dues + $c_debit - $c_credit+ $t_c_debit;


                  $d_details = "SELECT SUM(credit) AS d_credit FROM customer_details WHERE note ='Differance' AND status=1 AND customer_id='$customer_id';";
                  $d_details_result = mysqli_query($conn, $d_details);
                  $d_details_row = mysqli_fetch_array($d_details_result);
                                                              
                  $d_credit = $d_details_row["d_credit"];
                    if ($d_credit == "") {
                          $d_credit = 0.0;
                    }
                  
                                                              
                  $differance =$differance + $d_credit;
                  
                  
                } 
                
                $stmtpatient = mysqli_query($conn, "SELECT * FROM partners WHERE v_id='$current_vehicle_id' AND status = 1");
                                         $profit1 = 0;
                                                            foreach ($stmtpatient as $value) {
                                                                
                                                                $mine = $value['v_id'];
        
                                                                $id = $value['id'];
                                         $details1 = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM partner_details WHERE status=1 AND p_id='$id' ORDER BY date";
                                                                $details_result1= mysqli_query($conn, $details1);
                                                                $details_row1 = mysqli_fetch_array($details_result1);
                                                                
                                                                $c_credit1 = $details_row1["c_credit"];
                                                                if ($c_credit1 == "") {
                                                                    $c_credit1 = 0;
                                                                }
                                                                $c_debit1 = $details_row1["c_debit"];
                                                                if ($c_debit1 == "") {
                                                                    $c_debit1 = 0;
                                                                }
                                                                
                                                                $profit1 = $profit1 + $c_credit1 - $c_debit1;
                                                                
                                                                }


                                                                $i = 1;
                                                                $balance = 0;
                                                                $all_trips = 0;
                                                                $all_expance = 0;
                                                                $all_profit = 0;
                                                                $all_expense_of_trips = 0;
                                                                $total_expenses_of_trips = 0;
                                                                $trips = mysqli_query($conn, "SELECT count(id) as val, date FROM vehicle_income_operations where vehicle_id = '$current_vehicle_id';");
                                                                while ($trip = mysqli_fetch_array($trips)) {
                                                                    $date = $trip['date'];
                                                                
                                                                    $trips_query = mysqli_query($conn, "SELECT count(id) as trips, SUM(total_bill) as total_bill, SUM(expense) as trip_expense FROM trips where vehicle_id = '$current_vehicle_id'");
                                                                    $trips_total = mysqli_fetch_array($trips_query);
                                                                    $total_amount = $trips_total['total_bill'];
                                                                    $total_trips = $trips_total['trips'];
                                                                    $trip_expense = $trips_total['trip_expense'] ?? 0;
                                                                    $all_trips = $all_trips + $total_trips;
                                                                    $balance = $balance + $total_amount;
                                                                    if ($total_amount == "") {
                                                                        $total_amount = 0;
                                                                    }
                                                                
                                                                    $expance = mysqli_query($conn, "SELECT SUM(total_expenses) as total_expense FROM vehicle_expenses where vehicle_id = '$current_vehicle_id'");
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
                                                                 
                                                                }    
                                                                
                    $fuels_query = mysqli_query($conn, "SELECT SUM(balance) as total_bill FROM fuel where vehicle_id = '$current_vehicle_id';");
                    $fuel_row = mysqli_fetch_array($fuels_query);
                    $total_fuel_bill = $fuel_row['total_bill'];

                  $trips_details = "SELECT SUM(total_bill) AS sum_value FROM trips WHERE vehicle_id='$current_vehicle_id' AND status=1;";
                  $trips_details_result = mysqli_query($conn, $trips_details);
                  $trips_details_row = mysqli_fetch_array($trips_details_result);
                                                              
                  $revenue = $trips_details_row["sum_value"];
                    if ($revenue == "") {
                          $revenue = 0.0;
                    }

                  $profit_details = "SELECT SUM(cash_in) AS profit_sum,SUM(cash_out) AS expanse_sum FROM profit_book WHERE vehicle_id='$current_vehicle_id';";
                  $profit_details_result = mysqli_query($conn, $profit_details);
                  $profit_details_row = mysqli_fetch_array($profit_details_result);
                                                              
                  $profit = $profit_details_row["profit_sum"];
                    if ($profit == "") {
                          $profit = 0.0;
                    }

                    $ext_expanse = $profit_details_row["expanse_sum"];
                    if ($ext_expanse == "") {
                          $ext_expanse = 0.0;
                    }


                    $fuel_due = 0;
        $pumps = mysqli_query($conn, "SELECT * FROM pumps where vehicle_id = '$current_vehicle_id'");
        
        
  while ($pump = mysqli_fetch_array($pumps)) {
      $pump_id = $pump["id"];
     
      $a_details = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM pumps_details WHERE status=1 AND pump_id = '$pump_id' ORDER BY date";
      $a_details_result = mysqli_query($conn, $a_details);
      $a_details_row = mysqli_fetch_array($a_details_result);
                                                 
      $a_c_credit = $a_details_row["c_credit"];
       if ($a_c_credit == "") {
             $a_c_credit = 0.0;
       }
      $a_c_debit = $a_details_row["c_debit"];
       if ($a_c_debit == "") {
             $a_c_debit = 0.0;
       }
                                                 
      $fuel_due = $fuel_due + $a_c_debit - $a_c_credit;
  }

                    
                    //Assets
                    $fuel_paid = $total_fuel_bill - $fuel_due;

                    
                    $Retained_Earnings = $revenue - $total_expenses_of_trips - $total_fuel_bill;

                    $profit_shared = $Retained_Earnings - $profit1;
                    
                    $total_assets = $dues + $total_balances + $total_balance;
                    $total_lib = $profit1 + $fuel_due;

                    $toEqual =   $profit - $profit1 -$ext_expanse;
                    
?>

                </div>

                <!-- Table with stripped rows -->
                <div class="card card-body table-responsive">
                    <table class="table table-striped" id="incomeStatment">
                        <thead>

                            <tr>
                                <th scope="col">Assests</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Liabilities + Equity</th>
                                <th scope="col">Amount</th>
                            </tr>

                        </thead>
                        <tbody>

                            <tr>
                                <td>Vehicle Cash </td>
                                <td> <?=$total_balance?></td>
                                <td>Profit Payable </td>
                                <td> <?=$profit1?> </td>
                            </tr>
                            <tr>
                                <td>Company Cash </td>
                                <td> <?=$total_balances?></td>
                                <td> Fuel Payable </td>
                                <td> <?=$fuel_due;?></td>
                                
                            </tr>
                            <tr>
                                <td>Customer Dues </td>
                                <td> <?=$dues?></td>
                                
                                <td>  </td>
                                <td> </td>
                                
                            </tr>
                             <!--<tr>
                                <td>Profit widhdrawn</td>
                                <td> <?=$profit_shared?></td>
                                <td> </td>
                                <td> </td>
                                
                            </tr>-->

                            <!--<tr>
                                <td>Expanse Paid</td>
                                <td> <?=$total_expenses_of_trips?></td>
                                <td> </td>
                                <td> </td>
                                
                            </tr>-->

                            <!--<tr>
                                <td>Fuel Paid</td>
                                <td> <?=$fuel_paid ?></td>
                                <td> </td>
                                <td> </td>
                                
                            </tr>-->
                             
                             <tr>
                                <td>Total  </td>
                                <td> <?=$total_assets?></td>
                                <td>Total  </td>
                                <td> <?=$total_lib?></td>
                                
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