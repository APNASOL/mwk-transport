<?php   
include 'Controllers/DatabaseController.php';
$connect = OpenCon();
session_start();
$current_vehicle_id = $_SESSION['current_vehicle_id'];
$current_vehicle_number = $_SESSION['current_vehicle_number'];

if(isset($_POST['f_date'])){
    $table_result='';
    $f_date=$_POST['f_date'];
    $t_date=$_POST['t_date'];
    $p_id = $_POST['p_id'];

    $isPartner = false;
    $stmtpartners = mysqli_query($connect, "SELECT * FROM partners WHERE id='$p_id' AND status = 1");
    if(mysqli_num_rows($stmtpartners) > 0){
      $isPartner = false;
    }else{
      $isPartner = true;
    }
    foreach ($stmtpartners as $value) {
      $partner_name= $value['username'];
    }

    if($isPartner){

      $query = "SELECT * FROM partner_details 
      WHERE status=1 AND date Between '$f_date' And '$t_date' AND p_id IN (
        SELECT id FROM partners WHERE username='$p_id'
    ) ORDER BY date ASC,id ASC";
  
    }else{
         
      $query = "SELECT * FROM partner_details WHERE p_id='$p_id' AND status=1 AND date Between '$f_date' And '$t_date' ORDER BY date ASC,id ASC";
          
    }

        $result = mysqli_query($connect, $query);

        if (mysqli_num_rows($result) > 0) {
          $table_result .= '
              <div class="card">
              ';
              if($isPartner){
                $table_result .= '
                <input type="hidden" class="form-control" name="partner_name" id="partner_name" value='.$p_id.'>
                <input type="hidden" class="form-control" name="mine_name" id="mine_name" value="All Mines">
                ';}else{
                  $table_result .= '
                  <input type="hidden" class="form-control" name="partner_name" id="partner_name" value='.$partner_name.'>
                  <input type="hidden" class="form-control" name="mine_name" id="mine_name" value='.$current_vehicle_number.'>
                  ';
                }

                $table_result .= ' 
                
                <div class="card-content p-2">
                  <div class=" dt-bootstrap"> 
                      <div class="table-responsive">  
                        <table id="example" class="table">
                          <thead>
                          <tr>    
                      <th width="10%">Date</th>
                      <th width="25%">Message</th>
                      <th width="10%">Debit</th>
                      <th width="10%">Credit</th>
                      <th width="10%">Balance</th>
                      
                                            </tr>
                              </thead>';

                              if($isPartner){
                                $preAmount = "SELECT SUM(credit) AS cash_in_value, SUM(debit) AS cash_out_value FROM partner_details WHERE status=1 AND date < '$f_date' AND p_id IN (
                                  SELECT id FROM partners WHERE username='$p_id'
                              )"; 
                              }else{
                                $preAmount = "SELECT SUM(credit) AS cash_in_value, SUM(debit) AS cash_out_value FROM partner_details WHERE p_id='$p_id' AND status=1 AND date < '$f_date'";  
                              }
                                   
                    
                            $preAmountResult = mysqli_query($connect, $preAmount);
                            $row3 = mysqli_fetch_array($preAmountResult);
                            $total_cashin =  $row3["cash_in_value"];
                            $total_cashout =   $row3["cash_out_value"];

            
            
            $allAmount =$total_cashin - $total_cashout;

          // echo "The preamount =$allAmount";

          while ($row = mysqli_fetch_array($result)) {
           
            $id = $row["id"];
            $p_id = $row["p_id"];
            $t_id = $row["t_id"];
            $type = $row["type"];
            $note = $row["note"];
            $credit = $row["credit"];
            $debit = $row["debit"];
            $date = $row["date"];


            
            $allAmount =$allAmount + $credit - $debit;
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

            $table_result .= '
            <td width="10%">' . $res . '</td>';
           
              $table_result .= ' <td width="25%">';
              if($isPartner){
                $table_result .= '<a href="partner_old_amount.php?order_id='.$id .'">' . $note . ' (Vehicle : '.$current_vehicle_number.')</a>';
              }else{
                $table_result .= '<a href="partner_old_amount.php?order_id='.$id .'">' . $note . '</a>';
              }
              
              $table_result .= '</td>';
            
            $table_result .= '<td width="10%" class="text-success">' . $credit . '</td>  
            <td width="10%" class="text-danger">'.  $debit.'</td>';
            if($allAmount > 0){
            $table_result .= '<td width="10%">' . $allAmount . '</td>';
            }else{
              $table_result .= '<td width="10%" class="text-danger">' . $allAmount . '</td>';
            }
            $table_result .= '</tr>';
              
          }
          $table_result .=  '</table>
              </div>
              </div>
              </div>
              </div>';
          echo $table_result;
        } else {
          $table_result .= '<div class="table-responsive">  
                      <table id="example" class="table table-bordered">
                      <thead>
                      <tr>   
                       <th width="10%">Date</th>
                       <th width="25%">Message</th>
                       <th width="10%">Credit</th>
                       <th width="10%">Debit</th>
                       <th width="10%">Balance</th>
                       
                                             </tr>
                          </thead>
                          <tr>
                          <td colspan="6" align="center"> No Record found</td>
                          </tr>
                          </table>
                          </div>
                          </div>
                          </div>
                          </div>';
          echo $table_result;
        }
      }
            
    ?>
<script>
var today = new Date();
var mine =document.getElementById("mine_name").value;
var partner =document.getElementById("partner_name").value;

// var month =document.getElementById("m_name").value;
// var year =document.getElementById("y_name").value;
// var weight =document.getElementById("total_weight").value;
//  var amount =document.getElementById("t_amount").value;
//  var order =document.getElementById("t_order").value;

    $(document).ready(function() {
     var table = $('#example').DataTable({
         order:[],
       "lengthMenu": [
         [20, 40, 50, -1],
         [20, 40, 50, "All"]
       ],
       dom: 'Bfrtip',
       buttons: [
             {
                 extend: 'pdfHtml5',
                 orientation: 'portrait',
                 pageSize: 'A4',
                 title: 'Partner '+partner+' Details ( '+mine+' )',
                 messageTop: 'Print Date:'+today,
                 customize : function(doc){
                     doc.styles.tableHeader.alignment="left";
                     doc.content[2].table.widths = ["25%","20%","15%","15%","15%"];
                     for (var row = 1; row < doc.content[2].table.body.length; row++) {
                        var rowData = doc.content[2].table.body[row];
                        rowData[2].color = 'red';
                        rowData[3].color = 'green';
                        // Get the value of column 3 and set column 4 color based on its value
                        var column4Value = rowData[4].text;
                        if (column4Value < 0) {
                          rowData[4].color = 'red'; 
                        }
                      }
                 }
                
            },
         ],
     });
   });
</script>