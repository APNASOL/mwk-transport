<?php
include 'Controllers/DatabaseController.php';
$conn = OpenCon();

$expanse_id = $_POST['expanse_id'];

$queryFired = mysqli_query($conn, "DELETE FROM temprory_vehicle_expense_storage WHERE id = $expanse_id");

if ($queryFired) {
    echo "Data deleted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
