<?php
include 'Controllers/DatabaseController.php';
$conn = OpenCon();
session_start();
if (isset($_POST['month'])) {
    $table_daily_result = '';
    $month = $_POST['month'];
    $year = $_POST['year'];
    $c_id = $_SESSION['current_vehicle_id'];
    $v_name = $_SESSION['current_vehicle_number'];

 
    if ($c_id != "empty") {
    $vehicle_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id =  $c_id and YEAR(date) = '$year' AND MONTH(date) = '$month' group BY date");

  
    }

    if ($month != "" && $year != "") {

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

        $table_daily_result .= ' <div class="row p-2">

                            <!-- Left side columns -->
                            <div class="col-lg-12 ">
                                <div class="row">

                                    <!-- Revenue Card -->
                                    <div class="col-xxl-4 col-md-4">
                                        <div class="card info-card revenue-card">


                                            <div class="card-body">
                                                <h5 class="card-title">Vehicle cash in</h5>

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
                                                <h5 class="card-title">Vehicle cash out</h5>

                                                <div class="d-flex align-items-center">

                                                    <div class="font-weight">
                                                        <h4>'.$cash_out_show.'</h4>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- End expense Card -->

                                    <!-- fuel Card -->
                                    <div class="col-xxl-4 col-md-4">

                                        <div class="card info-card revenue-card">

                                            <div class="card-body">
                                                <h5 class="card-title">Vehicle balance </h5>

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
                        <!-- CODE ENDED for Vehicle boxs -->

                        <!-- code started for boxs -->
                        <div class="row p-2">

                            <!-- Left side columns -->
                            <div class="col-lg-12">
                                <div class="row">

                                    <!-- Revenue Card -->
                                    <div class="col-xxl-4 col-md-4">
                                        <div class="card info-card revenue-card">


                                            <div class="card-body">
                                                <h5 class="card-title">Company cash in</h5>

                                                <div class="d-flex align-items-center">

                                                    <div class="font-weight">
                                                        <h4>'.$cash_in_shows.'</h4>


                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- End Revenue Card -->

                                    <!-- expense Card -->
                                    <div class="col-xxl-4 col-md-4">


                                        <div class="card info-card revenue-card">

                                            <div class="card-body">
                                                <h5 class="card-title">Company cash out</h5>

                                                <div class="d-flex align-items-center">

                                                    <div class="font-weight">
                                                        <h4>'.$cash_out_shows.'</h4>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div><!-- End expense Card -->

                                    <!-- fuel Card -->
                                    <div class="col-xxl-4 col-md-4">

                                        <div class="card info-card revenue-card">

                                            <div class="card-body">
                                                <h5 class="card-title">Company balance </h5>

                                                <div class="d-flex align-items-center ">

                                                    <div class="font-weight">
                                                        <h4>'.$total_balances.'</h4>

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
                                        <th scope="col">Trips</th>
                                        <th scope="col">Credit</th>
                                        <th scope="col">Debit</th>
                                        <th scope="col">Balance</th>
                                        <th scope="col">Details</th>
                                    </tr>

                                </thead>
                                <tbody>

        ';

        if ($vehicle_details != "") {
            $preAmount = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE status = 1 AND vehicle_id='$c_id' AND date < '$year-$month-1'");

            $total_credit = 0;
            $total_debit = 0;
            while ($record = mysqli_fetch_array($preAmount)) {

                if ($record['type'] == 'Trip') {
                   
                       if ($record['note'] == 'Not received') {
                            $total_credit = $total_credit + 0;
                            $total_debit = $total_debit + $record['debit'];
                       } else {
                            $total_credit = $total_credit + $record['credit'];
                            $total_debit = $total_debit + $record['debit'];
                        }
                } else {
                   $total_credit = $total_credit + $record['credit'];
                   $total_debit = $total_debit + $record['debit'];
                                   }
               }


        if ($total_credit == '') {
            $total_credit = 0.0;
        }

        if ($total_debit == '') {
            $total_debit = 0.0;
        }
            
            $i = 1;
            $balance = $total_credit - $total_debit;
            while ($vehicle = mysqli_fetch_array($vehicle_details)) {

                $trips = 0;
                $today_credit = 0;
                $today_debit = 0 ;

                $s_date = $vehicle["date"];

                $today_vehicle_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id =  $c_id and date = '$s_date'");
                while ($today = mysqli_fetch_array($today_vehicle_details)) {

                   if ($today['type'] == 'Trip') {
                        $trips++;
                            if ($today['note'] == 'Not received') {

                                $today_credit = $today_credit + 0;
                                $today_debit = $today_debit + $today['debit']; 

                                $balance = $balance - $today['debit'];
                            } else {
                                $balance = $balance + $today['credit'] - $today['debit'];

                                $today_credit = $today_credit + $today['credit'];
                                $today_debit = $today_debit + $today['debit'];
                            }

                    } else {
                                $today_credit = $today_credit + $today['credit'];
                                $today_debit = $today_debit + $today['debit'];
                               $balance = $balance + $today['credit'] - $today['debit'];
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

            $date = $vehicle['date'];
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
                    <td >'. $res.'</td>
                    <td >'.$trips.'</td>
                    <td >'. $today_credit.'</td>
                    <td >'.$today_debit.'</td>
                    <td >'.$balance.'</td>
                    <td class="text-center">
                    <a href="vehicle_single_details.php?date='.$vehicle['date'].'&process=edit"> <i class="bi bi-arrows-fullscreen"></i></a>
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
        title: 'Monthly Vehicle Details \n Vehicle:  '+vehicle+'  ('+month+','+year+')',
        messageTop: 'Print Date:'+today,
        init: function (api, node, config) {
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
            $(this).addClass('btn-success');
        });
        },

        exportOptions: {
        columns: [0, 1, 2, 3, 4, 5] // export only columns
        },
        customize: function (doc) {
        // Add a header to the PDF document
    
        
        doc.styles.tableHeader.alignment="left";
                    doc.content[2].table.widths = ["5%","25%","10%","20%","20%","20%"];

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
