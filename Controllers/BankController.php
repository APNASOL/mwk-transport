<?php
include 'DatabaseController.php';
$conn = OpenCon();

if (isset($_POST['create_customer_transaction'])) {

    $customer_id = mysqli_real_escape_string($conn,$_POST['customer_id']);
    $operation_for =  'customer';
    $cash_in = mysqli_real_escape_string($conn,$_POST['amount']); 
    $cash_out = 0;
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    $bank = mysqli_real_escape_string($conn,$_POST['bank']); 
    $reference_no = mysqli_real_escape_string($conn,$_POST['reference_no']); 
    $notes  = 'Recieved bank payment from customer';
    $status = 1;
 
    $queryFired = mysqli_query($conn, "INSERT INTO bank_account (consume_id, operation_for,bank,reference_no, cash_in, cash_out, date, notes, status) VALUES ('$customer_id','$operation_for','$bank','$reference_no','$cash_in','$cash_out','$date','$notes','$status')");

    $cashbook_id = mysqli_insert_id($conn);
        
        $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$customer_id','$status','$cashbook_id','$notes','Cash Payment','$cash_in','0','0','$date')");

        $VehiclequeryFired = mysqli_query($conn, "UPDATE customers SET dues = dues - '$cash_in' WHERE id = $customer_id");

    if ($queryFired) {
        header('location:../account-bank.php?successMessage=Customer payment added');
    } else {
        header('location:../account-bank.php?errorMessage=Customer payment adding error');
    }
}
if (isset($_POST['cash_withdrawal'])) {
// echo "Tsting";
// // exit;
    $customer_id = "Company";
    $operation_for =  'Company';
    $cash_in = mysqli_real_escape_string($conn,$_POST['amount']); 
    $cash_out = 0;
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    $bank = mysqli_real_escape_string($conn,$_POST['bank']); 
    $reference_no = mysqli_real_escape_string($conn,$_POST['reference_no']); 
    $notes  = 'Cash Widhdraw';
    $status = 1;
 
    $queryForBankFired = mysqli_query($conn, "INSERT INTO bank_account (consume_id, operation_for,bank,reference_no, cash_in, cash_out, date, notes, status) VALUES ('$customer_id','$operation_for','$bank','$reference_no','$cash_in','$cash_out','$date','$notes','$status')");

    $queryForCashBookFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,cash_in, cash_out, date, notes, status) VALUES ('$customer_id','$operation_for','$cash_out','$cash_in','$date','$notes','$status')");

    if ($queryFired) {
        header('location:../account-bank.php?successMessage=Cash Widhdraw');
    } else {
        header('location:../account-bank.php?errorMessage=Cash Widhdraw error');
    }
}
if (isset($_POST['return_cash'])) {
    
    $customer_id = mysqli_real_escape_string($conn,$_POST['customer_id']);
    
    $operation_for =  'Return bank amount';
    $cash_in = 0; 
    $cash_out = mysqli_real_escape_string($conn,$_POST['amount']);
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    $bank = mysqli_real_escape_string($conn,$_POST['bank']); 
    $reference_no = mysqli_real_escape_string($conn,$_POST['reference_no']); 
    $notes  = 'Return bank amount to customer';
    $status = 1;     
   
 
    $queryForBankFired = mysqli_query($conn, "INSERT INTO bank_account (consume_id, operation_for,bank,reference_no, cash_in, cash_out, date, notes, status) VALUES ('$customer_id','$operation_for','$bank','$reference_no','$cash_in','$cash_out','$date','$notes','$status')");

    $bank_id = mysqli_insert_id($conn);
        
    $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$customer_id','$status','$bank_id','$notes','Bank amount resturn','0','$cash_out','0','$date')");
    
    $CustomerqueryFired = mysqli_query($conn, "UPDATE customers SET dues = dues + '$cash_out' WHERE id = $customer_id");

    if ($queryFired) {
        header('location:../account-bank.php?successMessage=bank amount returned');
    } else {
        header('location:../account-bank.php?errorMessage=bank amount returning error');
    }
}
 

if (@$_GET['process'] == 'delete') {

    $cashbook_id = $_GET['cashbook_id'];
 

    $queryFired = mysqli_query($conn, "DELETE FROM cashbook WHERE id = $cashbook_id");
    if ($queryFired) {
        header('location:../account-cashbook.php?successMessage=Record deleted successfully.');

    }

}
if (@$_GET['process'] == 'edit') {

    $cashbook_id = $_GET['cashbook_id'];
    if (isset($cashbook_id)) {
        header('location:../account-cashbook.php?cashbook_id=' . $cashbook_id);
    }
}

if (isset($_POST['update'])) {
    $pump_id = mysqli_real_escape_string($conn,$_POST['pump_id']); 
    $pump_name = mysqli_real_escape_string($conn,$_POST['pump_name']);
    $address = mysqli_real_escape_string($conn,$_POST['address']); 
    $balance = mysqli_real_escape_string($conn,$_POST['balance']); 
    
    $queryFired = mysqli_query($conn, "UPDATE pumps SET name = '$pump_name' , address = '$address' , balance = $balance WHERE id = $pump_id");

    if ($queryFired) {
        header('location:../account-cashbook.php?successMessage=Record updated successfully');
    } else {
        header('location:../account-cashbook.php?errorMessage=Record updating error' . '&pump_id=' . $pump_id);
    }
}
 
