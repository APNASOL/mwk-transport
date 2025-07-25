<?php
include 'Controllers/DatabaseController.php';
$conn = OpenCon();
session_start();
$v_name = $_SESSION['current_vehicle_number'];


if (isset($_POST['month'])) {
    $table_daily_result = '';
    $month = $_POST['month'];
    $year = $_POST['year'];
    $c_id = $_SESSION['current_vehicle_id'];

 
    
    if ($c_id != "empty") {
        // Old query
    // $vehicle_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id =  $c_id and YEAR(date) = '$year' AND MONTH(date) = '$month' group BY date");
mysqli_query($conn, "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
$vehicle_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id = $c_id and YEAR(date) = '$year' AND MONTH(date) = '$month' GROUP BY date");

  
    }

    if ($month != "" && $year != "") {

        

                                

                                        $current_month = date('m');
                                        $current_year = date('Y');
                                        // echo $current_month. "  / ".$current_year. " / ".$current_vehicle_id; exit();
                                        $QueryForCashbookAll = mysqli_query($conn,"SELECT SUM(cash_out) AS cash_out,SUM(cash_in) AS cash_in FROM  cashbook WHERE vehicle_id = '$c_id'");

                                        $cashbook_cash_all = mysqli_fetch_array($QueryForCashbookAll);
                                        $cash_in_show_all = $cashbook_cash_all['cash_in'];
                                        $cash_out_show_all = $cashbook_cash_all['cash_out'];

                                        $total_balance = $cash_in_show_all - $cash_out_show_all;

                                        $QueryForCashbook = mysqli_query($conn,"SELECT SUM(cash_out) AS cash_out,SUM(cash_in) AS cash_in FROM  cashbook WHERE vehicle_id = '$c_id' AND YEAR(date) = '$year' AND MONTH(date) = '$month' ");

                                        $cashbook_cash = mysqli_fetch_array($QueryForCashbook);
                                        $cash_in_show = $cashbook_cash['cash_in'];
                                        $cash_out_show = $cashbook_cash['cash_out'];

                                        if($cash_in_show == ""){
                                            $cash_in_show = 0;
                                        }

                                        if($cash_out_show == ""){
                                            $cash_out_show = 0;
                                        }

                                        $total_balance = $cash_in_show_all - $cash_out_show_all;
                                        


                                        
                                        $table_daily_result .='<div class="row">
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
                                                            <h4>'.$cash_in_show .'</h4>


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
                                                            <h4>'.$cash_out_show .'</h4>

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
                                                            <h4>'.$total_balance.'</h4>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div><!-- End fule Card -->
                                    </div>
                                </div>
                            </div>
                             <div class="card  card-body p-3">
                            <table class="table table-striped" id="monthlyTable">
                                <thead>

                                <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Consume from</th>
                                        <th scope="col">Operation for</th>
                                        <th scope="col">Cash In</th>
                                        <th scope="col">Cash Out</th>
                                        <th scope="col">Balance</th>
                                        
                                        <th scope="col">Notes</th>
                                        <th scope="col">Action</th>
                              </tr>
                                </thead>
                                <tbody>

        ';

        $preAmount = "SELECT SUM(cash_in) AS credit, SUM(cash_out) AS debit FROM cashbook WHERE status = 1 AND vehicle_id='$c_id' and date < '$year-$month-1'";
        $preAmountResult = mysqli_query($conn, $preAmount);
        $row3 = mysqli_fetch_array($preAmountResult);
        $total_credit = $row3["credit"];
        $total_debit = $row3["debit"];

        if ($total_credit == '') {
            $total_credit = 0.0;
        }

        if ($total_debit == '') {
            $total_debit = 0.0;
        }
        $balance =  $total_credit-$total_debit;

        $cashbooks = mysqli_query($conn, "SELECT * FROM cashbook where  vehicle_id = '$c_id' AND YEAR(date) = '$year' AND MONTH(date) = '$month' ORDER BY date");

        
        $i = 1;
        while ($cashbook = mysqli_fetch_array($cashbooks)) {
            $operation_for = $cashbook['operation_for'];
            $credit = $cashbook['cash_in'];
            $debit = $cashbook['cash_out'];
            $balance = $balance + $credit - $debit;
            

            $date = $cashbook['date'];
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

               $table_daily_result .='<tr>
               <input type="hidden" id="m_name" value='.$month_name.'> 
               <input type="hidden" id="y_name" value='.$year.'> 
               <input type="hidden" id="v_name" value='.$v_name.'>
                    <td scope="row">'.$i++.'</td>
                    <td >'.$res.'</td>';
                    if ($operation_for == 'Given to vehicle' || $operation_for == 'Recieved from vehicle' ) {
                        $vehicle_id = $cashbook['consume_id'];
                        $vehicle = mysqli_query($conn, "SELECT * FROM vehicles  WHERE id = $vehicle_id");

                        if (mysqli_num_rows($vehicle) > 0) {
                            $vehicle_array = mysqli_fetch_array($vehicle);
                            
                           $table_daily_result .='<td > Vehicle # '.  $vehicle_array['number'].'</td>';
                        }
                    } else if ($operation_for == 'customer' || $operation_for == 'Cash return') {
                        $customer_id = $cashbook['consume_id'];
                        $customer = mysqli_query($conn, "SELECT * FROM customers  WHERE id = $customer_id");

                        if (mysqli_num_rows($customer) > 0) {;
                            $customer_array = mysqli_fetch_array($customer);
                          $table_daily_result .='<td >'.$customer_array['name'].'</td>';
                        }

                    }
                    else if ($operation_for == 'Payed to pump') {
                      $pump_id = $cashbook['consume_id'];
                    $pump = mysqli_query($conn, "SELECT * FROM pumps  WHERE id = $pump_id");

                    if (mysqli_num_rows($pump) > 0) {
                        $pump_array = mysqli_fetch_array($pump);
                        $table_daily_result .=' <td >'. $pump_array['name'].'</td>';
                    }else{
                        $vehicle_id = $cashbook['consume_id'];
                        $vehicle = mysqli_query($conn, "SELECT * FROM vehicles  WHERE id = $vehicle_id");

                        if (mysqli_num_rows($vehicle) > 0) {
                            $vehicle_array = mysqli_fetch_array($vehicle);
                            
                           $table_daily_result .='<td > Vehicle # '.  $vehicle_array['number'].'</td>';
                        }

                    }
                }else if ($operation_for == 'Company cash') {
                    $table_daily_result .=' <td >Old Cash</td>';
                }else if ($operation_for == 'Vehicle profit') {
                    $pump_id = $cashbook['consume_id'];
                    $pump = mysqli_query($conn, "SELECT * FROM partners WHERE id = $pump_id");

                    if (mysqli_num_rows($pump) > 0) {
                        $pump_array = mysqli_fetch_array($pump);
                        $table_daily_result .=' <td >'. $pump_array['name'].'</td>';
                    }else{
                        $table_daily_result .=' <td >Profit</td>';
                    }
                }else{
                    $table_daily_result .=' <td >'. $cashbook['consume_id'].'</td>';
                }
                    $table_daily_result .='<td >'.$cashbook['operation_for'].'</td>
                    <td >'.$cashbook['cash_in'].'</td>
                    <td >'.$cashbook['cash_out'].'</td>
                    <td >'.$balance.'</td>
                    <td >'.$cashbook['notes'].'</td>
                    <td class="text-center">
                    <a href="Controllers/CashBookController.php?cashbook_id='.$cashbook['id'].'&process=edit"> <i class="bi bi-pencil-square"></i></a>
                    </td>

                </tr>';

             }
                $table_daily_result .='</tbody>
                                            </table>
                                        </div>';
                                        echo $table_daily_result; 
            
        }
         else{
                $table_daily_result .= '
                        <tr>
                        <td colspan="8" align="center"> No Record found</td>
                        </tr>
                        </tbody>
                            </table>
                        </div>
                        ';
                 echo $table_daily_result;
            }
        
}

?>
<script>
var today = new Date();
var month =document.getElementById("m_name").value;
var year =document.getElementById("y_name").value;
var vehicle =document.getElementById("v_name").value; 
function fetchDetails(transaction_id, type) {
        
       // alert(type);
        $.ajax({
            type: 'POST',
            url: 'Controllers/VehicleController.php?process=EntryDetails',
            data: {
                transaction_id: transaction_id,
                type: type
            },
            success: function (data) {
                console.log(data);
                $('#details_modal').html(data)
            }
        });
    }

$(document).ready(function() {
    $('#monthlyTable').DataTable({
  
    order: [],

    dom: 'Bfrtip',
    buttons: [{
        className: 'btn btn-dark',
        extend: 'pdfHtml5',
        text: 'Download details',
        title: 'Monthly Cashbook Details \n Vehicle:  '+vehicle+'  ('+month+','+year+')',
        messageTop: 'Print Date:'+today,
        init: function (api, node, config) {
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
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
});
</script>
