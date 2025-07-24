<?php include 'Master/head.php';
$conn = OpenCon();
session_start();
$v_id = $_SESSION['current_vehicle_id'];
$date = date("Y-m-d");
$currentdate = explode("-", $date);
$dat = $currentdate[2];
$month = $currentdate[1];
$year = $currentdate[0];
?>



<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Update record</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>

                    <li class="breadcrumb-item active">Profit Book Record Update</li>
                </ol>
            </nav>


            <?php



$cashbook_record_id = $_GET['cashbook_id'];
$Records = mysqli_query($conn, "SELECT * FROM profit_book WHERE id = '$cashbook_record_id'");
$record = mysqli_fetch_array($Records);

$t_id = $record['t_id'];
$vehicle_id = $record['vehicle_id'];

$cashbook_record_id = $record['id'];
$date = $record['date'];
$cash_out = $record['cash_out'];
$cash_in = $record['cash_in'];
$notes = $record['note'];
$type = $record['type'];

$m = $record['month'];

    $Consumer_record = mysqli_query($conn, "SELECT * FROM vehicles WHERE id = '$vehicle_id'");
    $consumer_obj = mysqli_fetch_array($Consumer_record);
    $consumer = $consumer_obj['number'];

    $consumer_id = $consumer_obj['id'];

    if($type == "profit") {

        $ExpanseRecords = mysqli_query($conn, "SELECT * FROM profit_book WHERE p_id = '$cashbook_record_id' AND type='expanse';");
        $expanse = mysqli_fetch_array($ExpanseRecords);
        
        $expanseId = $expanse['id'];
        $expanseAmount = $expanse['cash_out'];
        $expanseNote = $expanse['note'];
        $cashbook_id = $expanse['t_id'];

    }

?>
            <div class="card">
                <?php if($type != "profit") {?>
                <div class="card-body p-5">

                    <form class="row g-3" action="Controllers/ProfitController.php" method="post" onsubmit="handleSubmit(event, this)">
                            <input type="hidden" class="form-control" name="cashbook_record_id"
                            value="<?php echo $cashbook_record_id; ?>">
                            <input type="hidden" class="form-control" name="cashbook_id"
                            value="<?php echo $t_id; ?>">
                        
                            <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" readonly class="form-control" value="<?php echo $consumer; ?>">
                                
                                <input type="hidden" class="form-control" name="consume_id" required
                                    value="<?php echo $consume_id; ?>">
                                <label for="amount">Consumer</label>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="date" name="date" required
                                    value="<?php echo $date; ?>">
                                <label for="amount">Date</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="hidden" name="old_cash_in" value="<?php echo $cash_in; ?>">
                                
                                <?php if ($cash_in == '' || $cash_in == 0) {?>
                                <input type="text" readonly class="form-control" id="cash_in" name="cash_in" required
                                    value="<?php echo $cash_in; ?>">
                                <label for="amount">Cash in</label>
                               </div>
                            <?php } else {?>
                            <input type="text" class="form-control" id="cash_in" name="cash_in"
                                value="<?php echo $cash_in; ?>" required>
                            <label for="amount">Cash in</label>
                           </div>
                          <?php }?>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="hidden" name="old_cash_out" value="<?php echo $cash_out; ?>">
                                <?php if ($cash_in == '' || $cash_in == 0) {?>
                                <input type="text" class="form-control" id="cash_out" name="cash_out" required
                                    value="<?php echo $cash_out; ?>">
                                <?php } else {?>
                                <input type="text" readonly class="form-control" id="cash_out" name="cash_out"
                                    value="<?php echo $cash_out; ?>">
                                <?php }?>
                                <label for="amount">Cash out</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" readonly class="form-control" id="notes" name="notes" required
                                    value="<?php echo $notes; ?>">
                                <label for="amount">Notes</label>
                            </div>
                        </div>

                        <?php  if($type == "expanse") { 
                            ?>
                               <p style="color:red;">This is entry can update or delete from profit entry</p>
                            <?php
                        }else{?>
                        <div class="col-md-9"></div>
                        <div class="col-md-3">

                                <input type="hidden" name="profitbook_enteries_update"
                                    class="btn btn-dark" value="Update">
                                    
                                    <button type="submit" 
                                    class="btn btn-dark btn-block form-control"> Update</button>
                        
            
                        </div>
               

                        </form>

                        <br>
                        <form class="row g-3" action="Controllers/ProfitController.php" method="post" onsubmit="handleSubmit(event, this)">
                        
                                <input type="hidden"  name="cashbook_record_id" value="<?php echo $cashbook_record_id; ?>">
                                <input type="hidden" name="consume_id" value="<?php echo $consume_id; ?>">
                                <input type="hidden" name="old_cash_out" value="<?php echo $cash_out; ?>">
                                <input type="hidden" name="old_cash_in" value="<?php echo $cash_in; ?>">
                            
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <input  type="hidden" name="profitbook_enteries_delete" value="Delete">
                                <button type="submit" class="btn btn-danger btn-block form-control" onclick="return confirmDelete()">Delete</button> 
                            </div>
                                
                        </form>
                        <?php } ?>

                </div>
                <?php }else{?>

                    <div class="card-body">
                    <!-- Floating Labels Form -->
                    <form class="row mt-2" action="Controllers/ProfitController.php" method="post" onsubmit="handleSubmit(event, this)">
                        
                    <div class="row mt-2">
                        
                        <div class="col-md-3">
                            <input type="hidden" class="form-control" name="profit_id"
                            value="<?php echo $cashbook_record_id; ?>">
                            <input type="hidden" class="form-control" name="cashbook_id"
                            value="<?php echo $cashbook_id; ?>">

                            <div class="form-floating">
                                <input type="number" class="form-control" id="amount" name="amount" value="<?=$cash_in?>" required placeholder="Amount Note" oninput="calculateDistributedAmounts()">
                                <label for="amount">Profit Amount</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control"  name="amount_note" value="<?=$notes?>" required placeholder="Profit Note" >
                                <label for="expanse">Profit Message</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="expanse" name="expanse" value="<?=$expanseAmount?>" required placeholder="Amount" oninput="calculateDistributedAmounts()">
                                <label for="expanse">Extra Expanse</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control"  name="expanse_note" value="<?=$expanseNote?>" placeholder="Expanse Note" >
                                <label for="expanse">Expanse Message</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col">
                            <div class="form-floating">
                            <select class="form-control chosen" placeholder="" name="month" id="selected_month">
                                <?php
                                            $selectmonthquery = "SELECT * FROM months ORDER BY id";
                                            $selectmonth = mysqli_query($conn, $selectmonthquery);
                                            while ($m_result = mysqli_fetch_array($selectmonth)) { 
                                            if($m_result["name"]==$m){
                                                ?><option value="<?php echo $m_result["name"];?>" selected><?php echo $m_result["name"];?></option>
                                                <?php
                                                }else{
                                                     ?><option value="<?php echo $m_result["name"];?>"><?php echo $m_result["name"]; ?></option>
                                                     <?php
                                                }
                                            }?>
                                        </select>
                                <label for="date">Month</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating">
                                <input type="hidden" name="vehicle" value="<?php echo $current_vehicle_id ?>">
                                <input type="date" class="form-control" id="date" name="date" value="<?=$date?>" required placeholder="date">
                                <label for="date">Date</label>
                            </div>
                        </div>

                        
                    </div>
<?php
                    $query = "SELECT * FROM partner_profit_book WHERE status = 1 AND t_id='$cashbook_record_id' ORDER BY id ASC";
        $result = mysqli_query($conn, $query);
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {


            $p_id = $row["p_id"];

            $partner = mysqli_query($conn, "SELECT * FROM partners WHERE id = '$p_id'");
            $partnerRow = mysqli_fetch_array($partner);
            
            $username = $partnerRow["name"];
            
            $percentage = $partnerRow["percentage"];
?>
            <div class="row p-2 mt-3"> 
            <div class="col-md-3 mt-2">

                <h5><?=$username;?> Amount:</h5>
                
            </div>
            <div class="col-md-2">
               <input type="hidden" value="<?=$p_id;?>" name="p_id[]" id="pp_id<?=$i;?>'">
               <input type="number" class="form-control parcentage" name="parcentage[]" id="p_parcentage<?=$i;?>"  value="<?=$percentage;?>" readonly> 
            </div>

            <div class="col-md-1 mt-2"> 
                <b> % </b>
            </div>
        
            <div class="col-md-2">
               <input type="number" class="form-control" name="p_amount[]" id="pp_amount<?=$i;?>" placeholder="Enter Amount" readonly>
            </div>
            <div class="col-md-2">
               <input type="number" class="form-control" name="e_amount[]" id="ee_amount<?=$i;?>" placeholder="Enter Amount" readonly>
            </div>
            <div class="col-md-2">
               <input type="number" class="form-control" name="t_amount[]" id="tt_amount<?=$i;?>" placeholder="Enter Amount" readonly>
            </div>
        </div>
        <?php } ?>
        

                    <div class="row mt-2">
                        <div class="col-md-6"></div>
                    <div class="col-md-3 mt-2">
                        <div class="text-center">
                            
                            <input type="submit" name="delete_profit" value="Delete Profit"
                                class="btn btn-danger btn-block">
                        </div>

                     </div>
                     <div class="col-md-3 mt-2">
                        <div class="text-center">
                           
                            <input type="submit" name="update_profit" value="Update Profit"
                                class="btn btn-dark btn-block">
                        </div>

                     </div>
                    </div>

                    </form>
            
                </div>
                    <?php }?>
            </div>
        </div>



        </div>


    </main>

    <div class="modal fade" id="deleteRecord" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Cash Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <p>Are you sure you want to delete this record?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger " id="confirmDelete">Yes</button>
                    </div>
                </div>
            </div>
        </div>
<input type="hidden" id="recordId">

    <!-- ======= Footer ======= -->
    <?php include 'Master/footer.php';?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <?php include 'Master/scripts.php';?>
    <!-- Scripts -->

    <!-- custom style -->
    <style>
        .gap-2 {
            margin-left: 2px;
        }
    </style>
    <script>
calculateDistributedAmounts();
function calculateDistributedAmounts() {
            // Get the remaining amount
            var remainedAmount = parseFloat(document.getElementById('amount').value) || 0;
            var expanseAmount = parseFloat(document.getElementById('expanse').value) || 0;

            // Get all the partner input elements
            var percentageInputs = document.querySelectorAll('.parcentage');
            var amountInputs = document.querySelectorAll('[id^="pp_amount"]');
            var amountExpanseInputs = document.querySelectorAll('[id^="ee_amount"]');
            var amountTotalInputs = document.querySelectorAll('[id^="tt_amount"]');
            

            
                var totalDifferance = 0;
                var assginedAmount = 0;

            percentageInputs.forEach(function (percentageInput, index) {
                var percentage = parseFloat(percentageInput.value) || 0;
                var aCalculatedAmount = Math.round((percentage / 100) * remainedAmount);
                var calculatedAmount = (percentage / 100) * remainedAmount;
                var fractionalPart = calculatedAmount - Math.floor(calculatedAmount);

                var aECalculatedAmount = Math.round((percentage / 100) * expanseAmount);
                var eCalculatedAmount = (percentage / 100) * expanseAmount;
                var eFractionalPart = eCalculatedAmount - Math.floor(eCalculatedAmount);

                var total = aCalculatedAmount -aECalculatedAmount;
                amountInputs[index].value = isNaN(aCalculatedAmount) ? '' : aCalculatedAmount.toFixed(0);
                amountExpanseInputs[index].value = isNaN(aECalculatedAmount) ? '' : aECalculatedAmount.toFixed(0);
                amountTotalInputs[index].value = isNaN(total) ? '' : total.toFixed(0);
                // if(index != percentageInputs.length - 1){
                //     if(fractionalPart < 0.5){
                //       console.log("fractionalPart of => "+index+" is = "+fractionalPart);
                //        totalDifferance = totalDifferance + fractionalPart;
                //        console.log("total of => "+index+" is = "+totalDifferance);
                //     }
                // }
                
                // if (index === percentageInputs.length - 1) {
                //     console.log("totalDifferance of => "+index+" is = "+totalDifferance);
                //     aCalculatedAmount = Math.round(aCalculatedAmount + totalDifferance);
                //     assginedAmount = assginedAmount + aCalculatedAmount;
                    
                //     if(assginedAmount > remainedAmount){
                //          var differance = assginedAmount - remainedAmount;
                //         aCalculatedAmount = aCalculatedAmount -differance;
                //     }
                //     console.log("Total Remained Amount  = "+remainedAmount+" And Assigned AMount = "+assginedAmount);
                //     amountInputs[index].value = isNaN(aCalculatedAmount) ? '' : aCalculatedAmount.toFixed(0);
                // }else{
                //     assginedAmount = assginedAmount + aCalculatedAmount;
                //     amountInputs[index].value = isNaN(aCalculatedAmount) ? '' : aCalculatedAmount.toFixed(0);
                // }
            });
        
        }
    
    function handleSubmit(event, form) {
    //event.preventDefault(); // Prevent the form from submitting immediately

    var button = form.querySelector("button[type='submit']");
    button.disabled = true;
    button.innerText = 'Loading...'; // Optional: Change button text to indicate loading

    // Simulate an async operation (e.g., an API call or some processing)
    
}

// Handle delete button click in the modal
$('.deleteRecord').on('click', function () {
        var id = $(this).data("id");
        
        $('#recordId').prop("value", id);
    });

    // Handle delete button click in the modal
    $('#confirmDelete').on('click', function () {
        //alert("Delete record ");
        var id = $('#recordId').val(); 
        //alert("Delete record "+id);
        window.location.href = 'Controllers/CashBookController.php?cashbook_id=' + id +'&process=delete';
    });

    function confirmDelete() {
        return confirm("Are you sure you want to delete this record?");
    }

        $(document).ready(function () {
            $('#example').DataTable({
                searching: false,
                "paging": false,
                "info": false,
                order: [],

                dom: 'Bfrtip',
                buttons: [{
                    className: 'btn btn-dark',
                    extend: 'pdfHtml5',
                    text: 'Download details',
                    title: 'Cash Book details',
                    init: function (api, node, config) {
                        $(node).removeClass('btn-primary');
                        $(node).on('click', function () {
                            $(this).addClass('btn-success');
                        });
                    },

                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6] // export only columns
                    },
                    customize: function (doc) {
                        // Add a header to the PDF document
                        doc.content.unshift({
                            text: 'Cash Book details',
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

    <style>
        .font-weight {
            font-weight: bold !important;
        }
    </style>
</body>



</html>