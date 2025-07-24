<?php 
include 'Controllers/DatabaseController.php';
$conn = OpenCon();
session_start();
$current_vehicle_id = $_SESSION['current_vehicle_id'];
if (isset($_POST['date'])) {
$current_date = $_POST['date'];

$expenses = mysqli_query($conn, "SELECT * FROM temprory_vehicle_expense_storage WHERE vehicle_id = '$current_vehicle_id' AND date = '$current_date'");
 $total = 0;

 $table_result = '';

 $table_result .= '<table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                   <tbody>';
    
 $i = 1;
 while ($expense = mysqli_fetch_array($expenses)) { 
    $expense_name = $expense['expense_name'];
    $expense_amoumt = $expense['amount'];
    $expense_id = $expense['id'];
    $total = $total + $expense_amoumt;
    $table_result .='
    <tr>
    <th scope="row">'.$i++.'</th>
    <td>
        <input type="hidden" name="expense_names[]"
            value="'.$expense_name.'">'.$expense_name.'
    </td>

    <td>
        <input type="hidden" name="amounts[]" value="'.$expense_amoumt.'">
        '.$expense_amoumt.'
    </td>
    <td>

        
    <Button type="button" data-id="'.$expense_id.'" id="deleteExpanse" class="btn btn-sm fs-6 deleteExpanse" title="Delete"><i
                class="bi bi-trash"></i> 
                </button>
    </td>
</tr>
    ';

 }
 $table_result .='
  <tr class="p-5">

                                
                                <td colspan="2">Total</td>
                                <td>
                            
                                <input type="hidden" class="form-control" id="gSelectDate" name="date" value="' . $current_date . '">
                                    <input type="hidden" name="total_expanse" value="'.$total.'">
                                    '.$total.'
                                </td>
                                <td></td>

                            </tr> 
 
 </tbody></table>';
 echo $table_result;
}else{
    echo "NO data found";
}
 

?>
<script>

$(document).ready(function () {

    function getData(date) {
        //alert(date);
             $.ajax({
                 type: 'POST',
                 url: 'get_expanse_data.php',
                 data: {
                   date: date
                 },
                 dataType: 'text',
                 success: function (response) {
                     $('#expanses_date').html(response)
                 }
             });
}

    const valueButtons = document.querySelectorAll('.deleteExpanse');

    valueButtons.forEach(button => {
        button.addEventListener('click', function() {
            
            var id = $(this).data('id');

            var date = document.getElementById('gSelectDate').value;
            

            if(id == ""){
     alert("No expanse found");
   }else{
    var formData = new FormData();
         formData.append("expanse_id", id);
            
         var xhr = new XMLHttpRequest();
         xhr.open("POST", "delete_data.php", true);
         xhr.onreadystatechange = function() {
         if (xhr.readyState === 4 && xhr.status === 200) {
       // Handle the server's response
         //alert(xhr.responseText);
           if(xhr.responseText == "Data deleted successfully") {
            //alert("Date :"+date);
             getData(date);
           }// Display success message
          }
         };
         xhr.send(formData);
}
        
        });
    });
});
    </script>