<?php
include 'Controllers/DatabaseController.php';
session_start();
$connect = OpenCon();
$current_vehicle_id = $_SESSION['current_vehicle_id'];
if (isset($_POST['number'])) {
    $table_result = '';
    $date = $_POST['date'];
    $number = $_POST['number'];

    $table_result .= '<div class="card-content p-3">
    <div class="dt-bootstrap">
    <div class="table-responsive">
    <form class="form-horizontal" method="POST" id="tripsForm" action="Controllers/TripsController.php">
    <div >    <table id="example" class="table table-bordered">
            <input type="hidden" class="form-control" id="trips_number" name="entries" value="' . $number . '">
            <input type="hidden" class="form-control" id="date" name="date" value="' . $date . '">
            <thead>
                   <tr>
                   <th>S#</th>
                   <th>Customer</th>
                   <th>Vehicle</th>
                   <th>Cash In</th>

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

            <td><select class="form-control chosen target" placeholder="Choose an product" name="v_name[]" id="v_name' . $i . '">';
                $vehicles = "SELECT * FROM vehicles WHERE status=1 ORDER BY id";
                $selectvehicles = mysqli_query($connect, $vehicles);
                while ($p_v_result = mysqli_fetch_array($selectvehicles)) {
                    $table_result .= '<option value="' . $p_v_result["id"] . '">
                                                            ' . $p_v_result["number"] . ' </option>';
                }
                $table_result .= '
            </select></td>


            <td ><input type="number" step="0.001" class="form-control cash_in" id="cash_in' . $i . '" name="cash_in[]" data-id="' . $i . '" placeholder="Cash Received"></td>

            

                                        </tr> ';
        $i++;
    }

    $table_result .= '
    </table>
    </div>
<hr>
            
                                             
                                         
<br>

                                       <div class="row form-group">
                                            <div class="col-sm-9"></div>
                                            <div class="col-sm-3">
 
                                                
                                                <input type="submit" id="saveBtn" name="btn_save_all_cash" class=" form-control btn btn-dark" value="Save All">
                                                 
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
