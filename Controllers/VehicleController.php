<?php
include 'DatabaseController.php';
$conn = OpenCon();
session_start();
$current_vehicle_id = $_SESSION['current_vehicle_id'];
$current_vehicle_number = $_SESSION['current_vehicle_number'];


if($current_vehicle_id == 0 || $current_vehicle_id == ''){
    header('location:../vehicle-index.php?successMessage=Return');
    exit;
}

if (isset($_POST['create_vehicle'])) {
    $owner_name = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $vehicle_number = mysqli_real_escape_string($conn, $_POST['number']);
    $balance = mysqli_real_escape_string($conn, $_POST['balance']);
    $company_balance = mysqli_real_escape_string($conn, $_POST['company_balance']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $status = 1;

    $cash_in = 0;
    $cash_out = 0;

    if($company_balance >0){
        $cash_in = $company_balance;
    }else if($company_balance < 0){
        $cash_out = abs($company_balance);
    }

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "INSERT INTO vehicles (owner_name,number,balance,status,date) VALUES ('$owner_name','$vehicle_number','$balance','$status','$date')");

    $new_vehicle_id = mysqli_insert_id($conn);
    $vehicleDetailsqueryFired = mysqli_query($conn, "INSERT INTO vehicle_details (vehicle_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$new_vehicle_id','$status','$new_vehicle_id','Vehicle have balance','Old balance','$balance','0','0','$date')");

    if($company_balance != 0){
        $queryFiredCashbook = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id, cash_in, cash_out, date, notes, status) VALUES ('$new_vehicle_id','Company cash','$new_vehicle_id','$cash_in','$cash_out','$date','Company Old Cash','$status')");
    }

    // code for first time vehicle
    if($current_vehicle_id == "No vehicle added"){
      $vehicle = "SELECT * FROM vehicles LIMIT 1"; 
    
      $vehicle_record = mysqli_query($conn, $vehicle);

                // Check if any rows were returned
                if (mysqli_num_rows($vehicle_record) > 0) {
                    // Fetch the first row
                    $vehicle_row = mysqli_fetch_assoc($vehicle_record);
                     
                    // Display the data from the first row
                    $_SESSION['current_vehicle_id'] = $vehicle_row['id'];
                    $_SESSION['current_vehicle_number'] = $vehicle_row['number'];
                    mysqli_commit($conn);
                    header('location:../index.php?successMessage=First vehicle added now you can doing further process');
                    // Set autocommit back to true
                    mysqli_autocommit($conn, true);
                    exit;
                } 
                
    }
             
                // code end for first vehicle

    if ($queryFired && $vehicleDetailsqueryFired && $queryFiredCashbook) {
        mysqli_commit($conn);
        header('location:../vehicle-index.php?successMessage=New vehicle added successfully');
    } else {
        mysqli_rollback($conn);
        header('location:../vehicle-create.php?errorMessage=New vehicle adding error');
    }
     // Set autocommit back to true
     mysqli_autocommit($conn, true);
}

if(isset($_POST['vehicle_cash_update']) && $_POST['vehicle_cash_update'] == "Update"){

    $transaction_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);
    $consume_id = mysqli_real_escape_string($conn, $_POST['consume_id']);
    // echo $consume_id; exit;
    $cash_in = mysqli_real_escape_string($conn, $_POST['cash_in']);
    $old_cash_in = mysqli_real_escape_string($conn, $_POST['old_cash_in']);
    $cash_out = mysqli_real_escape_string($conn, $_POST['cash_out']);
    $old_cash_out = mysqli_real_escape_string($conn, $_POST['old_cash_out']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);


    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

        $vehicleQueryFired = mysqli_query($conn,"UPDATE vehicles SET balance = balance + $old_cash_in - $cash_in - $old_cash_out + $cash_out, date = '$date' WHERE id = '$current_vehicle_id'"); 

        $vehicleDetailsqueryFired = mysqli_query($conn, "UPDATE vehicle_details SET debit = '$cash_out', credit = '$cash_in', date = '$date' WHERE vehicle_id = '$current_vehicle_id' AND id = '$transaction_id'");  

        $queryFired = mysqli_query($conn, "UPDATE customers SET dues = dues + $old_cash_in - $cash_in - $old_cash_out + $cash_out, date = '$date' WHERE id = $consume_id");

        $queryFiredForDetailsTable = mysqli_query($conn, "UPDATE customer_details SET debit = '$cash_out', credit = '$cash_in', date = '$date' WHERE customer_id = '$consume_id' AND transaction_id = '$transaction_id'");

        if ($vehicleQueryFired && $vehicleDetailsqueryFired && $queryFiredForDetailsTable && $queryFired) {
            mysqli_commit($conn);
            header("location:../vehicle-details.php?vehicle_id=$current_vehicle_id&successMessage=Customer payment updated");
        } else {
            mysqli_rollback($conn);
            header("location:../vehicle-details.php?vehicle_id=$current_vehicle_id&errorMessage=Customer payment not updated");
        }

        // Set autocommit back to true
    mysqli_autocommit($conn, true);

}

if(isset($_POST["vehicle_cash_delete"]) && $_POST["vehicle_cash_delete"] == "Delete"){

    $transaction_id = $_POST['transaction_id'];

    $Records = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE id = '$transaction_id'");
    $record = mysqli_fetch_array($Records);

    $consume_id = $record['consume_id'];

    $operation_for = $record['note'];
    $cash_out = $record['debit'];
    $cash_in = $record['credit'];

    // echo "Consumer ID: $consume_id, Transaction Id: $transaction_id, Cash_Out: $cash_out, Cash_in: $cash_in";

    
        // Start a transaction
    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);


    $vehiclQueryFired = mysqli_query($conn,"UPDATE vehicles SET balance = balance - $cash_in + $cash_out WHERE id = '$current_vehicle_id'"); 


    $vehicleDetailsquery = "DELETE FROM vehicle_details WHERE id = '$transaction_id' AND vehicle_id = '$current_vehicle_id'";

    $vehicleDetailsqueryFired = mysqli_query($conn,$vehicleDetailsquery);  

    $queryFired = mysqli_query($conn, "UPDATE customers SET dues = dues + $cash_in - $cash_out WHERE id = $consume_id");

    $queryFiredForDetailsTable = mysqli_query($conn, "DELETE FROM customer_details WHERE customer_id = '$consume_id' AND transaction_id = '$transaction_id'");


    if ($vehiclQueryFired && $vehicleDetailsqueryFired && $queryFiredForDetailsTable && $queryFired) {
        mysqli_commit($conn);
        header("location:../vehicle-details.php?vehicle_id=$current_vehicle_id&successMessage=Customer payment deleted");
    } else {
        // Rollback the transaction if any UPDATE query fails
        mysqli_rollback($conn);
        header("location:../vehicle-details.php?vehicle_id=$current_vehicle_id&errorMessage=Customer payment not delete");
    }

    // Set autocommit back to true
    mysqli_autocommit($conn, true);

}

if (@$_GET['process'] == 'delete') {

    $vehicle_id = $_GET['vehicle_id'];

    $queryFired = mysqli_query($conn, "DELETE FROM vehicles WHERE id = $vehicle_id");
    if ($queryFired) {
        header('location:../vehicle-index.php?successMessage=Vehicle deleted successfully.');

    }

}

if (@$_GET['process'] == 'edit') {

    $vehicle_id = $_GET['vehicle_id'];
    if (isset($vehicle_id)) {
        header('location:../vehicle-update.php?vehicle_id=' . $vehicle_id);
    }
}

if (isset($_POST['update'])) {
    $owner_name = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $vehicle_number = mysqli_real_escape_string($conn, $_POST['number']);
    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "UPDATE vehicles SET owner_name = '$owner_name' , number = '$vehicle_number' , date = '$date' WHERE id = $vehicle_id");

    if ($queryFired) {
        mysqli_commit($conn);
        header('location:../vehicle-index.php?successMessage=Record updated successfully');
    } else {
        mysqli_rollback($conn);
        header('location:../vehicle-update.php?errorMessage=Record updating error' . '&vehicle_id=' . $vehicle_id);
    }
    // Set autocommit back to true
    mysqli_autocommit($conn, true);
}

if (@$_GET['process'] == 'details') {

    $vehicle_id = $_GET['vehicle_id'];
    if (isset($vehicle_id)) {
        header('location:../vehicle-details.php?vehicle_id=' . $vehicle_id);
    }

}

if (@$_GET['process'] == 'vehicle-income-statement') {

    $vehicle_id = $_GET['vehicle_id'];
    if (isset($vehicle_id)) {
        header('location:../vehicle-income-statements.php?vehicle_id=' . $vehicle_id);
    }

}

if (@$_GET['process'] == 'vehicle-financial-statement') {

    $vehicle_id = $_GET['vehicle_id'];
    if (isset($vehicle_id)) {
        header('location:../vehicle-financial-statements.php?vehicle_id=' . $vehicle_id);
    }

}

if (isset($_POST['search_income_statement'])) {
//  echo "testling";
    $year = $_POST['year'];
    $month = $_POST['month'];

    header('location:../vehicle-income-statements.php?year=' . $year . '&month=' . $month);

}

if (isset($_POST['temprory_vehicle_expense_storing'])) {
    
    if($_POST['default_expense_name'] != "")
    {
        $expense_name = $_POST['default_expense_name'];

    }else
    {
        $expense_name = $_POST['expense_name'];

    }

    // echo $expense_name;
    // echo $_POST['expense_name'];
    // exit();

    // $expense_name = $_POST['default_expense_name'];
    $amount = $_POST['amount'];
    $vehicle_id = $_POST['vehicle_id'];
    $current_date = date("Y-m-d");

    $queryFired = mysqli_query($conn, "INSERT INTO `temprory_vehicle_expense_storage`(`expense_name`, `amount`, `vehicle_id`, `date`) VALUES ('$expense_name','$amount','$vehicle_id','$current_date')");

    $expense_id = mysqli_insert_id($conn);

    if ($queryFired) {
        header('location:../vehicle-expenses-transaction.php?successMessage=Expense added successfully');
    }

}

if (isset($_POST['vehicle_expense_transaction'])) { 

    $expense_name = $_POST['expense_names'];
    $amount = $_POST['amounts'];
    $vehicle_id = $_POST['vehicle_id'];
    $date = $_POST['date'];
    $total = $_POST['total'];
    $status = 1;
    
    $TotalExpenseQuery = "INSERT INTO `vehicle_expenses`(`vehicle_id`, `end_date`, `total_expenses`) VALUES ('$vehicle_id','$date','$total')";

    $QueryFired = mysqli_query($conn, $TotalExpenseQuery);

    
    $LastExpenseId = mysqli_insert_id($conn);

    for ($i = 0; $i < count($_POST["expense_names"]); $i++) {

        $expenseId = $LastExpenseId;
        $expenseName = $_POST["expense_names"][$i];
        $Amount = $_POST["amounts"][$i];
        $status = 1;

        $ExpenseDetailsQuery = "INSERT INTO `vehicle_expenses_details`(`expense_id`, `expense_name`, `balance`, `status`) VALUES ('$expenseId','$expenseName','$Amount','$status')";
        $QueryFired = mysqli_query($conn, $ExpenseDetailsQuery);
    }

    $DeletTemproryStoredExpenses = mysqli_query($conn, "DELETE FROM temprory_vehicle_expense_storage WHERE vehicle_id = '$vehicle_id'");

    $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance - '$total' WHERE id = '$vehicle_id'");

    $vehicleDetailsqueryFired = mysqli_query($conn, "INSERT INTO vehicle_details (vehicle_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$vehicle_id','$status','$LastExpenseId','Vehicle expense added','Expense','0','$total','0','$date')");

    $queryIncomeFired = mysqli_query($conn, "INSERT INTO vehicle_income_operations ( `vehicle_id`, `type`, `transaction_id`, `amount`, `date`, `status`) VALUES ('$vehicle_id','Expense','$LastExpenseId','$total','$date','$status')");

    if ($TotalExpenseQuery and $DeletTemproryStoredExpenses and $VehiclequeryFired and $vehicleDetailsqueryFired) {
        header('location:../vehicle-details.php?vehicle_id=' . $vehicle_id);
    }

    exit();

    $queryFired = mysqli_query($conn, "INSERT INTO `temprory_vehicle_expense_storage`(`expense_name`, `amount`, `vehicle_id`, `date`) VALUES ('$expense_name','$amount','$vehicle_id','$current_date')");

    $expense_id = mysqli_insert_id($conn);

    if ($queryFired) {
        header('location:../vehicle-expenses-transaction.php?successMessage=Expense added successfully');
    }

}

if (isset($_POST['vehicle_expense_transaction_update']) && $_POST['vehicle_expense_transaction_update'] == "Update") { 
    
    $expense_id = $_POST['expense_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $ids = $_POST['ids'];
    $expense_name = $_POST['expense_names'];
    $amount = $_POST['amounts'];
    $current_expense_id = $expense_id;
    $total_new_amount = 0 ;


    $old_total_balance_of_vehicle = mysqli_query($conn,"SELECT * FROM vehicle_expenses WHERE id = '$expense_id'");
    $old_total_balance_of_vehicle_array = mysqli_fetch_array($old_total_balance_of_vehicle);
    $old_total_balance_of_vehicle_value = $old_total_balance_of_vehicle_array['total_expenses'];

    for ($i = 0; $i < count($_POST["expense_names"]); $i++) {

        $expenseId = $current_expense_id;
        $id = $_POST["ids"][$i];
        $expenseName = $_POST["expense_names"][$i];
        $Amount = $_POST["amounts"][$i];
        $status = 1;
        $total_new_amount += $Amount;

        $ExpenseDetailsQuery = "UPDATE vehicle_expenses_details SET expense_name = '$expenseName', balance = '$Amount' WHERE id = '$id'";
         
        $QueryFiredE = mysqli_query($conn, $ExpenseDetailsQuery);
    }

    $TotalExpenseQuery = "UPDATE vehicle_expenses SET total_expenses = '$total_new_amount' WHERE id = '$current_expense_id' AND vehicle_id = '$current_vehicle_id'";
  

    $QueryFired = mysqli_query($conn, $TotalExpenseQuery);

     

    $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance + '$old_total_balance_of_vehicle_value' - '$total_new_amount' WHERE id = '$current_vehicle_id'");

    $vehicleDetailsqueryFired = mysqli_query($conn, "UPDATE vehicle_details SET debit = '$total_new_amount' WHERE type = 'Expense' AND vehicle_id = '$current_vehicle_id' AND transaction_id='$current_expense_id'");

    $queryIncomeFired = mysqli_query($conn, "UPDATE vehicle_income_operations SET amount = '$total_new_amount' WHERE type = 'Expense' AND vehicle_id = '$current_vehicle_id' AND transaction_id='$current_expense_id'");

    if ($QueryFiredE && $QueryFired && $VehiclequeryFired && $vehicleDetailsqueryFired && $queryIncomeFired) {
        header('location:../vehicle-details.php?vehicle_id=' . $current_vehicle_id);
    }else{
        echo "Some error";
    }

}

if (isset($_POST['vehicle_expense_transaction_delete']) && $_POST['vehicle_expense_transaction_delete'] == "Delete") { 
    
    $expense_id = $_POST['expense_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $current_expense_id = $expense_id;
    $total_new_amount = 0 ;

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);


    $old_total_balance_of_vehicle = mysqli_query($conn,"SELECT * FROM vehicle_expenses WHERE id = '$expense_id'");

    $old_total_balance_of_vehicle_array = mysqli_fetch_array($old_total_balance_of_vehicle);
    $old_total_balance_of_vehicle_value = $old_total_balance_of_vehicle_array['total_expenses'];
    
   $QueryFiredE = mysqli_query($conn, "DELETE FROM vehicle_expenses_details WHERE expense_id = '$expense_id';");
    
    
   $QueryFired = mysqli_query($conn, "DELETE FROM vehicle_expenses WHERE id = '$expense_id';");

     

    $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance - '$old_total_balance_of_vehicle_value' WHERE id = '$current_vehicle_id';");

  $vehicleDetailsqueryFired = mysqli_query($conn, "DELETE FROM vehicle_details WHERE  type = 'Expense' AND vehicle_id = '$current_vehicle_id' AND transaction_id='$current_expense_id';");
    
    
   $queryIncomeFired = mysqli_query($conn, "DELETE FROM vehicle_income_operations WHERE   type = 'Expense' AND vehicle_id = '$current_vehicle_id' AND transaction_id='$current_expense_id'");

    if ($QueryFiredE && $QueryFired && $VehiclequeryFired && $vehicleDetailsqueryFired && $queryIncomeFired) {
        mysqli_commit($conn);
        header('location:../vehicle-details.php?vehicle_id=' . $current_vehicle_id);
    }else{
        mysqli_rollback($conn);
        header('location:../vehicle-details.php?vehicle_id=' . $current_vehicle_id);
    }
    // Set autocommit back to true
    mysqli_autocommit($conn, true);


}


if (@$_GET['process'] == 'single_expens_delete') {

    $single_expense_id = $_GET['single_expense_id'];

    $queryFired = mysqli_query($conn, "DELETE FROM temprory_vehicle_expense_storage WHERE id = $single_expense_id");
    if ($queryFired) {
        header('location:../vehicle-expenses-transaction.php?successMessage=Expense removed successfully');

    }

}

if (@$_GET['process'] == "EntryDetails") {

    $transaction_id = $_POST['transaction_id'];
    $type = $_POST['type'];
    $result = '';

    if ($type == "Trip") {
        $tripObject = mysqli_query($conn, "SELECT * FROM trips WHERE id = '$transaction_id'");
        $tripArray = mysqli_fetch_array($tripObject);

        $customer_id = $tripArray['customer_id'];
        $customerObj = mysqli_query($conn, "SELECT * FROM customers WHERE id = '$customer_id'");
        $customerArray = mysqli_fetch_array($customerObj);
        $customer_name = $customerArray['name'];

        $start_date = $tripArray['start_date'];
        $end_date = $tripArray['end_date'];
        $load_type = $tripArray['load_type'];
        $weight = $tripArray['weight'];
        $price = $tripArray['price'];
        $price_per_ton = $tripArray['price_per_ton'];
        $expense = $tripArray['expense'];
        $total_bill = $tripArray['total_bill'];
        $payment_status = $tripArray['payment_status'];

        $result .= '
                <div class="row card bg-white mb-3 p-3">

                <div class="row">
                    <div class="col-md-10"></div>

                    <div class="col-md-2">
                    
                <a href="Controllers/TripsController.php?trip_id='.$transaction_id.'&process=edit" class="btn btn-dark"> Update </a>
                </div>
                </div>

                    <div class="row">

                        <div class="col-md-6">
                            <span class="text-dark ">Type</b>
                        </div>
                        <div class="col-md-6">
                            ' . $type . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Customer</b>
                        </div>
                        <div class="col-md-6">
                            ' . $customer_name . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Load type</b>
                        </div>
                        <div class="col-md-6">
                            ' . $load_type . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Start date</b>
                        </div>
                        <div class="col-md-6">
                            ' . $start_date . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">End date</b>
                        </div>
                        <div class="col-md-6">
                            ' . $end_date . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Weight</b>
                        </div>
                        <div class="col-md-6">
                            ' . $weight . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Price</b>
                        </div>
                        <div class="col-md-6">
                            ' . $price . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Price/Ton</b>
                        </div>
                        <div class="col-md-6">
                            ' . $price_per_ton . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Expense</b>
                        </div>
                        <div  class="col-md-6">
                            ' . $expense . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Total Bill</b>
                        </div>
                        <div class="col-md-6">
                            ' . $total_bill . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Payment Status</b>
                        </div>
                        <div class="col-md-6">
                            ' . $payment_status . '</b>
                        </div>

                    </div>
                </div>';
        echo $result;
    }
    if ($type == "Balance" || $type == "Balance widhdrawn") {
        $cashbookObject = mysqli_query($conn, "SELECT * FROM cashbook WHERE id = '$transaction_id'");
        $cashbookArray = mysqli_fetch_array($cashbookObject);

        $cash_in = $cashbookArray['cash_in'];
        $cash_out = $cashbookArray['cash_out'];
        $date = $cashbookArray['date'];
        $notes = $cashbookArray['notes'];
        $operation_for = $cashbookArray['operation_for'];

        $result .= '
                <div class="row card bg-white mb-3 p-3">
                    <div class="row">

                        <div class="col-md-6">
                            <span class="text-dark ">Type</b>
                        </div>
                        <div class="col-md-6">
                            ' . $type . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Operation for</b>
                        </div>
                        <div class="col-md-6">
                            ' . $operation_for . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Cash in</b>
                        </div>
                        <div class="col-md-6">
                            ' . $cash_in . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Cash out</b>
                        </div>
                        <div class="col-md-6">
                            ' . $cash_out . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Date</b>
                        </div>
                        <div class="col-md-6">
                            ' . $date . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Notes</b>
                        </div>
                        <div class="col-md-6">
                            ' . $notes . '</b>
                        </div>

                    </div>
                </div>';
        echo $result;
    }

    if ($type == "Expense") {
        $expensesObject = mysqli_query($conn, "SELECT * FROM vehicle_expenses_details WHERE expense_id = '$transaction_id'");
        // $expenseArray = mysqli_fetch_array($expensesObject);

        $singleExpense = mysqli_query($conn, "SELECT * FROM vehicle_expenses WHERE id = '$transaction_id'");
        $ExpArray = mysqli_fetch_array($singleExpense);
        $date = $ExpArray['end_date'];
        $total_expenses = $ExpArray['total_expenses'];

        $i = 1;
        $result .= '
            <div class="row mb-3">
           
               <div class="col-md-2">Type : <b>Expense</b></div>


               <div class="col-md-4">Date: <b>' . $date . '</b></div>


               <div class="col-md-4">Total expense : <b>' . $total_expenses . '</b></div>
               <div class="col-md-2">
                <a href="expense_edit.php?transaction_id='.$transaction_id.'" class="btn btn-dark"> Update </a>
                </div>


            </div>
                 <table class="table table-striped">
                   <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Amount</th>
                    </tr>
                    </thead> ';

        while ($record = mysqli_fetch_array($expensesObject)) {
            $ExpenseName = $record['expense_name'];
            $ExpenseBalance = $record['balance'];

            $result .= '<tbody><tr>
                        <td>' . $i++ . '</td>
                        <td>' . $ExpenseName . '</td>
                        <td>' . $ExpenseBalance . '</td>
                    </tr>';}

        $result .= '</tbody>';

        $result .= '</table>';
        echo $result;
    }

    if ($type == "Cash Received") {
        $Object = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE id = '$transaction_id'");
        $Array = mysqli_fetch_array($Object);

        $id = $Array['id'];

        $date = $Array['date'];
        $debit = $Array['debit'];
        $credit = $Array['credit'];
        $note = $Array['note'];

        $i = 1;
        $result .= '
            <div class="row card bg-white mb-3 p-3">
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                        <a href="vehicle-cash-update.php?id='.$transaction_id.'" class="btn btn-dark"> Update </a>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">
                            <span class="text-dark ">Date</b>
                        </div>
                        <div class="col-md-6">
                            ' . $date . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Note</b>
                        </div>
                        <div class="col-md-6">
                            ' . $note . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Credit</b>
                        </div>
                        <div class="col-md-6">
                            ' . $credit . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Debit</b>
                        </div>
                        <div class="col-md-6">
                            ' . $debit . '</b>
                        </div>

                    </div>


            </div>';

        echo $result;
    }

    if ($type == "Other") {
        $Object = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE id = '$transaction_id'");
        $Array = mysqli_fetch_array($Object);

        $date = $Array['date'];
        $debit = $Array['debit'];
        $credit = $Array['credit'];
        $note = $Array['note'];

        $i = 1;
        $result .= '
            <div class="row card bg-white mb-3 p-3">
                    <div class="row">

                        <div class="col-md-6">
                            <span class="text-dark ">Date</b>
                        </div>
                        <div class="col-md-6">
                            ' . $date . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Note</b>
                        </div>
                        <div class="col-md-6">
                            ' . $note . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Credit</b>
                        </div>
                        <div class="col-md-6">
                            ' . $credit . '</b>
                        </div>

                        <div class="col-md-6">
                            <span class="text-dark ">Debit</b>
                        </div>
                        <div class="col-md-6">
                            ' . $debit . '</b>
                        </div>

                    </div>


            </div>';

        echo $result;
    }

}
