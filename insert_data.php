<?php
include 'Controllers/DatabaseController.php';
$conn = OpenCon();
session_start();
$current_vehicle_id = $_SESSION['current_vehicle_id'];

    $amount = $_POST['amount'];
    $name = $_POST['name'];
    $current_date = $_POST['date'];

    $queryFired = mysqli_query($conn, "INSERT INTO `temprory_vehicle_expense_storage`(`expense_name`, `amount`, `vehicle_id`, `date`) VALUES ('$name','$amount','$current_vehicle_id','$current_date')");

if ($queryFired) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
