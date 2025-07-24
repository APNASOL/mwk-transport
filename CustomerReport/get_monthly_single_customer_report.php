<?php
include '../Controllers/DatabaseController.php';
$connect = OpenCon();
session_start();

$v_name = $_SESSION['current_vehicle_number'];

if (isset($_POST['month'])) {
    $table_daily_result = '';
    $month = $_POST['month'];
    $year = $_POST['year'];
    $c_id = $_POST['cid'];

    $customer = mysqli_query($connect, "SELECT * FROM customers WHERE id = $c_id");

    if (mysqli_num_rows($customer) > 0) {
        $customer_array = mysqli_fetch_array($customer);
        $c_name = $customer_array['name'];
    }

    if ($month != "" && $year != "") {

        $query2 = "SELECT COUNT(id) AS total_order,SUM(total_bill) AS value_sum FROM trips WHERE status=1 AND customer_id = '$c_id' and YEAR(end_date) = '$year' AND MONTH(end_date) = '$month' ORDER BY id ASC";
        $result2 = mysqli_query($connect, $query2);
        $row2 = mysqli_fetch_array($result2);
        $total_amount = $row2["value_sum"];
        $total_order = $row2["total_order"];

        if($total_amount == '')
        {
            $total_amount = 0;
        } 

        $table_daily_result .= '

        <div class="row text-center ">

            <div class="col">
                <div class="card bg-white text-warning mb-3" >

                <div class="card-body p-3">
                <h5><b class="text-dark text-left card-title">Total Bills</b></h5><hr>
                <h3 class="card-title"><b>' . $total_amount . '</b></h3>

                </div>
                </div>
            </div>

            <div class="col">
                <div class="card bg-white text-warning mb-3" >

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total Orders</b></h5><hr>
                <h3 class="card-title"><b>' . $total_order . '</b></h3>
                </div>
                </div>
            </div>

        </div>

        ';

        /*$preAmount = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM customer_details WHERE status=1 AND c_id='$c_id' and YEAR(date) <= '$year' AND MONTH(date) < '$month'";  */

        /*if($month == 01){
        $preAmount = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM customer_details WHERE status=1 AND c_id='$c_id' and YEAR(date) < '$year'";

        }else{

        $preAmount = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM customer_details WHERE status=1 AND c_id='$c_id' and YEAR(date) <= '$year' AND MONTH(date) < '$month'";

        }*/
        $preAmount = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM customer_details WHERE status = 1 AND customer_id='$c_id' and date < '$year-$month-1'";
        $preAmountResult = mysqli_query($connect, $preAmount);
        $row3 = mysqli_fetch_array($preAmountResult);
        $total_credit = $row3["credit"];
        $total_debit = $row3["debit"];

        if ($total_credit == '') {
            $total_credit = 0.0;
        }

        if ($total_debit == '') {
            $total_debit = 0.0;
        }

        $details = "SELECT * FROM customer_details WHERE status=1 AND customer_id='$c_id' and YEAR(date) = '$year' AND MONTH(date) = '$month' ORDER by date ASC";
        $detailsResult = mysqli_query($connect, $details);
        if (mysqli_num_rows($detailsResult) > 0) {
            $table_daily_result .= '
            <div class="card p-2">
              <div class="card-content collapse show">
                <div class="card-dashboard dataTables_wrapper dt-bootstrap">
                    <div class="table-responsive">
                      <table id="monthlyTable" class="table table-bordered">
                         <thead>
                         <tr>
                         <th>Date</th>
                         <th>Load type</th>
                         <th>Weight</th>
                         <th>PPT</th>
                         <th>Expense</th>
                         <th>Credit</th>
                         <th>Debit</th>
                         <th>Balance</th>
                          </tr>
                             </thead>';

            $totalDueAmount = $total_debit - $total_credit;
            while ($detailsRow = mysqli_fetch_array($detailsResult)) {
                $t_id = $detailsRow["transaction_id"];
                $type = $detailsRow["type"];
                $due_amount = $detailsRow["due"];

                $credit = $detailsRow["credit"];
                $debit = $detailsRow["debit"];
                $date = $detailsRow["date"];

                $note = $detailsRow["note"];

                if ($type == "Trip") {

                    $totalDueAmount = $totalDueAmount + 0 + $debit;

                    $querySale = "SELECT * FROM trips WHERE status = 1 AND id='$t_id'";
                    $resultSale = mysqli_query($connect, $querySale);
                    if (mysqli_num_rows($resultSale) > 0) {
                        while ($saleRow = mysqli_fetch_array($resultSale)) {
                            $trip_id = $saleRow["id"];
                            $weight = $saleRow["weight"];
                            $load_type = $saleRow["load_type"];
                            
                            $vehicle_id = $saleRow["vehicle_id"];
                            $ton_price = $saleRow["price_per_ton"];
                            $total_bill = $saleRow["total_bill"];
                            $expense = $saleRow["expense"];
                        }
                    }

                    $query3 = "SELECT number FROM vehicles WHERE id='$vehicle_id';";
                    $result3 = mysqli_query($connect, $query3);
                    if (mysqli_num_rows($result3) > 0) {
                        $row3 = mysqli_fetch_array($result3);
                        $vehicle_number = $row3['number'];
                    }

                } else if ($type == "Cash Payment") {
                    $totalDueAmount = $totalDueAmount - $credit + $debit;
                    $queryCash = "SELECT * FROM cashbook WHERE id = '$t_id'";
                    $resultCash = mysqli_query($connect, $queryCash);
                    if (mysqli_num_rows($resultCash) > 0) {
                        while ($cashRow = mysqli_fetch_array($resultCash)) {
                            $receivedAmount = $cashRow["cash_in"];
                        }
                    }

                }else if ($type == "Cash Return") {
                    $totalDueAmount = $totalDueAmount - $credit + $debit;
                    $queryBank = "SELECT * FROM cashbook WHERE id='$t_id'";
                    $resultBank = mysqli_query($connect, $queryBank);
                    if (mysqli_num_rows($resultBank) > 0) {
                        while ($bankRow = mysqli_fetch_array($resultBank)) {

                            $returnAmount = $bankRow["cash_out"];

                        }
                    }

                }
                else if ($type == "Due") {
                    $totalDueAmount = $totalDueAmount - $credit + $debit;
                

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

                $query1 = "SELECT name FROM customers WHERE id='$c_id';";
                $result1 = mysqli_query($connect, $query1);
                if (mysqli_num_rows($result1) > 0) {
                    $row1 = mysqli_fetch_array($result1);
                    $username = $row1['name'];} else {
                    $username = $for;
                }

                if ($type == "Trip") {
                    $table_daily_result .= ' <tr >
                                             <input type="hidden" class="form-control" name="m_name" id="m_name" value=' . $month_name . '>
                                             <input type="hidden" class="form-control" name="y_name" id="y_name" value=' . $year . '>
                                             <input type="hidden" class="form-control" name="u_name" id="u_name" value=' . $username . '>
                                             <input type="hidden" class="form-control" name="t_amount" id="t_amount" value=' . $total_amount . '>
                                             <input type="hidden" class="form-control" name="t_order" id="t_order" value=' . $total_order . '>
                                                         <td>' . $res . '</td>
                                                         <td >' . $load_type . '</td>
                                                         <td >' . $weight . '</td>
                                                         <td >' . $ton_price . '</td>
                                                         <td >' . $expense . '</td>
                                                         <td >' . $credit . '</td>
                                                         <td >' . $debit . '</td>
                                                         <td >' . $totalDueAmount . '</td>
                                                       </tr> ';
                } else if ($type == "Cash Payment") {
                    
                    $table_daily_result .= '
                                             <tr class="table-danger" >
                                                 <td >' . $res . '</td>
                                                 
                                                 <td > Cash Payment Received</td>
                                                 <td ></td>
                                                 
                                                 <td></td>
                                                 <td ></td>
                                                 <td >' . $credit . '</td>
                                                 <td >' . $debit . '</td>
                                                 <td>' . $totalDueAmount . '</td>
                                               </tr> ';
                } else if ($type == "Cash Return") {
                    $table_daily_result .= ' <tr class="table-info">

                                <td >' . $res . '</td>
                                 <td > Return Cash</td>
                                <td ></td>
                                    <td ></td>
                                    
                                    <td ></td>
                               
                                <td >' . $credit . '</td>
                                <td >' . $debit . '</td>
                                <td >' . $totalDueAmount . '</td>


                                  </tr> ';
                }else if ($type == "Due") {
                    
                    $table_daily_result .= '
                                             <tr class="table-danger" >
                                                 <td >' . $res . '</td>';
                                                 if($note == "Old amount"){
                                                 
                                                 $table_daily_result .= '<td > Customer old due </td>';
                                                 }else{
                                                    $table_daily_result .= '<td > Customer Differance </td>'; 
                                                 }
                                                 $table_daily_result .= '<td ></td>
                                                 
                                                 <td></td>
                                                 <td ></td>
                                                 <td >' . $credit . '</td>
                                                         <td >' . $debit . '</td>
                                                 <td>' . $totalDueAmount . '
                                                 </td>


                                               </tr> ';
                }  
            }
            $table_daily_result .= '</table>
            <input type="hidden" id="m_name" value='.$month_name.'> 
               <input type="hidden" id="y_name" value='.$year.'> 
               <input type="hidden" id="v_name" value='.$v_name.'>
               <input type="hidden" id="c_name" value='.$c_name.'>
            </div>
            </div>
            </div>
            </div>';
            echo $table_daily_result;
        } else {
            $table_daily_result .= '<div class="table-responsive">
                     <table id="example" class="table table-bordered">
                     <thead>
                     <tr>
                         <th>Date</th>
                         <th>Load type</th>
                         <th>Weight</th>
                         <th>PPT</th>
                         <th>Expense</th>
                         <th>Credit</th>
                         <th>Debit</th>
                         <th>Balance</th>
                          </tr>
                         </thead>
                             <tr>
                             <td colspan="9" align="center"> No Record found</td>
                             </tr>';
            echo $table_daily_result;
        }

    }
}

?>
<script>
var today = new Date();
   var month = document.getElementById("m_name").value;
   var year = document.getElementById("y_name").value;
   var vehicle = document.getElementById("v_name").value;
   var customer = document.getElementById("c_name").value;

$(document).ready(function() {
    $('#monthlyTable').DataTable({
    order: [],
    dom: 'Bfrtip',
    buttons: [{
        className: 'btn btn-dark',
        extend: 'pdfHtml5',
        text: 'Download details',
        title: 'Customer Monthly Details \n Vehicle:  '+vehicle+' | Customer: '+customer+' ('+month+','+year+')',
        messageTop: 'Print Date:'+today,
        init: function (api, node, config) {
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
            $(this).addClass('btn-success');
        });
        },

        exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7] // export only columns
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
