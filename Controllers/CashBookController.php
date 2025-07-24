<?php
include 'DatabaseController.php';
$conn = OpenCon();
session_start();
$current_vehicle_id = $_SESSION['current_vehicle_id'];

if($current_vehicle_id == 0 || $current_vehicle_id == ''){
    header('location:../account-cashbook.php?successMessage=Return');
    return;
}

//print_r($_POST);exit();

if (isset($_POST['customer_transaction']) && $_POST['customer_transaction'] == "customer_transaction") {

    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $operation_for = 'customer';
    $cash_in = mysqli_real_escape_string($conn, $_POST['amount']);
    $cash_out = 0;
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    
    $status = 1;

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);


    if ($vehicle_id == "Company") {
        $notes = 'Company Recieved Cash From Customer';
        $queryFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id, cash_in, cash_out, date, notes, status) VALUES ('$customer_id','$operation_for','$current_vehicle_id','$cash_in','$cash_out','$date','$notes','$status')");

        $cashbook_id = mysqli_insert_id($conn);

        $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$customer_id','$status','$cashbook_id','$notes','Cash Payment','$cash_in','0','0','$date')");

        $VehiclequeryFired = mysqli_query($conn, "UPDATE customers SET dues = dues - '$cash_in' WHERE id = $customer_id");

        if ($queryFired && $customerDetailsqueryFired && $VehiclequeryFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Customer payment added');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Customer payment adding error');
        }
    } else {
        
        $notes = 'Vehicle Recieved Cash From Customer';

        $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance + '$cash_in' WHERE id = '$vehicle_id'");

        $vehicleDetailsqueryFired = mysqli_query($conn, "INSERT INTO vehicle_details (vehicle_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$current_vehicle_id','$status','$customer_id','Cash payment received by vehicle','Cash Received','$cash_in','0','0','$date')");

        $vehicle_details_last_id = mysqli_insert_id($conn);

        $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$customer_id','$status','$vehicle_details_last_id','$notes','Cash Payment','$cash_in','0','0','$date')");

        $VehiclequeryFired = mysqli_query($conn, "UPDATE customers SET dues = dues - '$cash_in' WHERE id = $customer_id");

        if ($VehiclequeryFired && $vehicleDetailsqueryFired && $customerDetailsqueryFired && $VehiclequeryFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Customer payment added to vehicle');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Customer payment adding error');
        }

    }

    mysqli_autocommit($conn, true);

}

if (isset($_POST['give_to_vehicle_transaction']) && $_POST['give_to_vehicle_transaction'] == "give_to_vehicle_transaction") {

    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);

    $operation_for = 'Given to vehicle';
    $cash_out = mysqli_real_escape_string($conn, $_POST['amount']);
    $cash_in = 0;
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $notes = 'Cash given to vehicle';
    $status = 1;

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id, cash_in, cash_out, date, notes, status) VALUES ('$vehicle_id','$operation_for','$vehicle_id','$cash_in','$cash_out','$date','$notes','$status')");

    $cashbook_id = mysqli_insert_id($conn);

    $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance + '$cash_out' WHERE id = '$vehicle_id'");

    $vehicleDetailsqueryFired = mysqli_query($conn, "INSERT INTO vehicle_details (vehicle_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$vehicle_id','$status','$cashbook_id','Given balance to vehicle','Balance','$cash_out','0','0','$date')");

    if ($queryFired && $VehiclequeryFired && $vehicleDetailsqueryFired) {
        mysqli_commit($conn);
        header('location:../account-cashbook.php?successMessage=Customer payment added');
    } else {
        mysqli_rollback($conn);
        header('location:../account-cashbook.php?errorMessage=Customer payment adding error');
    }
    // Set autocommit back to true
    mysqli_autocommit($conn, true);
}

if (isset($_POST['receive_from_vehicle_transaction']) && $_POST['receive_from_vehicle_transaction'] == "receive_from_vehicle_transaction") {

    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $operation_for = 'Recieved from vehicle';
    $cash_in = mysqli_real_escape_string($conn, $_POST['amount']);
    $cash_out = 0;
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $notes = 'Payment recieved from vehicle';
    $status = 1;

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id, cash_in, cash_out, date, notes, status) VALUES ('$vehicle_id','$operation_for','$vehicle_id','$cash_in','$cash_out','$date','$notes','$status')");

    $cashbook_id = mysqli_insert_id($conn);

    $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance - '$cash_in' WHERE id = '$vehicle_id'");

    $vehicleDetailsqueryFired = mysqli_query($conn, "INSERT INTO vehicle_details (vehicle_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$vehicle_id','$status','$cashbook_id','Received balance from vehicle','Balance widhdrawn','0','$cash_in','0','$date')");

    if ($queryFired && $VehiclequeryFired && $vehicleDetailsqueryFired) {
        mysqli_commit($conn);
        header('location:../account-cashbook.php?successMessage=Customer payment added');
    } else {
        mysqli_rollback($conn);
        header('location:../account-cashbook.php?errorMessage=Customer payment adding error');
    }
    // Set autocommit back to true
    mysqli_autocommit($conn, true);
}

if (isset($_POST['pay_to_pump_transaction']) && $_POST['pay_to_pump_transaction'] == "pay_to_pump_transaction") {

    $pump_id = mysqli_real_escape_string($conn, $_POST['pump_id']);
    $operation_for = 'Payed to pump';
    $cash_out = mysqli_real_escape_string($conn, $_POST['amount']);
    $cash_in = 0;
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $notes = 'Payed to pump';
    $status = 1;

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id,cash_in, cash_out, date, notes, status) VALUES ('$pump_id','$operation_for','$current_vehicle_id','$cash_in','$cash_out','$date','$notes','$status')");

    $pump_id_new = mysqli_insert_id($conn);

    $pumpsDetailsqueryFired = mysqli_query($conn, "INSERT INTO pumps_details (pump_id, status, transaction_id, note, type, credit, debit,due,date) VALUES ('$pump_id','$status','$pump_id_new','$notes','Cash payment','$cash_out','0','0','$date')");

    $VehiclequeryFired = mysqli_query($conn, "UPDATE pumps SET balance = balance - '$cash_out' WHERE id = '$pump_id'");

    if ($queryFired && $pumpsDetailsqueryFired && $VehiclequeryFired ) {
        mysqli_commit($conn);
        header('location:../account-cashbook.php?successMessage=Customer payment added');
    } else {
        mysqli_rollback($conn);
        header('location:../account-cashbook.php?errorMessage=Customer payment adding error');
    }
    // Set autocommit back to true
    mysqli_autocommit($conn, true);
}

if (isset($_POST['return_cash']) && $_POST['return_cash'] == "return_cash") {

    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $operation_for = 'Cash return';
    $cash_out = mysqli_real_escape_string($conn, $_POST['amount']);
    $cash_in = 0;
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $notes = 'Returned cash back to customer';
    $status = 1;

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id,cash_in, cash_out, date, notes, status) VALUES ('$customer_id','$operation_for','$current_vehicle_id','$cash_in','$cash_out','$date','$notes','$status')");

    $cashbook_id = mysqli_insert_id($conn);

    $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$customer_id','$status','$cashbook_id','$notes','Cash Return','0','$cash_out','0','$date')");

    $CustomerqueryFired = mysqli_query($conn, "UPDATE customers SET dues = dues + '$cash_out' WHERE id = $customer_id");

    if ($queryFired && $customerDetailsqueryFired && $CustomerqueryFired) {
        mysqli_commit($conn);
        header('location:../account-cashbook.php?successMessage=Customer payment added');
    } else {
        mysqli_rollback($conn);
        header('location:../account-cashbook.php?errorMessage=Customer payment adding error');
    }

    // Set autocommit back to true
    mysqli_autocommit($conn, true);
}

if (@$_GET['process'] == 'edit') {

    $cashbook_id = $_GET['cashbook_id'];
    if (isset($cashbook_id)) {
        header('location:../account-cashbook-update.php?cashbook_id=' . $cashbook_id);
    }
}

if (isset($_POST['update'])) {
    $pump_id = mysqli_real_escape_string($conn, $_POST['pump_id']);
    $pump_name = mysqli_real_escape_string($conn, $_POST['pump_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $balance = mysqli_real_escape_string($conn, $_POST['balance']);

    $queryFired = mysqli_query($conn, "UPDATE pumps SET name = '$pump_name' , address = '$address' , balance = $balance WHERE id = $pump_id");

    if ($queryFired) {
        header('location:../account-cashbook.php?successMessage=Record updated successfully');
    } else {
        header('location:../account-cashbook.php?errorMessage=Record updating error' . '&pump_id=' . $pump_id);
    }
}

if (isset($_POST['chashbook_enteries_update']) && $_POST['chashbook_enteries_update'] == "Update") {

    $cashbook_record_id = mysqli_real_escape_string($conn, $_POST['cashbook_record_id']);
    $consume_id = mysqli_real_escape_string($conn, $_POST['consume_id']);
    // echo $consume_id; exit;
    $operation_for = mysqli_real_escape_string($conn, $_POST['operation_for']);
    $cash_in = mysqli_real_escape_string($conn, $_POST['cash_in']);
    $old_cash_in = mysqli_real_escape_string($conn, $_POST['old_cash_in']);
    $cash_out = mysqli_real_escape_string($conn, $_POST['cash_out']);
    $old_cash_out = mysqli_real_escape_string($conn, $_POST['old_cash_out']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    // cashbook query for all the records.
    $querCashBookFired = mysqli_query($conn, "UPDATE cashbook SET cash_in = '$cash_in' , cash_out = '$cash_out', date = '$date' WHERE id = '$cashbook_record_id'");

    if ($operation_for == "customer" || $operation_for == 'Cash return') {
        
        $queryFired = mysqli_query($conn, "UPDATE customers SET dues = dues + $old_cash_in - $cash_in - $old_cash_out + $cash_out, date = '$date' WHERE id = $consume_id");

        $queryFiredForDetailsTable = mysqli_query($conn, "UPDATE customer_details SET debit = '$cash_out', credit = '$cash_in', date = '$date' WHERE customer_id = '$consume_id' AND transaction_id = '$cashbook_record_id' AND (type='Cash Payment' OR type='Cash Return')");

        if ($querCashBookFired && $queryFired && $queryFiredForDetailsTable) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record updated successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record updating error');
        }
        // Set autocommit back to true
    mysqli_autocommit($conn, true);

    } else if ($operation_for == "Payed to pump") 
    {
        
        $queryFired = mysqli_query($conn,"UPDATE pumps SET balance = balance + $old_cash_in - $cash_in + $old_cash_out - $cash_out, date = '$date' WHERE id = '$consume_id'");   
             
        $pumpDetailsqueryFired = mysqli_query($conn, "UPDATE pumps_details SET debit = '$cash_in', credit = '$cash_out', date = '$date' WHERE pump_id = '$consume_id' AND transaction_id = '$cashbook_record_id'");

        if ($querCashBookFired && $queryFired && $pumpDetailsqueryFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record updated successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record updating error');
        }
      // Set autocommit back to true
      mysqli_autocommit($conn, true);

    } else if ($operation_for == "Given to vehicle" || $operation_for == "Recieved from vehicle") {
        
        $type = '';
        
        if($operation_for == "Given to vehicle"){
            $type ='Balance';
        }else{
            $type = 'Balance widhdrawn';
        }
        

        $queryFired = mysqli_query($conn,"UPDATE vehicles SET balance = balance + $old_cash_in - $cash_in - $old_cash_out + $cash_out, date = '$date' WHERE id = '$consume_id'");  
              
        $vehicleDetailsqueryFired = mysqli_query($conn, "UPDATE vehicle_details SET debit = '$cash_in', credit = '$cash_out', date = '$date' WHERE type = '$type' AND vehicle_id = '$consume_id' AND transaction_id = '$cashbook_record_id'");

        if ($queryFired && $querCashBookFired && $vehicleDetailsqueryFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record updated successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record updating error');
        }

        // Set autocommit back to true
       mysqli_autocommit($conn, true);
    }else if ($operation_for == "Company cash") {

    

        if ($querCashBookFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record updated successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record updating error');
        }
// Set autocommit back to true
mysqli_autocommit($conn, true);

    }else if ($operation_for == "Vehicle profit") {

    
        $queryFired = mysqli_query($conn,"UPDATE profit_book SET cash_out = $cash_out, date = '$date' WHERE t_id = '$cashbook_record_id' AND cash_in=0.00"); 

        if ($querCashBookFired && $queryFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record updated successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record updating error');
        }
// Set autocommit back to true
mysqli_autocommit($conn, true);

    }


}
if (isset($_POST['chashbook_enteries_delete']) && $_POST['chashbook_enteries_delete'] == "Delete") {

    $cashbook_record_id = mysqli_real_escape_string($conn, $_POST['cashbook_record_id']);
    $consume_id = mysqli_real_escape_string($conn, $_POST['consume_id']);
    $old_cash_in = mysqli_real_escape_string($conn, $_POST['old_cash_in']);
    $old_cash_out = mysqli_real_escape_string($conn, $_POST['old_cash_out']);
    $operation_for = mysqli_real_escape_string($conn, $_POST['operation_for']);
    
    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    // cashbook query for all the records.
    $querCashBookFired = mysqli_query($conn, "DELETE FROM cashbook WHERE id = '$cashbook_record_id'");

    if ($operation_for == "customer" || $operation_for == 'Cash return') {
        
        $queryFired = mysqli_query($conn, "UPDATE customers SET dues = dues - $old_cash_in +  $old_cash_out  WHERE id = $consume_id");

        $queryFiredForDetailsTable = mysqli_query($conn, "DELETE FROM customer_details WHERE customer_id = '$consume_id' AND transaction_id = '$cashbook_record_id' AND (type = 'Cash Payment' OR type = 'Cash Return')");

        if ($querCashBookFired && $queryFired && $queryFiredForDetailsTable) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record deleted successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record deleted error');
        }
        // Set autocommit back to true
       mysqli_autocommit($conn, true);

    } else if ($operation_for == "Payed to pump") 
    {
        
        $queryFired = mysqli_query($conn,"UPDATE pumps SET balance = balance + $old_cash_in - $old_cash_out  WHERE id = '$consume_id'");        
        $pumpDetailsqueryFired = mysqli_query($conn, "DELETE FROM pumps_details WHERE pump_id = '$consume_id' AND transaction_id = '$cashbook_record_id'");

        if ($querCashBookFired && $queryFired && $pumpDetailsqueryFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record deleted successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record deleted error');
        }
// Set autocommit back to true
mysqli_autocommit($conn, true);

    } else if ($operation_for == "Given to vehicle" || $operation_for == "Recieved from vehicle") {

        $queryFired = mysqli_query($conn,"UPDATE vehicles SET balance = balance + $old_cash_in - $old_cash_out WHERE id = '$consume_id'");        
        $vehicleDetailsqueryFired = mysqli_query($conn, "DELETE FROM vehicle_details WHERE vehicle_id = '$consume_id' AND transaction_id = '$cashbook_record_id' AND (type = 'Balance' OR type = 'Balance widhdrawn')");

        if ($queryFired && $querCashBookFired && $vehicleDetailsqueryFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record deleted successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record deleted error');
        }
// Set autocommit back to true
mysqli_autocommit($conn, true);

    }else if ($operation_for == "Company cash") {

    

        if ($querCashBookFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record deleted successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record deleted error');
        }
// Set autocommit back to true
mysqli_autocommit($conn, true);

    }else if ($operation_for == "Vehicle profit") {

    
        $queryFired = mysqli_query($conn,"DELETE FROM profit_book WHERE t_id = '$cashbook_record_id' AND cash_in=0.00"); 

        if ($querCashBookFired && $queryFired) {
            mysqli_commit($conn);
            header('location:../account-cashbook.php?successMessage=Record updated successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-cashbook.php?errorMessage=Record updating error');
        }
// Set autocommit back to true
mysqli_autocommit($conn, true);

    }


}
