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
    $totalDueAmount = 0;

    $pump_id = $c_id;
    $pump = mysqli_query($connect, "SELECT * FROM pumps WHERE id = $pump_id");

    if (mysqli_num_rows($pump) > 0) {
        $pump_array = mysqli_fetch_array($pump);
        $p_name = $pump_array['name'];
    }

    if ($month != "" && $year != "") {

        $query2 = "SELECT COUNT(id) AS total_order,SUM(balance) AS value_sum FROM fuel WHERE status = 1 AND pump_id = '$c_id' and YEAR(date) = '$year' AND MONTH(date) = '$month' ORDER BY id ASC";
        $result2 = mysqli_query($connect, $query2);
        $row2 = mysqli_fetch_array($result2);
        $total_amount = $row2["value_sum"];
        $total_order = $row2["total_order"];

        if ($total_amount == '') {
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

        $preAmount = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM pumps_details WHERE status = 1 AND pump_id='$c_id' and date < '$year-$month-1'";
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

        $details = "SELECT * FROM pumps_details WHERE status=1 AND pump_id = '$c_id' and YEAR(date) = '$year' AND MONTH(date) = '$month' ORDER by date ASC";
        $detailsResult = mysqli_query($connect, $details);

        if (mysqli_num_rows($detailsResult) > 0) {
            $table_daily_result .= '
            <div class="card p-2">
              <div class="card-content collapse show">
                <div class="card-dashboard dataTables_wrapper dt-bootstrap">
                    <div class="table-responsive">
                      <table id="pMonthlyTable" class="table table-bordered">
                         <thead>
                         <tr>
                         <th>Date</th>
                         <th>Operation</th>
                         <th>Credit</th>
                         <th>Debit</th>
                         <th>Balance</th>
                          </tr>
                             </thead>';
                             $totalDueAmount = 0;
           $totalDueAmount = $totalDueAmount + $total_debit - $total_credit;
            while ($detailsRow = mysqli_fetch_array($detailsResult)) {
                $t_id = $detailsRow["transaction_id"];
                $type = $detailsRow["type"];
                $due_amount = $detailsRow["due"];

                $credit = $detailsRow["credit"];
                $debit = $detailsRow["debit"];
                $date = $detailsRow["date"];
                $totalDueAmount = $totalDueAmount + $debit - $credit;
                if ($type == "Fuel") {
                     
                    $queryCash = "SELECT * FROM fuel WHERE id = '$t_id'";
                    $resultCash = mysqli_query($connect, $queryCash);
                    if (mysqli_num_rows($resultCash) > 0) {
                        while ($cashRow = mysqli_fetch_array($resultCash)) {
                             // $receivedAmount = $cashRow["cash_in"];
                             $pump_id = $cashRow["id"];
                             $litres = $cashRow["litres"];
 
                             // $balance = $cashRow["balance"];
                             $date = $cashRow["date"];
                             $vehicle_id = $cashRow["vehicle_id"];
                        }
                    }

                } else if ($type == "Cash payment") {
                     
                    $queryBank = "SELECT * FROM cashbook WHERE id='$t_id'";
                    $resultBank = mysqli_query($connect, $queryBank);
                    if (mysqli_num_rows($resultBank) > 0) {
                        while ($bankRow = mysqli_fetch_array($resultBank)) {

                            $returnAmount = $bankRow["cash_out"];

                        }
                    }

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

                $query1 = "SELECT name FROM pumps WHERE id='$c_id';";
                $result1 = mysqli_query($connect, $query1);
                if (mysqli_num_rows($result1) > 0) {
                    $row1 = mysqli_fetch_array($result1);
                    $username = $row1['name'];} else {
                    $username = $for;
                }

                if ($type == "Company due") {
                    $table_daily_result .= ' <tr >

                                                         <td>' . $res . '</td>
                                                         <td> Pumps old due amount</td>

                                                         <td >' . $credit . '</td>
                                                         <td >' . $debit . '</td>
                                                         <td >' . $totalDueAmount . '</td>
                                                       </tr> ';
                } else if ($type == "Fuel") {

                    $table_daily_result .= '
                    <tr >

                    <td>' . $res . '</td>

                    <td >Vehicle fuel</td>
                    <td >' . $credit . '</td>
                    <td >' . $debit . '</td>
                    <td >' . $totalDueAmount . '</td>
                  </tr> ';
                } else if ($type == "Cash payment") {
                    $table_daily_result .= ' <tr class="table-info">

                                <td >' . $res . '</td>

                                <td > Cash Received</td>
                                <td >' . $credit . '</td>
                                <td >' . $debit . '</td>
                                <td >' . $totalDueAmount . '</th>


                                  </tr> ';
                }
            }
            $table_daily_result .= '</table>
            <input type="hidden" id="m_name" value='.$month_name.'> 
               <input type="hidden" id="y_name" value='.$year.'> 
               <input type="hidden" id="v_name" value='.$v_name.'>
               <input type="hidden" id="p_name" value='.$p_name.'>
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
                         <th>litres</th>
                         <th>Credit</th>
                         <th>Debit</th>
                         <th>Balance</th>
                          </tr>
                         </thead>
                             <tr>
                             <td colspan="5" align="center"> No Record found</td>
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
   var pump = document.getElementById("p_name").value;

    $(document).ready(function() {
    $('#pMonthlyTable').DataTable({
    order: [],
    dom: 'Bfrtip',
    buttons: [{
        className: 'btn btn-dark',
        extend: 'pdfHtml5',
        text: 'Download details',
        title: 'Pump Monthly Details \n Vehicle:  '+vehicle+' | Pump: '+pump+' ('+month+','+year+')',
        messageTop: 'Print Date:'+today,
        init: function (api, node, config) {
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
            $(this).addClass('btn-success');
        });
        },

        exportOptions: {
        columns: [0, 1, 2, 3, 4] // export only columns
        },
        customize: function (doc) {
        // Add a header to the PDF document
        doc.styles.tableHeader.alignment="left";
        doc.content[2].table.widths = ["25%","30%","15%","15%","15%"];

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