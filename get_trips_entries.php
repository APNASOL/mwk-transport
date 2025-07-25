<?php
include 'Controllers/DatabaseController.php';
session_start();
$connect = OpenCon();
$current_vehicle_id = $_SESSION['current_vehicle_id'];
$pumps = mysqli_query($connect, "SELECT * FROM pumps where vehicle_id = '$current_vehicle_id'");
if (isset($_POST['number'])) {
    $table_result = '';
    $date = $_POST['date'];
    $number = $_POST['number'];

    $table_result .= '<div class="card-content p-3">
    <div class="dt-bootstrap">
    <div class="table-responsive">
    <form class="form-horizontal" method="POST" id="tripsForm" action="Controllers/TripsController.php" enctype="multipart/form-data" onsubmit="handleSubmit(event, this)">
    <div >    <table id="example" class="table table-bordered">
            <input type="hidden" class="form-control" id="trips_number" name="tripsNumber" value="' . $number . '">
            <input type="hidden" class="form-control" id="date" name="date" value="' . $date . '">
            <thead>
                   <tr>
                   <th>S#</th>
                   <th>Customer</th>
                   <th>Type</th>
                   <th>Truck Weight</th>
                   <th>Price per ton</th>
                   <th>Price</th>
                   <th>Expense</th>
                   <th>Total</th>
                   <th>Payment</th>

                   </tr>
            </thead>';
    $i = 1;
    while ($i <= $number) {
        $table_result .= ' <tr >  <td>' . $i . '</td>
            
            <td><select class="form-control chosen target" placeholder="Choose an product" name="c_name[]" id="c_name' . $i . '">';
                $selectuserquery = "SELECT * FROM customers WHERE status=1 AND vehicle_id=$current_vehicle_id  ORDER BY id";
                $selectuser = mysqli_query($connect, $selectuserquery);
                while ($p_s_result = mysqli_fetch_array($selectuser)) {
                    $table_result .= '<option value="' . $p_s_result["id"] . '">
                                                            ' . $p_s_result["name"] . ' </option>';
                }
                $table_result .= '
                </select></td>
            <td><select class="form-control chosen target c_load_type" placeholder="Choose an load type" name="load_type[]" id="load_type' . $i . '" data-id="' . $i . '">

                    <option Selected hidden>
                        Please select load type
                     </option>
                     <option Selected value="custom">Custom</option>
                    <option Selected value="coal">Coal</option>
            </select></td>

            <td class="c_custom_type" id="custom_type_block' . $i . '"><input type="text" class="form-control" id="custom_type' . $i . '" name="custom_type[]" placeholder="type">  </td>

            <td id="weight_block' . $i . '" ><input type="number" step="0.001" class="form-control" id="weight' . $i . '" name="weight[]" placeholder="weight"></td>
   
            
            <td id="price_per_ton_block' . $i . '"><input type="number" class="form-control c_price_per_ton" step="0.001" id="price_per_ton' . $i . '" name="price_per_ton[]" data-id="' . $i . '" placeholder="price per ton"></td>
            <td class="c_pp" id="c_pp' . $i . '">

            <td class="c_price" id="main_div_price' . $i . '"><input type="number" step="0.001" class="form-control c_price" id="price' . $i . '" name="price[]" data-id="' . $i . '" placeholder="price"></td>
            <td class="c_p" id="c_p' . $i . '">

            <td ><input type="number" step="0.001" class="form-control c_expense" id="expanse' . $i . '" name="expanse[]" data-id="' . $i . '" placeholder="expanse"></td>

            <td class="c_total_price"><input type="number" step="0.001" class="form-control c_total_bill" id="total_bill' . $i . '" name="total_bill[]" placeholder="Total bill"></td>

            <td><select class="form-control chosen target" placeholder="Choose an product" name="payment_status[]" id="payment_status' . $i . '">

            <option Selected hidden> Select status</option>
            <option Selected value="received">Received</option>
            <option Selected value="due">Due</option>

            </select></td>

                                        </tr> ';
        $i++;
    }

    $table_result .= '
    </table>
    </div>
<hr>
                                       <div class="row form-group">
                                            <div class="col-sm-6">
                                            <h5 class="card-title">Fuel Entry</h5>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-floating mb-3">
                                                    <select class="form-select" id="pump" name="pump_id" aria-label="vehicles" required>';
                                                        

                                                        while ($pump = mysqli_fetch_array($pumps)) {
                                                            $table_result .= '<option value="' . $pump["id"] . '">
                                                                                                    ' . $pump["name"] . ' </option>';
                                                        }
                                                        
                                                        $table_result .= '</select>
                                                    <label for="vehicle">Pumps</label>
                                                </div>
                                            </div> 

                                            <div class="col-sm-3">
 
                                                
                                                <div class="form-floating">
                                                <input type="text" class="form-control" id="pump_bill" name="pump_bill" placeholder="Total bill">
                                                    <label for="balnce">Total bill</label>
                                                </div>
                                                 
                                            </div>

                                        </div>
                                        <hr>
                                          
                                           <div class="row form-group">

                                                <div class="col-sm-8">
                                                   <h5 class="card-title">Select Photo to Upload:</h5>
                                                </div>

                                                <div class="col-sm-4">
                                                   <input type="file" name="photo" id="photo" class="form-control" required>
                                                </div>
                                                 
                                           </div>    

                                        <hr>
                                        
                                        <div class="row">
                                            <div class="col-md-3 "> <h5 class="card-title">Expanse Entry</h5>
                                            </div>
                                        </div>
                                        ';

                                        $common_expenses = mysqli_query($connect,"SELECT * FROM common_expenses_types");
                                        while ($expanse = mysqli_fetch_array($common_expenses)) {

                                         $table_result .='<div class="row">
                                            
                                         <div class="col-md-3" >
                                         <label for="amount">'.$expanse["name"].'</label>
                                         <input type="hidden" class="form-control" id="f_expense_name" name="f_expense_names[]" value='.$expanse["name"].'>
                                     </div>
                                        <div class="col-md-3">
                                         
                                         <input type="number" class="form-control" id="f_amount" name="f_amounts[]"
                                             placeholder="Amount">
                                       </div>';

                                       if($expanse["name"] == "Vehicle Repair"){ 
                                            $table_result .='<div class="col"><input type="text" class="form-control" id="message" name="repair_message"
                                             placeholder="Repair Message"> </div>';
                                       }

                                       

                                                $table_result .=' </div>
                                                <br>';

                                        }

                                        $table_result .='

                                       
                                            <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id;?>">

                                            <input type="hidden" class="form-control" id="selectDate" name="date" value="' . $date . '">

                                            <div class="row">
                                            
                                            <div class="col-md-3" id="">
                                                <label for="expense_name"> Custom Expense name</label>
                                                <input type="text" class="form-control" id="expense_name" name="expense_name"
                                                    placeholder="Expense expense name" >
                                            </div>
                                               <div class="col-md-3">
                                                <label for="amount">Amount</label>
                                                <input type="number" class="form-control" id="amount" name="amount"
                                                    placeholder="Amount">
                                              </div>
                                              <div class="col-md-2 text-center mt-4">
                                                   <button type="button" id="submitBtn"
                                                    class="btn btn-dark form-control">Add</button>
                                              </div>
                                            
                                            
                                             </div>

                                             <hr>

                                             <div id="expanses_date">
                                             <table class="table table-striped">
                                             <thead>
                                                 <tr>
                                                     <th scope="col">#</th>
                                                     <th scope="col">Name</th>
                                                     <th scope="col">Amount</th>
                                                     <th scope="col">Action</th>
                                                 </tr>
                                             </thead>
                                            <tbody>
                                             <tr><td colspan="4" align="center">No expanse added yet</td></tr>
                                            </tbody>
                                            </table>
                                             </div>
                                             
                                         
<br>

                                       <div class="row form-group">
                                            <div class="col-sm-9"></div>
                                            <div class="col-sm-3">
 
                                                <input type="hidden" name="btn_save_all" value="1">
                                                <button type="submit" id="saveBtn" class=" form-control btn btn-dark" > Save All </button>
                                                 
                                            </div>

                                        </div>
    </form>

                             </div>
                          </div>
                      </div>';
    echo $table_result;
}

?>
<script>

    var id;
    var expanseId;
    var defaultExpanse;

    function getData(date) {
           // alert(date);
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

// function preventFormSubmission(event) {
//             event.preventDefault();
//         }

function handleSubmit(event, form) {
    //event.preventDefault(); // Prevent the form from submitting immediately

        var button = form.querySelector("button[type='submit']");
        button.disabled = true;
        button.innerText = 'Loading...'; // Optional: Change button text to indicate loading

    // Simulate an async operation (e.g., an API call or some processing)
    
    }

    $(document).ready(function () {
            var date = $('#selectDate').val();
            
            getData(date);

            $(".c_custom_type").hide();
            $(".c_p").hide();
            $(".c_pp").hide();
            $(".c_price").attr("readonly","readonly");
            $(".c_total_bill").attr("readonly","readonly");
            $("#price_per_ton_block").show();

            $("#expense_default_section").show();
            $("#expense_custom_section").hide();
            // $("#default_btn").hide();
            // $("#custom_btn").show();
            defaultExpanse = 1;

            // Changing load type to hide or show the fields realted to load type
            $(".c_load_type").change(function(){
                    id = $(this).data('id');
                    var load_type = $('#load_type'+ id).val();
                    // alert(id);
                    // alert(load_type);
                    if(load_type == 'custom')
                    {
                        // var price = $('#price').val();
                        $("#custom_type_block"+ id).show();
                        $("#c_p"+ id).show();
                        $("#c_pp"+ id).show();
                        $("#weight_block"+ id).hide();
                        $("#main_div_price"+ id).hide();
                        $("#price_per_ton_block"+ id).hide();
                        $("#price"+ id).removeAttr("readonly");
                        $("#total_bill"+ id).removeAttr("readonly");
                    }
                    if(load_type == 'coal')
                    {
                        // var price = $('#price').val();
                        $("#weight_block"+ id).show();
                        $("#custom_type_block"+ id).hide();
                        $("#c_p"+ id).hide();
                        $("#c_pp"+ id).hide();
                        $("#main_div_price"+ id).show();
                        $("#price_per_ton_block"+ id).show();
                        $("#price"+ id).show();
                        $("#price"+ id).attr("readonly","readonly");
                        $("#total_bill"+ id).attr("readonly","readonly");
                    }
            });


            $(".c_price_per_ton").change(function(){
                  id = $(this).data('id');
                  var weight = $('#weight' + id).val();
                  var price_per_ton = $('#price_per_ton' + id).val();
                  var total = parseFloat(weight) * parseFloat(price_per_ton);
                  $('#price' + id).val(total);  

            });

            $(".c_price").change(function(){
                id = $(this).data('id');
                var price = $('#price' + id).val();
                var expense = $('#expanse'+id).val();
                var total = parseFloat(price) + parseFloat(expense);
                $('#total_bill'+id).val(total);
            });

            // the below event is for price - expense =  total <-- change in expense
            $(".c_expense").change(function(){
                id = $(this).data('id');
                var price = $('#price' + id).val();
                var expense = $('#expanse' + id).val();
                var total = parseFloat(price) + parseFloat(expense);
                $('#total_bill' + id).val(total);
            });

        var table = $('#example2').DataTable({
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
        });

        
    });

    // document.getElementById("customBtn").addEventListener("click", function(event) {
    //     const form = document.getElementById("tripsForm");
    //    // form.addEventListener("submit", preventFormSubmission);
    //     $("#expense_default_section").hide();
    //         $("#expense_custom_section").show();
    //         $("#default_btn").show();
    //         $("#custom_btn").hide();
    //         defaultExpanse = 0;
    // });

    // document.getElementById("defaultBtn").addEventListener("click", function(event) {
    //     //const form = document.getElementById("tripsForm");
    //     //form.addEventListener("submit", preventFormSubmission);
    //     $("#expense_default_section").show();
    //         $("#expense_custom_section").hide();
    //         $("#default_btn").hide();
    //         $("#custom_btn").show();
    //         defaultExpanse = 1;
    // });

    document.getElementById("saveBtn").addEventListener("click", function(event) {
        //const form = document.getElementById("tripsForm");
//event.preventDefault(); // Prevent default form submission
       // console.log("Form submitted.");
        //form.addEventListener("submit", preventFormSubmission);
    });

    document.getElementById("submitBtn").addEventListener("click", function(event) {
        //const form = document.getElementById("tripsForm");
        //form.addEventListener("submit", preventFormSubmission);
    // Gather form data
    var amount = document.getElementById("amount").value;
    var name = document.getElementById("expense_name").value;
    var date = document.getElementById('selectDate').value;
    

    if(amount == "" || name==""){
      alert("Both name and amount required");
    }else{
    var formData = new FormData();
    formData.append("name", name);
    formData.append("amount", amount);
    formData.append("date", date);

    // Send data to the server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "insert_data.php", true);
    xhr.onreadystatechange = function() {
        //getData(date);
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Handle the server's response
        //alert(xhr.responseText);
        if(xhr.responseText == "Data inserted successfully") {
            
            $("#expense_default_section").show();
            $("#expense_custom_section").hide();
            $("#default_btn").hide();
            $("#custom_btn").show();
            defaultExpanse = 1;
            document.getElementById("amount").value="";
           // alert("the date: "+date);
            getData(date);
        }// Display success message
      }
    };
    xhr.send(formData);
}

  });

</script>
