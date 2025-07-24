<?php
include 'DatabaseController.php';
$conn = OpenCon();
session_start();

print_r($_POST);
if (isset($_POST['create_customer'])) {
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $contact = mysqli_real_escape_string($conn,$_POST['contact']);   
    $dues = mysqli_real_escape_string($conn,$_POST['dues']);   
    $date = mysqli_real_escape_string($conn,$_POST['date']);
    $vehicle_id = $_SESSION['current_vehicle_id']; 
  
    if($vehicle_id == "No vehicle added")
    {
        header('location:../customer-create.php?errorMessage=No vehicle added ! Please add vehicle first');
    }
    $status = 1;

    $queryFired = mysqli_query($conn, "INSERT INTO customers (vehicle_id,name,username,contact,dues,date,status) VALUES ('$vehicle_id','$name','$username','$contact','$dues','$date','$status')");

    $customer_id_new = mysqli_insert_id($conn);

    $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note, type, credit, debit,due,date) VALUES ('$customer_id_new','$status','$customer_id_new','Old amount','Due','0','$dues','0','$date')");

    if ($queryFired) {
        header('location:../customer-index.php?successMessage=New customer added successfully');
    } else {
        header('location:../customer-create.php?errorMessage=New customer adding error');
    }
}

if(isset($_POST['differance_transaction'])){
    $customerId = $_POST['customer_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    
    
    $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note, type, credit, debit,due,date) VALUES ('$customerId',1,'$customerId','Differance','Due','$amount','0','0','$date')");
    
        if ($customerDetailsqueryFired) {
            //$response = ['status' => 'success'];
            header('location:../customer-index.php?successMessage=Customer dues update successfully');
        } else {
            //$response = ['status' => 'error'];
            header('location:../customer-index.php?errorMessage=Customer dues update error');
        }
}

if (@$_GET['process'] == 'delete') {

    $customer_id = $_GET['customer_id'];

    $queryFired = mysqli_query($conn, "DELETE FROM customers WHERE id = $customer_id");
    if ($queryFired) {
        header('location:../customer-index.php?successMessage=Customer deleted successfully.');

    }

}
if (@$_GET['process'] == 'details') {
 
    $customer_id = $_GET['customer_id'];
    if (isset($customer_id)) {
        header('location:../customer-details.php?customer_id=' . $customer_id);
    }

}
if (@$_GET['process'] == 'edit') {

    $customer_id = $_GET['customer_id'];
    if (isset($customer_id)) {
        header('location:../customer-update.php?customer_id=' . $customer_id);
    }
}

if (isset($_POST['update'])) {
    $customer_id = mysqli_real_escape_string($conn,$_POST['customer_id']); 
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    /*$contact = mysqli_real_escape_string($conn,$_POST['contact']);  
    $old_dues = mysqli_real_escape_string($conn,$_POST['old_dues']);  
    $new_dues = mysqli_real_escape_string($conn,$_POST['new_dues']); */ 
    $customer_details_table_id = mysqli_real_escape_string($conn,$_POST['customer_details_table_id']);  
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    
    $queryFired = mysqli_query($conn, "UPDATE customers SET name = '$name', username = '$username', date = '$date' WHERE id = $customer_id");
     

    $queryFiredForDetailsTable = mysqli_query($conn, "UPDATE customer_details SET date = '$date' WHERE id = $customer_details_table_id");


    if ($queryFired && $queryFiredForDetailsTable) {
        header('location:../customer-index.php?successMessage=Record updated successfully');
    } else {
        header('location:../customer-update.php?errorMessage=Record updating error' . '&customer_id=' . $customer_id);
    }
}
