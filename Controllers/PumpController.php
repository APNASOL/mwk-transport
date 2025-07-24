<?php
include 'DatabaseController.php';
session_start();
$conn = OpenCon();


if (isset($_POST['create'])) {
    $pump_name = mysqli_real_escape_string($conn,$_POST['pump_name']);
    $address = mysqli_real_escape_string($conn,$_POST['address']); 
    $balance = mysqli_real_escape_string($conn,$_POST['balance']); 
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    $vehicle_id = $_SESSION['current_vehicle_id']; 
    $status = 1;
    if($vehicle_id == "No vehicle added")
    {   
        header('location:../pump-create.php?errorMessage=No vehicle added ! Please add vehicle first');
     
    }else{
 
    $queryFired = mysqli_query($conn, "INSERT INTO pumps (vehicle_id,name,address,balance,status,date) VALUES ('$vehicle_id','$pump_name','$address','$balance','$status','$date')");

    $pump_id_new = mysqli_insert_id($conn);

    $pumpDetailsqueryFired = mysqli_query($conn, "INSERT INTO pumps_details (pump_id, status, transaction_id, note, type, credit, debit,due,date) VALUES ('$pump_id_new','$status','$pump_id_new','Old due amount','Company due','0','$balance','0','$date')");

    if ($queryFired) {
        header('location:../pump-index.php?successMessage=New pump added successfully');
    } else {
        header('location:../pump-create.php?errorMessage=New pump adding error');
    }
}
}

if (@$_GET['process'] == 'delete') {

    $pump_id = $_GET['pump_id'];

    // $user_object = mysqli_query($conn, "SELECT * FROM users WHERE id = '$pump_id'");
    // $user = mysqli_fetch_array($user_object);

    // if ($user['profile_photo_path']) {
    //     $img = $user['profile_photo_path'];
    //     $imgpath = "../UserImages/" . $img;
    //     unlink($imgpath);
    // }

    $queryFired = mysqli_query($conn, "DELETE FROM pumps WHERE id = $pump_id");
    if ($queryFired) {
        header('location:../pump-index.php?successMessage=Pump deleted successfully.');

    }

}
if (@$_GET['process'] == 'edit') {

    $pump_id = $_GET['pump_id'];
    if (isset($pump_id)) {
        header('location:../pump-update.php?pump_id=' . $pump_id);
    }
}

if (isset($_POST['update'])) {
    $pump_id = mysqli_real_escape_string($conn,$_POST['pump_id']); 
    $pump_name = mysqli_real_escape_string($conn,$_POST['pump_name']);
    $address = mysqli_real_escape_string($conn,$_POST['address']); 
    $new_balance = mysqli_real_escape_string($conn,$_POST['new_balance']); 
    $old_balance = mysqli_real_escape_string($conn,$_POST['old_balance']); 
    $pump_details_table_id = mysqli_real_escape_string($conn,$_POST['pump_details_table_id']); 

    $the_db_new_balance = $old_balance - $new_balance;
    
    $date = $_POST['date']; 
    date('Y-m-d', strtotime($date));
    
    $queryFired = mysqli_query($conn, "UPDATE pumps SET name = '$pump_name' , address = '$address' , balance = balance - $old_balance + $new_balance,date = '$date' WHERE id = $pump_id");

    $queryFiredForDetailsTable = mysqli_query($conn, "UPDATE pumps_details SET debit = '$new_balance', date = '$date' WHERE id = $pump_details_table_id");


    if ($queryFired && $queryFiredForDetailsTable) {
        header('location:../pump-index.php?successMessage=Record updated successfully');
    } else {
        header('location:../pump-update.php?errorMessage=Record updating error' . '&pump_id=' . $pump_id);
    }
}

if (@$_GET['process'] == 'details') {
 
    $pump_id = $_GET['pump_id'];
    if (isset($pump_id)) {
        header('location:../pump-details.php?pump_id=' . $pump_id);
    }

}
