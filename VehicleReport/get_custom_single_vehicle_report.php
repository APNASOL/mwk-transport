
<?php
include '../Controllers/DatabaseController.php';
$connect = OpenCon();
session_start();
if (isset($_POST['f_date'])) {
    $table_daily_result = '';
    $f_date = $_POST['f_date'];
    $t_date = $_POST['t_date'];
    $c_id = $_SESSION['current_vehicle_id'];

    if ($f_date != "" && $t_date != "") {

        $query1 = "SELECT COUNT(id) AS total_order,SUM(total_bill) AS value_sum FROM trips WHERE status=1 and vehicle_id = '$c_id' and end_date Between '$f_date' And '$t_date'";

        $result1 = mysqli_query($connect, $query1);
        $row1 = mysqli_fetch_array($result1);
        $total_amount = $row1["value_sum"];
        $total_order = $row1["total_order"];

        $table_daily_result .= '

        <div class="row text-center ">

            <div class="col">
                <div class="card bg-white text-warning mb-3" style="">

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total bills</b></h5><hr>
                <h3 class="card-title">' . $total_amount . '</h3>

                </div>
                </div>
            </div>

            <div class="col">
                <div class="card bg-white text-warning mb-3" style="">

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total Orders</b></h5><hr>
                <h3 class="card-title"><b>' . $total_order . '</b></h3>
                </div>
                </div>
            </div>

        </div>

        ';

        $preAmount = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM vehicle_details WHERE status = 1 AND vehicle_id = '$c_id' and date < '$f_date'";
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

        $details = "SELECT * FROM vehicle_details WHERE status=1 AND vehicle_id = '$c_id' and date Between '$f_date' And '$t_date' ORDER by date ASC";
        $detailsResult = mysqli_query($connect, $details);
        if (mysqli_num_rows($detailsResult) > 0) {
            $table_daily_result .= '
            <div class="card p-2">
              <div class="card-content collapse show">
                <div class=" card-dashboard dataTables_wrapper dt-bootstrap">
                    <div class="table-responsive">
                      <table id="CustomTable" class="table table-bordered">
                      <thead>
                      <tr>
                      
                         <th>Date</th>
                         <th>Customer</th>
                         <th>Load type</th>
                         <th>Weight</th>
                         <th>PPT</th>
                         <th>Expense</th>
                         <th>Credit</th>
                         <th>Debit</th>
                         <th>Balance</th>
                       </tr>
                          </thead>';

            $totalDueAmount =$total_credit-$total_debit;
            while ($detailsRow = mysqli_fetch_array($detailsResult)) {
                $t_id = $detailsRow["transaction_id"];
                $type = $detailsRow["type"];
                $due_amount = $detailsRow["due"];

                $credit = $detailsRow["credit"];
                $debit = $detailsRow["debit"];
                $date = $detailsRow["date"];
                
                

                if ($type == "Trip") {

                    $querySale = "SELECT * FROM trips WHERE status = 1 AND id='$t_id'";
                    $resultSale = mysqli_query($connect, $querySale);
                    if (mysqli_num_rows($resultSale) > 0) {
                        while ($saleRow = mysqli_fetch_array($resultSale)) {
                            $trip_id = $saleRow["id"];
                            $weight = $saleRow["weight"];
                            $load_type = $saleRow["load_type"];
                            $customerId = $saleRow["customer_id"];
                            $paymentStatus = $saleRow["payment_status"];
                            $vehicle_id = $saleRow["vehicle_id"];
                            $ton_price = $saleRow["price_per_ton"];
                            $total_bill = $saleRow["total_bill"];
                            $expense = $saleRow["expense"];
                            
                            if($paymentStatus == "due"){
                           $totalDueAmount = $totalDueAmount - $debit; 
                        }else{
                            $totalDueAmount = $totalDueAmount + $credit - $debit;
                        }
                        $query1 = "SELECT name FROM customers WHERE id='$customerId';";
                        $result1 = mysqli_query($connect, $query1);
                        if (mysqli_num_rows($result1) > 0) {
                            $row1 = mysqli_fetch_array($result1);
                            $username = $row1['name'];} else {
                            $username = $for;
                        }
                        }
                        
                        
                    }

                   

                } 
                else  {
                    $username = "";
                    $totalDueAmount = $totalDueAmount + $credit - $debit;
                

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

              

                if ($type == "Trip") {
                    $table_daily_result .= ' <tr >
                                                    <input type="hidden" class="form-control" name="m_name" id="m_name" value=' . $month_name . '>
                                                    <input type="hidden" class="form-control" name="y_name" id="y_name" value=' . $year . '>
                                                    <input type="hidden" class="form-control" name="u_name" id="u_name" value=' . $username . '>
                                                    <input type="hidden" class="form-control" name="t_amount" id="t_amount" value=' . $total_amount . '>
                                                    <input type="hidden" class="form-control" name="t_order" id="t_order" value=' . $total_order . '>
                                                    <td>' . $res . '</td>
                                                    <td>' . $username . '</td>
                                                                
                                                                <td >' . $load_type . '</td>
                                                                <td >' . $weight . '</td>
                                                                <td >' . $ton_price . '</td>
                                                                <td >' . $expense . '</td>
                                                                <td >' . $credit . '</td>
                                                                <td >' . $debit . '</td>
                                                                <td >' . $totalDueAmount . '</td>
                                                            </tr> ';
                } else if ($type == "Cash Received") {

                    $table_daily_result .= '
                                                    <tr class="table-danger" >
                                                    
                                                        <td >' . $res . '</td>
                                                    <td >'.$username.'</td>
                                                       <td></td>
                                                         <td></td>
                                                           <td></td>
                                                        <td > Cash Payment Received</td>
                                                        <td >' . $credit . '</td>
                                                        <td >' . $debit . '</td>
                                                        <td>' . $totalDueAmount . '</td>


                                                    </tr> ';
                } else if ($type == "Balance") {
                    $table_daily_result .= ' <tr class="table-info">
                    <td >' . $res . '</td>
                      <td></td>
                                                          <td></td>
                                                            <td></td>
                                                              <td></td>
                     <td>Given balance to vehicle</td>
                     <td >' . $credit . '</td>
                     <td >' . $debit . '</td>
                    <td>' . $totalDueAmount . '</td>
                                        </tr> ';
                }
                else if ($type == "Balance widhdrawn") {

                    $table_daily_result .= '
                                                    <tr class="table-danger" >
                                                     
                                                        <td>' . $res . '</td>
                                                        <td></td>
                                                          <td></td>
                                                            <td></td>
                                                              <td></td>
                                                        <td >Received balance from vehicle</td>
                                                        <td >' . $credit . '</td>
                                                                <td >' . $debit . '</td>
                                                        <td>' . $totalDueAmount . '</td>



                                                    </tr> ';
                }
                else if ($type == "Expense") {

                    $table_daily_result .= '
                                                    <tr class="table-danger" >
                                                    
                                                        <td >' . $res . '</td>
                                                  <td></td>
                                                          <td></td>
                                                            <td></td>
                                                              <td></td>
                                                        <td >Vehicle expense</td>
                                                        <td >' . $credit . '</td>
                                                                <td >' . $debit . '</td>
                                                        <td>' . $totalDueAmount . '</td>



                                                    </tr> ';
                }
                else if ($type == "Old balance") {

                    $table_daily_result .= '
                                                    <tr class="table-danger" >
                                                    
                                                        <td>' . $res . '</td>
                                                  <td></td>
                                                          <td></td>
                                                            <td></td>
                                                              <td></td>
                                                        <td >Vehicle old balance</td>
                                                        <td >' . $credit . '</td>
                                                                <td >' . $debit . '</td>
                                                        <td>' . $totalDueAmount . '</td>



                                                    </tr> ';
                }
            }
            $table_daily_result .= '
                    </table>
          
            </div>
            </div>
            </div>';
            echo $table_daily_result;
        } else {
            $table_daily_result .= '<div class="table-responsive">
                     <table id="example" class="table table-bordered">
                         <thead>
                         <tr>
                        <th>Customer</th>
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
                             <td colspan="6" align="center"> No Record found</td>
                             </tr>';
            echo $table_daily_result;
        }

    }
}

?>
<script>
var today = new Date();
var to_date =document.getElementById("to_date").value;
 var from_date =document.getElementById("from_date").value;
 var user =document.getElementById("u_name").value;
 var amount =document.getElementById("t_amount").value;
 var order =document.getElementById("t_order").value;
$(document).ready(function() {
    $('#CustomTable').DataTable({
    order: [],

    dom: 'Bfrtip',
    buttons: [{
        className: 'btn btn-dark',
        extend: 'pdfHtml5',
        text: 'Download details',
        title: 'Custom report details',
        init: function (api, node, config) {
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
            $(this).addClass('btn-success');
        });
        },

        exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // export only columns
        },
        customize: function (doc) {
        // Add a header to the PDF document
        doc.content.unshift({
            text: 'Custom report',
            style: 'header'
        });

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
