<?php
include 'DatabaseController.php';
$conn = OpenCon();

if (isset($_POST['create'])) {
    $vehicle_id = mysqli_real_escape_string($conn,$_POST['vehicle_id']);
    
    if($vehicle_id == 0 || $vehicle_id == ''){
    header('location:../fuel-index.php?successMessage=Return');
    exit;
}
    
    if($vehicle_id == "No vehicle added")
    {  
        header('location:../Fuel-create.php?errorMessage=No vehicle added ! Please add vehicle first');
     
    }
    $pump_id = mysqli_real_escape_string($conn,$_POST['pump_id']); 
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    $litres = mysqli_real_escape_string($conn,$_POST['litres']); 
    $bill = mysqli_real_escape_string($conn,$_POST['bill']); 
    $paid = ""; 
    $due = ""; 
    $payment_type = ""; 
    
    // $paid = mysqli_real_escape_string($conn,$_POST['paid']); 
    // $due = mysqli_real_escape_string($conn,$_POST['due']); 
    // $payment_type = mysqli_real_escape_string($conn,$_POST['payment_type']); 
    $status = 1;

    $queryFired = mysqli_query($conn, "INSERT INTO fuel (vehicle_id, pump_id, date, litres, balance, paid, due, paymet_type,status) VALUES ('$vehicle_id','$pump_id','$date','$litres','$bill','$paid','$due','$payment_type','$status')");


    $pump_id_new = mysqli_insert_id($conn);

    $pumpsDetailsqueryFired = mysqli_query($conn, "INSERT INTO pumps_details (pump_id, status, transaction_id, note, type, credit, debit,due,date) VALUES ('$pump_id','$status','$pump_id_new','Fuel fill amount','Fuel','0','$bill','0','$date')");

    $VehiclequeryFired = mysqli_query($conn, "UPDATE pumps SET balance = balance + '$bill' WHERE id = '$pump_id'");

    if ($queryFired) {
        header('location:../fuel-index.php?successMessage=New fuel record added successfully');
    } else {
        header('location:../fuel-create.php?errorMessage=New fuel record adding error');
    }
}

if (isset($_POST['fuel_delete'])) {

    $fuel_id = $_POST['id'];

     $amount_object = mysqli_query($conn, "SELECT * FROM fuel WHERE id = '$fuel_id'");
     $fuel = mysqli_fetch_array($amount_object);
     $fuel_amount = $fuel['balance'];
     $fuel_pump = $fuel['pump_id'];

    // if ($user['profile_photo_path']) {
    //     $img = $user['profile_photo_path'];
    //     $imgpath = "../UserImages/" . $img;
    //     unlink($imgpath);
    // }

    $queryFired = mysqli_query($conn, "DELETE FROM fuel WHERE id = $fuel_id");
    
     $queryDFired = mysqli_query($conn, "DELETE FROM pumps_details WHERE transaction_id = $fuel_id AND type ='Fuel'");
     
     $VehiclequeryFired = mysqli_query($conn, "UPDATE pumps SET balance = balance -'$fuel_amount' WHERE id = '$fuel_pump'");
    
    if ($queryFired && $queryDFired) {
        header('location:../fuel-index.php?successMessage=Record deleted successfully.');

    }

}
if (@$_GET['process'] == 'edit') {

    $fuel_id = $_GET['fuel_id'];
    if (isset($fuel_id)) {
        header('location:../fuel-update.php?fuel_id=' . $fuel_id);
    }
}

if (isset($_POST['update'])) {

    $fuel_record_id = mysqli_real_escape_string($conn,$_POST['id']);  
    $old_pump_id = mysqli_real_escape_string($conn,$_POST['old_pump']);  
    $pump_id = mysqli_real_escape_string($conn,$_POST['pump_id']);  
    $date = mysqli_real_escape_string($conn,$_POST['date']);   
    $old_balance = mysqli_real_escape_string($conn,$_POST['old_balance']); 
    $bill = mysqli_real_escape_string($conn,$_POST['bill']); 
    $paid = ""; 
    $due = ""; 
    $payment_type = "";  
    $status = 1;

    if($old_pump_id === $pump_id){

    $queryFired = mysqli_query($conn, "UPDATE fuel SET date = '$date', balance = '$bill' WHERE id = '$fuel_record_id'");

    $pumpsDetailsqueryFired = mysqli_query($conn, "UPDATE pumps_details SET date = '$date', debit = '$bill' WHERE transaction_id = '$fuel_record_id' AND type = 'Fuel
    ' AND pump_id = '$pump_id'");
      
    $VehiclequeryFired = mysqli_query($conn, "UPDATE pumps SET balance = balance -'$old_balance' + '$bill' WHERE id = '$pump_id'");

    if ($queryFired && $pumpsDetailsqueryFired && $VehiclequeryFired) {
        header('location:../pump-index.php?successMessage=Record updated successfully');
    } else {
        header('location:../pump-update.php?errorMessage=Record updating error' . '&pump_id=' . $pump_id);
    }
  }else{

    $queryFired = mysqli_query($conn, "UPDATE fuel SET pump_id='$pump_id', date = '$date', balance = '$bill' WHERE id = '$fuel_record_id'");

    $pumpsDetailsqueryFired = mysqli_query($conn, "UPDATE pumps_details SET pump_id='$pump_id', date = '$date', debit = '$bill' WHERE transaction_id = '$fuel_record_id' AND type = 'Fuel'");
      
    $VehiclequeryFired = mysqli_query($conn, "UPDATE pumps SET balance = balance -'$old_balance' WHERE id = '$old_pump_id'");

    $pumpQueryFired = mysqli_query($conn, "UPDATE pumps SET balance = balance + '$bill' WHERE id = '$pump_id'");

    if ($queryFired && $pumpsDetailsqueryFired && $VehiclequeryFired && $pumpQueryFired) {
        header('location:../pump-index.php?successMessage=Record updated successfully');
    } else {
        header('location:../pump-update.php?errorMessage=Record updating error' . '&pump_id=' . $pump_id);
    }

  }
}
 
