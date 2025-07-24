<?php
include 'Controllers/DatabaseController.php';
$conn = OpenCon();
session_start();
$table_daily_result = '';
$customers = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");
$table_daily_result .= ' <div class="card  card-body p-3">
                            <table class="table table-striped" id="customersTable">
                            
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Customer name</th>
                                        <th scope="col">Contact #</th>
                                        <th scope="col">Dues</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody>';
                                
                                
                                $i = 1;

while ($customer = mysqli_fetch_array($customers)) {
  $due = 0;
 $customer_id = $customer['id'];
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
   if ($t_c_credit == "") {
         $t_c_credit = 0.0;
   }
 $c_debit = $details_row["c_debit"];
   if ($c_debit == "") {
         $c_debit = 0.0;
   }
                                             
 $due = $due + $c_debit - $c_credit+ $t_c_debit;
 if($due != 0){

    
               $table_daily_result .= ' <tr>
                  <input type="hidden" id="v_name" value='.$v_name.'>
                  <th scope="row">'.$i++.'</th>
                  <td>
                    <a href="Controllers/CustomerController.php?customer_id='.$customer["id"].'&process=details" type="button"
                         
                          title="Details">'.$customer["name"].'
                      </a>
                    </td>
                  <td>'. $customer["contact"].'</td>
                  <td>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#customerDifferance" data-id='.$customer["id"].' data-name='.$customer["name"].' data-amount='.$due.' class="setCustomer" title="setCustomer">
                          '.$due.'
                        </a>
                  </td>
                
                  <td>'.$customer["date"].'</td>
                  <td>

                    <a href="Controllers/CustomerController.php?customer_id='.$customer["id"].'&process=edit" type="button"
                        class="btn btn-sm fs-6"
                        title="Edit"><i class="bi bi-pencil-square"></i></a>

                        <a href="#" 
                          data-bs-toggle="modal" 
                          data-bs-target="#confirmDeleteModal"
                          data-id='.$customer["id"].'
                          class="btn btn-sm fs-6 deleteRecord"
                          title="Delete">
                          <i class="bi bi-trash"></i>
                        </a>


                  </td>
                </tr>';
              } }
           $table_daily_result .= ' </tbody>
          </table>';
          echo $table_daily_result;


?>

<script>
    
    $(document).ready(function() {
    
    $('#customersTable').DataTable({
    order: [],
    dom: 'Bfrtip',
    buttons: [{
        className: 'btn btn-dark',
        extend: 'pdfHtml5',
        text: 'Download details',
        title: 'All Customers \n Vehicle:  '+vehicle,
        messageTop: 'Print Date:'+today,
        init: function (api, node, config) {
            
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
            $(this).addClass('btn-success');
        });
        },

        exportOptions: {
        columns: [0, 1, 3] // export only columns
        },
        customize: function (doc) {
        // Add a header to the PDF document
        
        doc.styles.tableHeader.alignment="left";
        doc.content[2].table.widths = ["10%","60%","30%"];

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