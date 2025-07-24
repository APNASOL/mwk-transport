<?php
include 'DatabaseController.php';
$conn = OpenCon();


// Handle the AJAX request to update the customer dues in the database
$customerId = $_POST['customerId'];
$amount = $_POST['amount'];
$date = Date();


$customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note, type, credit, debit,due,date) VALUES ('$customerId',1,'$customerId','Differance','Due','$amount','0','0',CURDATE())");

    if ($queryFired) {
        $response = ['status' => 'success'];
        //header('location:customer-index.php?successMessage=Customer dues update successfully');
    } else {
        $response = ['status' => 'error'];
       // header('location:customer-create.php?errorMessage=Customer dues update error');
    }

    
echo json_encode($response);

// Perform database update here
// Example: update customer dues in the database using $customerId and $amount

// Respond to the AJAX request (you can send a success or error response)
//echo json_encode(['status' => 'success']);
?>