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
    $vehicle_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id =  $c_id and YEAR(date) = '$year' AND MONTH(date) = '$month' group BY date");
    }

    if ($month != "" && $year != "") {

        $table_daily_result .= ' <div class="card  card-body p-3">
                            <table class="table table-striped" id="monthlyTable">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Customer</th> 
                                    <th scope="col">Type</th>
                                    <th scope="col">Start date</th>
                                    <th scope="col">End date</th>
                                    <th scope="col">Total Bill</th>  
                                    <th scope="col">Action</th> 
                                </tr>
                                </thead>
                                <tbody>

        ';

       
            $trips_array = mysqli_query($conn, "SELECT * FROM trips where vehicle_id = '$c_id' AND YEAR(start_date) = '$year' AND MONTH(start_date) = '$month' ORDER BY end_date");

        $total_balance = 0;
        $i = 1;
        while ($trip = mysqli_fetch_array($trips_array)) {
            $vehicle_id = $trip['vehicle_id'];  
                  $customer_id = $trip['customer_id'];

                  $customer = mysqli_query($conn, "SELECT * FROM customers WHERE id = $customer_id");
                  
                  if(mysqli_num_rows($customer) > 0)
                  {
                    $customer_array = mysqli_fetch_array($customer); 
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
    
                $date = $trip['start_date'];
                $currentdate = explode("-", $date);
    
                $dat = $currentdate[2];
                $month = $currentdate[1];
                $year = $currentdate[0];
                $month_name = $months[$month - 1];
    
                $res = $dat . ' ' . $month_name . ' , ' . $year;

                $date1 = $trip['end_date'];
                $currentdate1 = explode("-", $date1);
    
                $dat1 = $currentdate1[2];
                $month1 = $currentdate1[1];
                $year1 = $currentdate1[0];
                $month_name1 = $months[$month1 - 1];
    
                $res1 = $dat1 . ' ' . $month_name1 . ' , ' . $year1;

               $table_daily_result .='<tr>
               <input type="hidden" id="m_name" value='.$month_name.'> 
               <input type="hidden" id="y_name" value='.$year.'> 
               <input type="hidden" id="v_name" value='.$v_name.'>
                    <td scope="row">'.$i++.'</td>
                    <td >'. $customer_array['name'].'</td>
                    <td >'.$trip['load_type'].'</td>
                    <td >'. $res.'</td>
                    <td >'.$res1.'</td>
                    <td >'.$trip['total_bill'].'</td>
                    <td class="text-center">
                    <a href="Controllers/TripsController.php?trip_id='.$trip['id'].'&process=edit"> <i class="bi bi-pencil-square"></i></a>
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
                        <td colspan="7" align="center"> No Record found</td>
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
        title: 'Monthly Trips Details \n Vehicle:  '+vehicle+'  ('+month+','+year+')',
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
        // Add a header to the PDF documen

        doc.styles.tableHeader.alignment="left";
        doc.content[2].table.widths = ["5%","25%","10%","20%","20%","10%"];

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
