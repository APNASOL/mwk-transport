<?php
include 'DatabaseController.php';
$conn = OpenCon();

if (isset($_POST['cash_in_transaction']) && $_POST['cash_in_transaction'] == "cash_in_transaction") {

    $vehicle_id = mysqli_real_escape_string($conn,$_POST['vehicle']);
    $cash_in = mysqli_real_escape_string($conn,$_POST['amount']); 
    $month = mysqli_real_escape_string($conn,$_POST['month']);
    $cash_out = 0;
    $date = mysqli_real_escape_string($conn,$_POST['date']);  

    $expanse = mysqli_real_escape_string($conn,$_POST['expanse']);
    $expanse_note = mysqli_real_escape_string($conn,$_POST['expanse_note']);
    $notes = 'Vehicle Profit added';

    //$notes  = 'Vehicle Profit Added';
    $status = 1;
    $p_id_array = $_POST['p_id'];
    $queryFired = mysqli_query($conn, "INSERT INTO `profit_book`(`vehicle_id`,`t_id`,`p_id`,`type`, `note`, `cash_in`, `cash_out`, `date`,`month`, `status`)
     VALUES ('$vehicle_id','$vehicle_id',0,'profit','$notes','$cash_in','$cash_out','$date','$month','$status')");
    $last_id = mysqli_insert_id($conn);

    $queryCashFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id,cash_in, cash_out, date, notes, status) 
    VALUES ('$vehicle_id','Vehicle profit','$vehicle_id',0,'$expanse','$date','$expanse_note','$status')");

    $profit_id = mysqli_insert_id($conn); 

    $queryFired1 = mysqli_query($conn, "INSERT INTO `profit_book`(`vehicle_id`,`t_id`,`p_id`,`type`, `note`, `cash_in`, `cash_out`, `date`,`month`, `status`)
     VALUES ('$vehicle_id','$profit_id','$last_id','expanse','$expanse_note',0,'$expanse','$date','$month','$status')");

    $parcentage_array = $_POST['parcentage'];
    $p_amount_array = $_POST['p_amount'];
    $e_amount_array = $_POST['e_amount'];
    $t_amount_array = $_POST['t_amount'];

    // Create an empty array to store editable data
        $distribution = [];

        // Function to add an array to the editable array
        function addData($data) {
            global $distribution;
            $distribution[] = $data;
        }

        foreach ($p_id_array as $index => $p_id) {
            $data = ['name' => $p_id, 
                     'parcentage' => $parcentage_array[$index], 
                     'amount' => $t_amount_array[$index],
                     'expanse' => $e_amount_array[$index],
                     'profit' => $p_amount_array[$index],
                    ];
            addData($data);
        }

    if ($queryFired && $queryFired1) {
        foreach ($distribution as $index => $data) {

                $consumer = $data['name'];
                $parcentage = $data['parcentage'];       
                $amount = $data['amount'];
                      
                $expanse = $data['expanse']; 
                $profit = $data['profit'];

            $type = "monthly profit";
            //$message = "Vehicle monthly profit";
            if ($amount < 0) {
                $credit = 0;
                $debit = abs($amount);
            } else {
                $credit = $amount;
                $debit = 0;
            }

            $queryPartner = "INSERT INTO `partner_profit_book`(`p_id`, `t_id`, `profit_note`, `expanse_note`,`percentage`, `gross_profit`, `expanse`, `net_profit`, `month`, `date`, `status`) 
            VALUES ('$consumer','$last_id','$notes','$expanse_note','$parcentage','$profit','$expanse','$amount','$month','$date',1)";
    
            if (mysqli_query($conn, $queryPartner)) {}

           
            $last_pp_id = mysqli_insert_id($conn);
            $queryCustomer = "INSERT INTO `partner_details`(`p_id`, `t_id`, `type`, `note`, `credit`, `debit`, `date`, `status`) 
            VALUES ('$consumer','$last_pp_id','$type','$notes','$credit','$debit','$date',1)";

            if (mysqli_query($conn, $queryCustomer)) {}

        }
            

        header('location:../partners-index.php?successMessage=Vehicle Profit added');
            
    } else {
        header('location:../partners-index.php?errorMessage=Vehicle Profit adding error');
    }
}

if (isset($_POST['update_profit'])) {

    $profit_id = mysqli_real_escape_string($conn,$_POST['profit_id']);
    $cash_id = mysqli_real_escape_string($conn,$_POST['cashbook_id']);

    //$vehicle_id = mysqli_real_escape_string($conn,$_POST['vehicle']);
    $cash_in = mysqli_real_escape_string($conn,$_POST['amount']); 
    $month = mysqli_real_escape_string($conn,$_POST['month']);
    $cash_out = 0;
    $date = mysqli_real_escape_string($conn,$_POST['date']);  

    $expanse = mysqli_real_escape_string($conn,$_POST['expanse']);
    $expanse_note = mysqli_real_escape_string($conn,$_POST['expanse_note']);
    $notes = mysqli_real_escape_string($conn,$_POST['amount_note']);

    //$notes  = 'Vehicle Profit Added';
    $status = 1;
    $p_id_array = $_POST['p_id'];

    $queryFiredUpdate = mysqli_query($conn, "UPDATE `profit_book` SET `note` = '$notes', `cash_in` = '$cash_in', `date` = '$date' ,`month`='$month' WHERE id='$profit_id'");


    $queryCashFiredUpdate = mysqli_query($conn, "UPDATE cashbook SET cash_out='$expanse', date='$date', notes ='$expanse_note' WHERE id='$cash_id'");


    $queryFired1Update = mysqli_query($conn, "UPDATE `profit_book` SET `note` = '$expanse_note', `cash_out` = '$expanse', `date` = '$date' ,`month`='$month' WHERE p_id='$profit_id' AND type='expanse'");


    $parcentage_array = $_POST['parcentage'];
    $p_amount_array = $_POST['p_amount'];
    $e_amount_array = $_POST['e_amount'];
    $t_amount_array = $_POST['t_amount'];

    // Create an empty array to store editable data
        $distribution = [];

        // Function to add an array to the editable array
        function addData($data) {
            global $distribution;
            $distribution[] = $data;
        }

        foreach ($p_id_array as $index => $p_id) {
            $data = ['name' => $p_id, 
                     'parcentage' => $parcentage_array[$index], 
                     'amount' => $t_amount_array[$index],
                     'expanse' => $e_amount_array[$index],
                     'profit' => $p_amount_array[$index],
                    ];
            addData($data);
        }

    if ($queryFiredUpdate && $queryFired1Update) {
        foreach ($distribution as $index => $data) {

                $consumer = $data['name'];
                $parcentage = $data['parcentage'];       
                $amount = $data['amount'];
                      
                $expanse = $data['expanse']; 
                $profit = $data['profit'];

            $type = "monthly profit";
            //$message = "Vehicle monthly profit";
            if ($amount < 0) {
                $credit = 0;
                $debit = abs($amount);
            } else {
                $credit = $amount;
                $debit = 0;
            }

            $check = mysqli_query($conn, "SELECT * FROM partner_profit_book WHERE t_id='$profit_id' AND p_id='$consumer'");
            $checkresult = mysqli_fetch_array($check);
            $p_p_id = $checkresult['id'];

            $queryPartnerUpdate = "UPDATE `partner_profit_book` SET  `profit_note` = '$notes', `expanse_note` = '$expanse_note', `gross_profit` = '$profit', `expanse` ='$expanse', `net_profit` ='$amount', `month`='$month' , `date` ='$date' WHERE t_id='$profit_id' AND p_id='$consumer'";
    
            if (mysqli_query($conn, $queryPartnerUpdate)) {}

          
            $queryCustomerUpdate = "UPDATE `partner_details` set  `note`='$notes', `credit` = '$credit', `debit`='$debit', `date`='$date' WHERE t_id='$p_p_id'";

            if (mysqli_query($conn, $queryCustomerUpdate)) {}
        }

            
        header('location:../account-profitbook.php?successMessage=Vehicle Profit updated');
            
    } else {
        header('location:../account-profitbook.php?errorMessage=Vehicle Profit updated error');
    }
}

if (isset($_POST['delete_profit'])) {

    $profit_id = mysqli_real_escape_string($conn,$_POST['profit_id']);
    $cash_id = mysqli_real_escape_string($conn,$_POST['cashbook_id']);

    //$vehicle_id = mysqli_real_escape_string($conn,$_POST['vehicle']);
    $cash_in = mysqli_real_escape_string($conn,$_POST['amount']); 
    $month = mysqli_real_escape_string($conn,$_POST['month']);
    $cash_out = 0;
    $date = mysqli_real_escape_string($conn,$_POST['date']);  

    $expanse = mysqli_real_escape_string($conn,$_POST['expanse']);
    $expanse_note = mysqli_real_escape_string($conn,$_POST['expanse_note']);
    $notes = mysqli_real_escape_string($conn,$_POST['amount_note']);

    //$notes  = 'Vehicle Profit Added';
    $status = 1;
    $p_id_array = $_POST['p_id'];
    mysqli_begin_transaction($conn);

    mysqli_autocommit($conn, false);

    $queryFiredDelete = mysqli_query($conn, "DELETE FROM `profit_book` WHERE id='$profit_id'");


    $queryCashFiredDelete = mysqli_query($conn, "DELETE FROM cashbook WHERE id='$cash_id'");


    $queryFired1Delete = mysqli_query($conn, "DELETE FROM `profit_book` WHERE p_id='$profit_id' AND type='expanse'");


    $parcentage_array = $_POST['parcentage'];
    $p_amount_array = $_POST['p_amount'];
    $e_amount_array = $_POST['e_amount'];
    $t_amount_array = $_POST['t_amount'];

    // Create an empty array to store editable data
        $distribution = [];

        // Function to add an array to the editable array
        function addData($data) {
            global $distribution;
            $distribution[] = $data;
        }

        foreach ($p_id_array as $index => $p_id) {
            $data = ['name' => $p_id, 
                     'parcentage' => $parcentage_array[$index], 
                     'amount' => $t_amount_array[$index],
                     'expanse' => $e_amount_array[$index],
                     'profit' => $p_amount_array[$index],
                    ];
            addData($data);
        }

    if ($queryFiredDelete && $queryFired1Delete && $queryCashFiredDelete) {
        
         $query = false;
        foreach ($distribution as $index => $data) {

            $consumer = $data['name'];
                
            $check = mysqli_query($conn, "SELECT * FROM partner_profit_book WHERE t_id='$profit_id' AND p_id='$consumer'");
            $checkresult = mysqli_fetch_array($check);
            $p_p_id = $checkresult['id'];

            $queryPartnerUpdate = "DELETE FROM `partner_profit_book` WHERE t_id='$profit_id' AND p_id='$consumer'";
    
            if (mysqli_query($conn, $queryPartnerUpdate)) {
                $query = true;
            }
          
            $queryCustomerUpdate = "DELETE FROM `partner_details` WHERE t_id='$p_p_id'";

            if (mysqli_query($conn, $queryCustomerUpdate)) {
                $query = true;
            }

        }

        if($query)   {  
            mysqli_commit($conn);  
        header('location:../account-profitbook.php?successMessage=Vehicle Profit deleted');
        }else{
            mysqli_rollback($conn);
        header('location:../account-profitbook.php?errorMessage=Vehicle Profit deleted error');
        }
            
    } else {
        mysqli_rollback($conn);
        header('location:../account-profitbook.php?errorMessage=Vehicle Profit deleted error');
    }
    // Set autocommit back to true
    mysqli_autocommit($conn, true);
}

if (isset($_POST['profit_withdraw']) && $_POST['profit_withdraw'] == "profit_withdraw") {

    $vehicle_id = mysqli_real_escape_string($conn,$_POST['vehicle_id']);
    $month = mysqli_real_escape_string($conn,$_POST['month']);
    $cash_in = 0; 
    $cash_out = mysqli_real_escape_string($conn,$_POST['amount']);
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    $notes  = mysqli_real_escape_string($conn,$_POST['note']);
    $status = 1;

    
 
    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id,cash_in, cash_out, date, notes, status) 
    VALUES ('$vehicle_id','Vehicle profit','$vehicle_id','$cash_in','$cash_out','$date','$notes','$status')");

    $profit_id = mysqli_insert_id($conn); 

    $queryFiredProfit = mysqli_query($conn, "INSERT INTO `profit_book`(`vehicle_id`,`t_id`,`p_id`,`type`,`note`, `cash_in`, `cash_out`, `date`,`month`, `status`)
     VALUES ('$vehicle_id','$profit_id',0,'withdraw','$notes','$cash_in','$cash_out','$date','$month','$status')");


    if ($queryFired && $queryFiredProfit ) {
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

    $cashbook_id = $_GET['id'];
    if (isset($cashbook_id)) {
        header('location:../account-profitbook-update.php?cashbook_id=' . $cashbook_id);
    }
}

if (isset($_POST['profitbook_enteries_update']) && $_POST['profitbook_enteries_update'] == "Update") {

    $cashbook_record_id = mysqli_real_escape_string($conn, $_POST['cashbook_record_id']);
    $t_id = mysqli_real_escape_string($conn, $_POST['cashbook_id']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $cash_in = mysqli_real_escape_string($conn, $_POST['cash_in']);
    $old_cash_in = mysqli_real_escape_string($conn, $_POST['old_cash_in']);
    $cash_out = mysqli_real_escape_string($conn, $_POST['cash_out']);
    $old_cash_out = mysqli_real_escape_string($conn, $_POST['old_cash_out']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    // cashbook query for all the records.
    $querProfitBookFired = mysqli_query($conn, "UPDATE profit_book SET cash_in = '$cash_in' , cash_out = '$cash_out', date = '$date' WHERE id = '$cashbook_record_id'");

    if($cash_in == "" || $cash_in == 0){

        $querCashBookFired = mysqli_query($conn, "UPDATE cashbook SET cash_in = '$cash_in' , cash_out = '$cash_out', date = '$date' WHERE id = '$t_id'");

        if ($querCashBookFired && $querProfitBookFired) {
            mysqli_commit($conn);
            header('location:../account-profitbook.php?successMessage=Record updated successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-profitbook.php?errorMessage=Record updating error');
        }
        // Set autocommit back to true
        mysqli_autocommit($conn, true);
    }else{
        if ($querProfitBookFired ) {
            mysqli_commit($conn);
            header('location:../account-profitbook.php?successMessage=Record updated successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-profitbook.php?errorMessage=Record updating error');
        }
        // Set autocommit back to true
        mysqli_autocommit($conn, true);
    }

}


if (isset($_POST['profitbook_enteries_delete']) && $_POST['profitbook_enteries_delete'] == "Delete") {

    $cashbook_record_id = mysqli_real_escape_string($conn, $_POST['cashbook_record_id']);
    $t_id = mysqli_real_escape_string($conn, $_POST['cashbook_id']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $cash_in = mysqli_real_escape_string($conn, $_POST['cash_in']);
    $old_cash_in = mysqli_real_escape_string($conn, $_POST['old_cash_in']);
    $cash_out = mysqli_real_escape_string($conn, $_POST['cash_out']);
    $old_cash_out = mysqli_real_escape_string($conn, $_POST['old_cash_out']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    // cashbook query for all the records.
    $querProfitBookFired = mysqli_query($conn, "DELETE FROM profit_book WHERE id = '$cashbook_record_id'");

    if($cash_in == "" || $cash_in == 0){

        $querCashBookFired = mysqli_query($conn, "DELETE FROM cashbook WHERE id = '$t_id'");

        if ($querCashBookFired && $querProfitBookFired) {
            mysqli_commit($conn);
            header('location:../account-profitbook.php?successMessage=Record Deleted successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-profitbook.php?errorMessage=Record deleted error');
        }
        // Set autocommit back to true
        mysqli_autocommit($conn, true);
    }else{
        if ($querProfitBookFired ) {
            mysqli_commit($conn);
            header('location:../account-profitbook.php?successMessage=Record deleted successfully');
        } else {
            mysqli_rollback($conn);
            header('location:../account-profitbook.php?errorMessage=Record deleted error');
        }
        // Set autocommit back to true
        mysqli_autocommit($conn, true);
    }


}

 
