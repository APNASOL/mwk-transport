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

            <h1>Add Monthly Profit</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>

                    <li class="breadcrumb-item active">Profit</li>
                </ol>
            </nav>

            <?php  
                $customers = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");
            ?>

            <div class="card">
                <div class="card-body">
                    <!-- Floating Labels Form -->
                    <form class="row mt-2" action="Controllers/ProfitController.php" method="post" onsubmit="handleSubmit(event, this)">
                        
                    <div class="row mt-2">

                       <div class="col-md-2 mt-4">
                                <h5>Profit:</h5>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="amount" name="amount" required placeholder="Amount Note" oninput="calculateDistributedAmounts()">
                                <label for="amount">Profit Amount</label>
                            </div>
                        </div>

                        <div class="col-md-2 mt-4">
                                <h5>Expanse:</h5>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="expanse" name="expanse" required placeholder="Amount" oninput="calculateDistributedAmounts()">
                                <label for="expanse">Extra Expanse</label>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-2">
                        
                    

                        <div class="col">
                            <div class="form-floating">
                                <input type="text" class="form-control"  name="expanse_note" placeholder="Expanse Note" >
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
                                            if($m_result["code"]==$month){
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
                                <input type="date" class="form-control" id="date" name="date" required placeholder="date">
                                <label for="date">Date</label>
                            </div>
                        </div>

                        
                    </div>
<?php
                    $query = "SELECT * FROM partners WHERE status = 1 AND v_id='$v_id' ORDER BY id ASC";
        $result = mysqli_query($conn, $query);
        $i = 1;
        while ($row = mysqli_fetch_array($result)) {

            $p_id = $row["id"];
            $username = $row["name"];
            
            $percentage = $row["percentage"];
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
                        <div class="col-md-10"></div>
                     <div class="col-md-2 mt-2">
                        <div class="text-center">
                            <input type="hidden" name="cash_in_transaction" value="cash_in_transaction">
                            <button type="submit"
                                class="btn btn-dark btn-block" id="saveBtn">Add Profit</button>
                        </div>

                     </div>
                    </div>

                    </form>
            
                </div>
            </div>

        </div><!-- End Page Title -->
        

        
        

    </main><!-- End #main -->
    

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

                // Calculate the distributed amounts for each partner
            var totalPercentage = 0;

            percentageInputs.forEach(function (percentageInput, index) {
                var percentage = parseFloat(percentageInput.value) || 0;
                totalPercentage += percentage;
            });

            // Ensure total percentage is not greater than 100
            if (totalPercentage > 100) {
                alert("Total percentage cannot exceed 100. Adjusting percentages.");
                $("#saveBtn").hide();
                // percentageInputs.forEach(function (percentageInput, index) {
                //     var percentage = parseFloat(percentageInput.value) || 0;
                //     percentageInput.value = (percentage / totalPercentage) * 100;
                // });
                // totalPercentage = 100;
            }

            // Ensure total percentage is not greater than 100
            if (totalPercentage < 100) {
                alert("Total percentage cannot less 100. Adjusting percentages.");
                $("#saveBtn").hide();
                // percentageInputs.forEach(function (percentageInput, index) {
                //     var percentage = parseFloat(percentageInput.value) || 0;
                //     percentageInput.value = (percentage / totalPercentage) * 100;
                // });
                // totalPercentage = 100;
            }

            // Update amounts based on adjusted percentages
            if(totalPercentage == 100){
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
        
        }

</script>

    <style>
        .font-weight {
            font-weight: bold !important;
        }
    </style>
    
</body>

</html>