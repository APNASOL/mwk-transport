<?php
include 'DatabaseController.php';
$conn = OpenCon();
session_start();

if (isset($_POST['selected_option'])) {
  // Sanitize and validate input
  $selected_option = $_POST['selected_option'];
 
  $vehicle = "SELECT * FROM vehicles where id = '$selected_option'"; 

  $vehicle_record = mysqli_query($conn, $vehicle);

  // Check if any rows were returned
  if (mysqli_num_rows($vehicle_record) > 0) {
      // Fetch the first row
      $vehicle_row = mysqli_fetch_assoc($vehicle_record);
      // Display the data from the first row
      $_SESSION['current_vehicle_id'] = $vehicle_row['id'];
      $_SESSION['current_vehicle_number'] = str_replace(' ','-',$vehicle_row['number']);
      echo $_SESSION['current_vehicle_id'];
  } else {
      $_SESSION['current_vehicle_id'] = 'empty';
      $_SESSION['current_vehicle_number'] = 'empty';
  }
}
?>
