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
                                <th scope="col">Vehicle</th>
                                <th scope="col">Pump</th>
                                <th scope="col">Date</th>
                                <th scope="col">Bill</th>
                                <th scope="col">Balance</th>
                                <th scope="col">Action</th>
                              </tr>
                                </thead>
                                <tbody>

        ';

        $fuel_array = mysqli_query($conn, "SELECT * FROM fuel where vehicle_id = '$c_id' AND YEAR(date) = '$year' AND MONTH(date) = '$month' ORDER BY date");

        $total_balance = 0;
        $i = 1;
        while ($fuel = mysqli_fetch_array($fuel_array)) {
            $vehicle_id = $fuel['vehicle_id'];
            $pump_id = $fuel['pump_id'];
            $vehicle = mysqli_query($conn, "SELECT * FROM vehicles  WHERE id = $vehicle_id");
        
            if (mysqli_num_rows($vehicle) > 0) {;
                $vehicle_array = mysqli_fetch_array($vehicle);
            }
        
            $pump = mysqli_query($conn, "SELECT * FROM pumps WHERE id = $pump_id");
        
            if (mysqli_num_rows($pump) > 0) {
                $pump_array = mysqli_fetch_array($pump);
            }
            $total_balance = $total_balance + $fuel['balance'];
            $date = $fuel['date'];
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
                    <td >'. $vehicle_array['number'].'</td>
                    <td >'.$pump_array['name'].'</td>
                    <td >'. $res.'</td>
                    <td >'.$fuel['balance'].'</td>
                    <td >'.$total_balance.'</td>
                    <td class="text-center">
                    <a href="Controllers/FuelController.php?fuel_id='.$fuel['id'].'&process=edit"> <i class="bi bi-pencil-square"></i></a>
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
        title: 'Monthly Feul Details \n Vehicle:  '+vehicle+'  ('+month+','+year+')',
        messageTop: 'Print Date:'+today,
        init: function (api, node, config) {
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
            $(this).addClass('btn-success');
        });
        },

        exportOptions: {
        columns: [0, 2, 3, 4, 5] // export only columns
        },
        customize: function (doc) {

        doc.styles.tableHeader.alignment="left";
        doc.content[2].table.widths = ["5%","30%","30%","20%","15%"];

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
