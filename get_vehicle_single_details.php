<?php
include 'Controllers/DatabaseController.php';
$conn = OpenCon();
session_start();
if (isset($_POST['date'])) {
    $table_daily_result = '';
    $date = $_POST['date'];
    $c_id = $_SESSION['current_vehicle_id'];

 
    if ($c_id != "empty") {
    $vehicle_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id =  $c_id and date = '$date' ORDER BY date");
  
    }

    if ($date!= "") {

         $vehicle_cash_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id='$c_id' ORDER BY date");

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

             
                $QueryForCashbook = mysqli_query($conn, "SELECT SUM(cash_out) AS cash_out,SUM(cash_in) AS cash_in FROM  cashbook WHERE vehicle_id='$c_id'");

                $cashbook_cash = mysqli_fetch_array($QueryForCashbook);
                $cash_in_shows = $cashbook_cash['cash_in'];
                $cash_out_shows = $cashbook_cash['cash_out'];

                $total_balances = $cash_in_shows - $cash_out_shows;

        $table_daily_result .= ' 
                        <div class="card card-body p-3">
                            <table class="table table-striped" id="monthlyTable">
                                <thead>

                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Notes</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Credit</th>
                                        <th scope="col">Debit</th>
                                        <th scope="col"></th>
                                        <th scope="col">Details</th>
                                    </tr>

                                </thead>
                                <tbody>

        ';

        if ($vehicle_details != "") {
            $preAmount = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM vehicle_details WHERE status = 1 AND vehicle_id='$c_id' AND date < '$date'";
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
            
            $i = 1;
            $balance = $total_credit - $total_debit;
            while ($vehicle = mysqli_fetch_array($vehicle_details)) {
                
                if ($vehicle['type'] == 'Trip') {

                    $transaction_id = $vehicle['transaction_id'];
                    $tripObject = mysqli_query($conn, "SELECT * FROM trips WHERE id = '$transaction_id'");
                    $tripArray = mysqli_fetch_array($tripObject);

                    $customer_id = $tripArray['customer_id'];
                    $customerObj = mysqli_query($conn, "SELECT * FROM customers WHERE id = '$customer_id'");
                    $customerArray = mysqli_fetch_array($customerObj);
                    $customer_name = $customerArray['name'];

                    if ($vehicle['note'] == 'Not received') {
                        $balance = $balance - $vehicle['debit'];
                    } else {
                        $balance = $balance + $vehicle['credit'] - $vehicle['debit'];
                    }

                } else {
                    $balance = $balance + $vehicle['credit'] - $vehicle['debit'];
                }

                if ($vehicle['type'] == 'Balance' || $vehicle['type'] == 'Due') {
                    $transaction_id = $vehicle['transaction_id'];
                    $type = $vehicle['type'];
                    $table_daily_result .='<tr class="bg-success">
                    <td class="text-white" scope="row">'.$i++.'</td>
                    <td class="text-white">'.$vehicle['date'].'</td>
                    <td class="text-white">'.$vehicle['note'].'</td>';
                   if( $type =='Trip'){
                    $table_daily_result .='<td class="text-white">'.$vehicle['type'].'('.$customer_name.')</td>';
                   }else{
                    $table_daily_result .='<td class="text-white">'.$vehicle['type'].'</td>';
                   }
                    
                   $table_daily_result .=' <td class="text-white">'.$vehicle['credit'].'</td>
                    <td class="text-white">'.$vehicle['debit'].'</td>
                    <td class="text-white"></td>
                    <td class="text-center">
                        <i data-bs-toggle="modal" data-bs-target="#tripDetailsModal"
                            onclick="fetchDetails('.$transaction_id.',\''.$type.'\')"
                            class="bi bi-arrows-fullscreen text-white"></i>
                    </td>

                </tr>';
                } else if ($vehicle['type'] == 'Balance widhdrawn') {
                    $transaction_id = $vehicle['transaction_id'];
                    $type = $vehicle['type'];
                
                    $table_daily_result .= '<tr class="bg-danger">
                    <td class="text-white" scope="row">'. $i++.'</td>

                    <td class="text-white">'. $vehicle['date'].'</td>
                    <td class="text-white">'. $vehicle['note'].'</td>
                    <td class="text-white">'.$vehicle['type'].'</td>
                    <td class="text-white">'. $vehicle['credit'].'</td>
                    <td class="text-white">'. $vehicle['debit'].'</td>
                    <td class="text-white"></td>
                    <td class="text-center"> <i data-bs-toggle="modal" data-bs-target="#tripDetailsModal"
                            onclick="fetchDetails('.$transaction_id.',\''.$type.'\')"
                            class="bi bi-arrows-fullscreen text-white"></i></td>

                   </tr>';

               } else {
                        $transaction_id = $vehicle['transaction_id'];
                    // $tripObject = mysqli_query($conn, "SELECT * FROM trips WHERE id = '$transaction_id'");
                    // $tripArray = mysqli_fetch_array($tripObject);

                    // $customer_id = $tripArray['customer_id'];
                    // $customerObj = mysqli_query($conn, "SELECT * FROM customers WHERE id = '$customer_id'");
                    // $customerArray = mysqli_fetch_array($customerObj);
                    // $customer_name = $customerArray['name'];

                        $type = $vehicle['type'];
                        $current_id = $vehicle['id'];
                        $table_daily_result .= '<tr>
                                        <td scope="row">'.$i++.'</td>

                                        <td>'.$vehicle['date'].'</td>
                                        <td>'.$vehicle['note'].'</td>';
                                        if( $type =='Trip'){
                                         $table_daily_result .='<td >'.$vehicle['type'].' ('.$customer_name.') </td>';
                                        }else{
                                         $table_daily_result .='<td >'.$vehicle['type'].'</td>';
                                        }
                                         
                                        $table_daily_result .='
                                        <td>'.$vehicle['credit'].'</td>
                                        <td>'.$vehicle['debit'].'</td>
                                        <td></td>
                                        <td class="text-black text-center">';

                                            if ($type == "Trip" || $type == "Expense") {

                                                $table_daily_result .= '<i class="bi bi-arrows-fullscreen" data-bs-toggle="modal"
                                                data-bs-target="#tripDetailsModal"
                                                onclick="fetchDetails('.$transaction_id.',\''.$type.'\')"></i>';

                                            }else if($type == "Cash Received"){
                                                $table_daily_result .= '<i class="bi bi-arrows-fullscreen" data-bs-toggle="modal"
                                                data-bs-target="#tripDetailsModal"
                                                onclick="fetchDetails('.$current_id.',\''.$type.'\')"></i>';
                                            }else {
                                                $type = "Other";
                                                $table_daily_result .= ' <i class="bi bi-arrows-fullscreen" data-bs-toggle="modal"
                                                data-bs-target="#tripDetailsModal"
                                                onclick="fetchDetails('.$current_id.',\''.$type.'\')"></i>';

                                             }
                                             $table_daily_result .= '</td>

                                    </tr>';
                }}
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
}

?>
<script>


$(document).ready(function() {
    $('#monthlyTable').DataTable({
  
    order: [],

    dom: 'Bfrtip',
    buttons: [{
        className: 'btn btn-dark',
        extend: 'pdfHtml5',
        text: 'Download details',
        title: 'Monthly report details',
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
        doc.content.unshift({
            text: 'Monthly report',
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
