<?php
include 'DatabaseController.php';
session_start();
$conn = OpenCon();
$current_vehicle_id = $_SESSION['current_vehicle_id'];

if($current_vehicle_id == 0 || $current_vehicle_id == ''){
    header('location:../trip-index.php?successMessage=Return');
    exit;
}

// Check if a file has been uploaded
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $date = mysqli_real_escape_string($conn,$_POST['date']);
    //echo "The data: $date";exit;
    $uploadDir = '../uploads/'; // Specify the directory where you want to save the uploaded file
    $uploadFile = $uploadDir . basename($_FILES['photo']['name']);
    $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

    // Check file size (example: 5MB maximum)
    if ($_FILES['photo']['size'] > 5000000) {
        echo "Sorry, your file is too large.";
    } 
    // Allow certain file formats (e.g., jpg, png,)
    elseif(!in_array($fileType, ['jpg', 'png', 'jpeg'])) {
        echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
    } 
    // Check if file already exists
    elseif (file_exists($uploadFile)) {
        echo "Sorry, file already exists.";
    } 
    else {
        // Attempt to move the uploaded file to the designated directory

        $vehicle_details = mysqli_query($conn, "SELECT * FROM media WHERE vehicle_id =  $current_vehicle_id AND date = '$date';");
        $row= mysqli_fetch_array($vehicle_details);
        if($row){
            $imagePath = $row["path"];
            $image = $row["name"];

            $filePath = "uploads/$image";

            if (file_exists($imagePath)) {
                // Attempt to delete the file
                if (unlink($imagePath)) {
                    echo "The file has been deleted successfully.";
                } else {
                    echo "There was an error deleting the file.";
                }
            } else {
                echo "The file does not exist.";
            }

            $vehicleDetailsDelete = mysqli_query($conn, "DELETE FROM media WHERE vehicle_id =  $current_vehicle_id AND date = '$date';");
        

        }

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $fileName = basename($_FILES['photo']['name']);
            $filePath = $uploadFile;
            $sql = "INSERT INTO `media` (`vehicle_id`, `name`,`path`, `date`, `status`) VALUES ('$current_vehicle_id', '$fileName', '$filePath','$date',1)";
            if (mysqli_query($conn, $sql)) {
                echo "File information stored in the database.";
                header("location:../vehicle_single_details.php?date=$date&process=edit");
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            echo "The file ". basename($_FILES['photo']['name']). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    echo "No file was uploaded or there was an error.";
}


if (isset($_POST['btn_save_all'])) {
    
    if($current_vehicle_id == 0){
    header('location:../trip-index.php?successMessage=Return');
    exit;
}
    $trips = (int)$_POST['tripsNumber'];
    $d = $_POST['date'];

    // Check if a file has been uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/'; // Specify the directory where you want to save the uploaded file
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Check file size (example: 5MB maximum)
        if ($_FILES['photo']['size'] > 5000000) {
            echo "Sorry, your file is too large.";
        } 
        // Allow certain file formats (e.g., jpg, png,)
        elseif(!in_array($fileType, ['jpg', 'png', 'jpeg'])) {
            echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed.";
        } 
        // Check if file already exists
        elseif (file_exists($uploadFile)) {
            echo "Sorry, file already exists.";
        } 
        else {
            // Attempt to move the uploaded file to the designated directory
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                $fileName = basename($_FILES['photo']['name']);
                $filePath = $uploadFile;
                $sql = "INSERT INTO `media` (`vehicle_id`, `name`,`path`, `date`, `status`) VALUES ('$current_vehicle_id', '$fileName', '$filePath','$d',1)";
                if (mysqli_query($conn, $sql)) {
                    echo "File information stored in the database.";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                echo "The file ". basename($_FILES['photo']['name']). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file was uploaded or there was an error.";
    }

   if($trips > 0){

        $customers = $_POST['c_name'];
        $load_types =$_POST['load_type'];
        $start_date =$_POST['date'];
        $end_date =$_POST['date'];
        $weights = $_POST['weight'];
        $payments = $_POST['payment_status'];
        $ppts = $_POST['price_per_ton'];
        $expenses = $_POST['expanse'];
        $total_bills = $_POST['total_bill'];
        $prices =$_POST['price'];
        $loadTypes = $_POST['custom_type'];

        $i =0;
    
     while($i < $trips ){
       $customer_id = $customers[$i];
       $load_type =$load_types[$i];
       $weight = $weights[$i];
       $total_bill = $total_bills[$i];
       $expense = $expenses[$i];
       $payment_status = $payments[$i];

 
       //echo "The total bill: $total_bill";exit;

       if($total_bill > 0){
       if ($load_type == "custom") {
         $load_type = $loadTypes[$i];
         $price = $total_bill - $expense;
         $price_per_ton = 0;
         $weight = 0;
       } else {
         $price = $prices[$i];
         $price_per_ton = $ppts[$i];
       }

        $status = 1;


        /// --- From

        // Check if the record already exists
        $checkQuery = mysqli_query($conn, "SELECT id FROM trips WHERE vehicle_id = '$current_vehicle_id' AND customer_id = '$customer_id' AND load_type = '$load_type' AND start_date = '$start_date' AND end_date = '$end_date' AND weight ='$weight' AND price ='$price' AND price_per_ton ='$price_per_ton' AND expense ='$expense' AND total_bill ='$total_bill'");
        if (mysqli_num_rows($checkQuery) == 0) {
        $queryFired = mysqli_query($conn, "INSERT INTO trips (vehicle_id, customer_id, load_type, start_date, end_date, weight, price, price_per_ton,expense,total_bill,payment_status,status) VALUES 
        ('$current_vehicle_id','$customer_id','$load_type','$start_date','$end_date','$weight','$price','$price_per_ton','$expense','$total_bill','$payment_status','$status')");

        $trip_id = mysqli_insert_id($conn);
        if ($payment_status == 'received') {

            $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$customer_id','$status','$trip_id','Delivered trip and received','Trip','$total_bill','0','0','$end_date')");

            $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance + '$total_bill' - '$expense' WHERE id = '$vehicle_id'");

            $vehicleDetailsqueryFired = mysqli_query($conn, "INSERT INTO vehicle_details (vehicle_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$current_vehicle_id','$status','$trip_id','Delivered trip and received','Trip','$price+$expense','$expense','0','$end_date')");

        } else if ($payment_status == 'due') {
            //$trip_id = mysqli_insert_id($conn);

            $customerDetailsqueryFired = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note, type, credit, debit,due,date) VALUES ('$customer_id','$status','$trip_id','Delivered trip and due','Trip','0','$total_bill','0','$end_date')");

            $VehiclequeryFired = mysqli_query($conn, "UPDATE customers SET dues = dues + '$total_bill' WHERE id = $customer_id");

            $VehicleupdatequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance - '$expense' WHERE id = '$current_vehicle_id'");

            $vehicleDetailsqueryFired = mysqli_query($conn, "INSERT INTO vehicle_details (vehicle_id, status, transaction_id, note,type,credit, debit,due,date) VALUES ('$current_vehicle_id','$status','$trip_id','Not received','Trip','$price','$expense','0','$end_date')");
        }

        $queryFired1 = mysqli_query($conn, "UPDATE customers SET date = '$start_date' WHERE id = $customer_id");
        
        $queryIncomeFired = mysqli_query($conn, "INSERT INTO vehicle_income_operations ( `vehicle_id`, `type`, `transaction_id`, `amount`, `date`, `status`) VALUES ('$current_vehicle_id','Trip','$trip_id','$total_bill','$end_date','$status')");

        if ($queryFired1 && $queryFired && $queryIncomeFired && $customerDetailsqueryFired && $VehiclequeryFired && $vehicleDetailsqueryFired) {
           // header('location:../trip-index.php?successMessage=New trip record added successfully');
        } else {
            //header('location:../trip-create.php?errorMessage=New trip record adding error');
        }
    }
        //// --- To
    }
        /// ---- here 

        $i++;
    
     }
   }

   if(isset($_POST["pump_bill"])){

    $pump_id = mysqli_real_escape_string($conn,$_POST['pump_id']); 
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    $litres = 0; 
    $bill = mysqli_real_escape_string($conn,$_POST['pump_bill']); 
    $paid = ""; 
    $due = ""; 
    $payment_type = ""; 
    
    $status = 1;
    
    if($bill > 0 ){

    $queryFired = mysqli_query($conn, "INSERT INTO fuel (vehicle_id, pump_id, date, litres, balance, paid, due, paymet_type,status) VALUES ('$current_vehicle_id','$pump_id','$date','$litres','$bill','$paid','$due','$payment_type','$status')");


    $pump_id_new = mysqli_insert_id($conn);

    $pumpsDetailsqueryFired = mysqli_query($conn, "INSERT INTO pumps_details (pump_id, status, transaction_id, note, type, credit, debit,due,date) VALUES ('$pump_id','$status','$pump_id_new','Fuel fill amount','Fuel','0','$bill','0','$date')");

    $VehiclequeryFired = mysqli_query($conn, "UPDATE pumps SET balance = balance + '$bill' WHERE id = '$pump_id'");
    }

   }

   if(isset($_POST["total_expanse"])){

    

    $f_expense_names = $_POST['f_expense_names'];
    $f_amounts = $_POST['f_amounts'];


    $expanses = [];

        // Function to add an array to the editable array
    function addData($data) {
        global $expanses;
        $expanses[] = $data;
    }

    $total = 0;

    if($_POST["total_expanse"] > 0 ){
    $expense_names = $_POST['expense_names'];
    $amounts = $_POST['amounts'];

    for ($i = 0; $i < count($_POST["expense_names"]); $i++) {

        $expenseName = $_POST["expense_names"][$i];
        $Amount = $_POST["amounts"][$i];
        $total = (float)$total + (float)$Amount;
        if($Amount > 0){

            $data = ['name' => $expenseName, 'amount' => $Amount];
            addData($data);
            }

        }
    }

    for ($i = 0; $i < count($_POST["f_expense_names"]); $i++) {

        $expenseName = $_POST["f_expense_names"][$i];
        $Amount = $_POST["f_amounts"][$i];
        $total = (float)$total + (float)$Amount;
        if($Amount > 0){

            if($expenseName == 'Vehicle'){
                $repairMessage = $_POST['repair_message'];
                $repairExpenseQuery = "INSERT INTO `vehicle_repair` (`v_id`, `message`, `amount`, `date`, `status`) 
                VALUES ('$current_vehicle_id','$repairMessage','$Amount','$date',1)";
                $RepairQueryFired = mysqli_query($conn, $repairExpenseQuery);
                if($RepairQueryFired){
                    echo "<script>console. log('expanse added  " . $expenseName. "' );</script>";
                }else{
                    echo "<script>console. log('this is a Variable: " . $expenseName. "' );</script>";
                }
            }

            $data = ['name' => $expenseName, 'amount' => $Amount];
            addData($data);
        }
    }


    
    $date = $_POST['date'];
    $status = 1;

    if($total > 0){

        $TotalExpenseQuery = "INSERT INTO `vehicle_expenses`(`vehicle_id`, `end_date`, `total_expenses`) VALUES ('$current_vehicle_id','$date','$total')";

       $QueryFired = mysqli_query($conn, $TotalExpenseQuery);

       $LastExpenseId = mysqli_insert_id($conn);

       foreach ($expanses as $index => $data) {

         $expenseId = $LastExpenseId;
         $expenseName = $data['name'];
         $Amount = $data['amount'];
         $status = 1;

         $ExpenseDetailsQuery = "INSERT INTO `vehicle_expenses_details`(`expense_id`, `expense_name`, `balance`, `status`) VALUES ('$expenseId','$expenseName','$Amount','$status')";
         $QueryFired = mysqli_query($conn, $ExpenseDetailsQuery);
       }

      $DeletTemproryStoredExpenses = mysqli_query($conn, "DELETE FROM temprory_vehicle_expense_storage WHERE vehicle_id = '$current_vehicle_id' AND date = '$date'");

      $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance - '$total' WHERE id = '$current_vehicle_id'");
 
      $vehicleDetailsqueryFired = mysqli_query($conn, "INSERT INTO vehicle_details (vehicle_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$current_vehicle_id','$status','$LastExpenseId','Vehicle expense added','Expense','0','$total','0','$date')");

      $queryIncomeFired = mysqli_query($conn, "INSERT INTO vehicle_income_operations ( `vehicle_id`, `type`, `transaction_id`, `amount`, `date`, `status`) VALUES ('$current_vehicle_id','Expense','$LastExpenseId','$total','$date','$status')");

      if ($TotalExpenseQuery && $DeletTemproryStoredExpenses && $VehiclequeryFired && $vehicleDetailsqueryFired && $queryIncomeFired) {
        
       }

     }
   }
    
 header('location:../vehicle-details.php?vehicle_id=' . $current_vehicle_id);

}

if (@$_GET['process'] == 'edit') {

    $trip_id = $_GET['trip_id'];
    if (isset($trip_id)) {
        header('location:../trip-update.php?trip_id=' . $trip_id);
    }
}

if (isset($_POST['delete']) && $_POST['delete'] == "Delete") {

    
    $trip_id = mysqli_real_escape_string($conn, $_POST['id']);
    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $old_customer_id = mysqli_real_escape_string($conn, $_POST['old_customer_id']);
    $price = mysqli_real_escape_string($conn, $_POST['old_total_bill']);
    $expances = mysqli_real_escape_string($conn, $_POST['old_expense']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

    $insideQueryRun = false;

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);
    

    $queryFired = mysqli_query($conn, "DELETE FROM trips WHERE id = $trip_id");
    
    $queryFired1 = mysqli_query($conn, "DELETE FROM customer_details WHERE transaction_id = $trip_id AND type = 'Trip' AND customer_id='$old_customer_id'");
    
    $queryFired2 = mysqli_query($conn, "DELETE FROM vehicle_details WHERE transaction_id = $trip_id AND type = 'Trip' AND vehicle_id='$current_vehicle_id'");
    
    $queryFired3 =mysqli_query($conn, "DELETE FROM vehicle_income_operations WHERE transaction_id = $trip_id AND type = 'Trip' AND vehicle_id='$current_vehicle_id'");
    
    if ($payment_status == 'received') {
        $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance - $price + '$expense' WHERE id = '$current_vehicle_id'");

        if($VehiclequeryFired){
            $insideQueryRun = true;
        }else{
            $insideQueryRun = false;
        }

    }else{
        $CustomerqueryFired = mysqli_query($conn, "UPDATE customers SET dues = dues - '$price'  WHERE id = '$old_customer_id'");
        $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance + '$expense' WHERE id = '$current_vehicle_id'");

        if($VehiclequeryFired && $CustomerqueryFired){
            $insideQueryRun = true;
        }else{
            $insideQueryRun = false;
        }
    }


    if ($queryFired && $queryFired1 && $queryFired2 && $queryFired3 && $insideQueryRun) {
        mysqli_commit($conn);
        header('location:../trip-index.php?successMessage=Record deleted successfully.');

    }else{
        mysqli_rollback($conn);
        header('location:../trip-index.php?errorMessage=Delete trips have error.');
        
    }

    // Set autocommit back to true
    mysqli_autocommit($conn, true);

}


if (isset($_POST['update']) && $_POST['update'] == "Update") {
    $trip_id = mysqli_real_escape_string($conn, $_POST['id']);
    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    //$load_type = mysqli_real_escape_string($conn, $_POST['load_type']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

    if ($_POST['price_per_ton']) {
        $price_per_ton = mysqli_real_escape_string($conn, $_POST['price_per_ton']);
    } else {
        $price_per_ton = 0;
    }


    $expense = mysqli_real_escape_string($conn, $_POST['expense']);
    $total_bill = mysqli_real_escape_string($conn, $_POST['total_bill']);

    // old references values
    $old_customer_id = mysqli_real_escape_string($conn, $_POST['old_customer_id']);
    $old_weight = mysqli_real_escape_string($conn, $_POST['old_weight']);
    $old_price = mysqli_real_escape_string($conn, $_POST['old_price']);
    $old_price_per_ton = mysqli_real_escape_string($conn, $_POST['old_price_per_ton']);
    $old_expense = mysqli_real_escape_string($conn, $_POST['old_expense']);
    $old_total_bill = mysqli_real_escape_string($conn, $_POST['old_total_bill']);
    $insideQueryRun = false;

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "UPDATE trips SET  customer_id = '$customer_id', start_date ='$start_date', end_date = '$end_date', weight = '$weight', price = '$price', price_per_ton = '$price_per_ton', expense='$expense',total_bill = '$total_bill' WHERE id = $trip_id");

    if ($payment_status == 'received') {
        if ($customer_id == $old_customer_id) {
            $customerDetailsqueryFired = mysqli_query($conn, "UPDATE customer_details SET credit = '$total_bill',date = '$end_date' WHERE customer_id = $customer_id AND transaction_id = '$trip_id' AND type = 'Trip'");

            if($customerDetailsqueryFired ){
                $insideQueryRun = true;
            }else{
                $insideQueryRun = false;
            }
        } else {
            $customerDetailsqueryOldDeleteFired = mysqli_query($conn, "DELETE FROM customer_details  WHERE customer_id = $old_customer_id AND transaction_id = '$trip_id' AND type = 'Trip' ");

            $customerDetailsqueryFiredAgainInsertion = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$customer_id','$status','$trip_id','Delivered trip and received','Trip','$total_bill','0','0','$end_date')");
            if($customerDetailsqueryOldDeleteFired && $customerDetailsqueryFiredAgainInsertion){
                $insideQueryRun = true;
            }else{
                $insideQueryRun = false;
            }
        }

        $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance - $old_total_bill + $old_expense  + '$total_bill' - '$expense' WHERE id = '$vehicle_id'");

        $vehicleDetailsqueryFired = mysqli_query($conn, "UPDATE vehicle_details SET credit = '$price',debit = '$expense', date = '$end_date' WHERE vehicle_id = '$vehicle_id' AND transaction_id = '$trip_id' AND type = 'Trip'");

    } else if ($payment_status == 'due') {
        if ($customer_id == $old_customer_id) {
            $CustomerqueryFired = mysqli_query($conn, "UPDATE customers SET dues = dues - '$old_total_bill' + '$total_bill' WHERE id = $customer_id");

            $customerDetailsqueryFired = mysqli_query($conn, "UPDATE customer_details SET debit = '$total_bill',date = '$end_date' WHERE customer_id = $customer_id AND transaction_id = '$trip_id' AND type = 'Trip' ");

            if($CustomerqueryFired && $customerDetailsqueryFired){
                $insideQueryRun = true;
            }else{
                $insideQueryRun = false;
            }
        } else {
            $CustomerqueryFired = mysqli_query($conn, "UPDATE customers SET dues = dues  + '$total_bill' WHERE id = $customer_id");

            $oldCustomerqueryFired = mysqli_query($conn, "UPDATE customers SET dues = dues - '$old_total_bill'  WHERE id = '$old_customer_id'");

            $customerDetailsqueryOldDeleteFired = mysqli_query($conn, "DELETE FROM customer_details  WHERE customer_id = $old_customer_id AND transaction_id = '$trip_id' AND type = 'Trip';");

            $customerDetailsqueryFiredAgainInsertion = mysqli_query($conn, "INSERT INTO customer_details (customer_id, status, transaction_id, note,type, credit, debit,due,date) VALUES ('$customer_id',1,'$trip_id','Delivered trip and due','Trip','0','$total_bill','0','$end_date')");

            if($CustomerqueryFired && $oldCustomerqueryFired && $customerDetailsqueryOldDeleteFired && $customerDetailsqueryFiredAgainInsertion){
                $insideQueryRun = true;
            }else{
                $insideQueryRun = false;
            }

            
        }

        $VehiclequeryFired = mysqli_query($conn, "UPDATE vehicles SET balance = balance + '$old_expense' - '$expense' WHERE id = '$vehicle_id'");

        $vehicleDetailsqueryFired = mysqli_query($conn, "UPDATE vehicle_details SET credit = '$price',debit = '$expense', date = '$end_date' WHERE vehicle_id = '$vehicle_id' AND transaction_id = '$trip_id' AND type = 'Trip'");
    }

    $queryIncomeFired = mysqli_query($conn, "UPDATE vehicle_income_operations SET amount = '$total_bill', date = '$end_date' WHERE type = 'Trip' AND vehicle_id = '$vehicle_id' AND transaction_id = '$trip_id'");

    if ($queryFired && $queryIncomeFired && $VehiclequeryFired && $vehicleDetailsqueryFired && $insideQueryRun) {
        mysqli_commit($conn);
        header('location:../trip-index.php?successMessage=Record updated successfully');
    } else {
        mysqli_rollback($conn);
        header('location:../trip-update.php?errorMessage=Record updating error' . '&trip_id=' . $trip_id);
    }
    // Set autocommit back to true
    mysqli_autocommit($conn, true);
}

if(isset($_POST['btn_save_all_cash'])){

    $entries = (int)$_POST['entries'];

   if($entries > 0){

        $customers = $_POST['c_name'];
        $vehicles =$_POST['v_name'];
        $date =$_POST['date'];
        $cash_entries =$_POST['cash_in'];

        $i =0;
    
        while($i < $entries ){
            $customer_id = $customers[$i];
            $vehicle_id =$vehicles[$i];
            $cash = $cash_entries[$i];

            $cashFlowQueryFired = mysqli_query($conn, "INSERT INTO cashflow(`vehicle_id`, `consumer`, `cash_in`, `cash_out`, `date`, `status`) VALUES ('$vehicle_id','$customer_id','$cash',0,'$date',1)");

            $i++;
        }

        if($cashFlowQueryFired){
            header('location:../cashflow-index.php?successMessage=Cash entries added successfully');
        }else{
            header('location:../cashflow-index.php?errorMessage=Cash entries adding failed');
        }

   }
}

if (isset($_POST['update_cash'])) {

    
    $cashflow_id = mysqli_real_escape_string($conn, $_POST['id']);

    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $old_customer_id = mysqli_real_escape_string($conn, $_POST['old_customer_id']);

    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $old_vehicle_id = mysqli_real_escape_string($conn, $_POST['old_vehicle_id']);

    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $cash_in = mysqli_real_escape_string($conn, $_POST['new_balance']);
    
    $queryFired = mysqli_query($conn, "UPDATE cashflow SET vehicle_id ='$vehicle_id', consumer ='$customer_id', cash_in = '$cash_in', date = '$date' WHERE id = $cashflow_id");
    
    if ($queryFired) {
        
        header('location:../cashflow-index.php?successMessage=Record updated successfully');

    }else{
        
        header('location:../cashflow-index.php?errorMessage=updating cashflow have error.');
        
    }


}

if (isset($_POST['delete_cash'])) {

    
    $cashflow_id = mysqli_real_escape_string($conn, $_POST['id']);
    
    $queryFired = mysqli_query($conn, "DELETE FROM cashflow WHERE id = $cashflow_id");
    
    if ($queryFired) {
        
        header('location:../cashflow-index.php?successMessage=Record deleted successfully.');

    }else{
        
        header('location:../cashflow-index.php?errorMessage=Delete cashflow have error.');
        
    }


}
