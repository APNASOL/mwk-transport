<?php
include 'DatabaseController.php';
session_start();
$conn = OpenCon();
$current_vehicle_id = $_SESSION['current_vehicle_id'];

if($current_vehicle_id == 0){
    header('location:../partners-index.php?successMessage=Return');
}


if (isset($_POST["add_new_unique_partner"])) {

    $name = $_POST["new_partner_username"];
    $contact = $_POST["contact"];
    $vehicle_id = $current_vehicle_id;
    $percentage = $_POST["percentage"];
    $amount = $_POST["amount"];
    $date = $_POST["date"];

    //echo " the partner old amount: $amount";exit;

    $checkquery = "SELECT * FROM partners WHERE status=1 and name ='$name' and v_id='$vehicle_id'";

    $check = mysqli_query($conn, $checkquery);
    $checkresult = mysqli_fetch_array($check);
    if ($checkresult) {
            header('location:../partners-index.php?SuccessErr=This partner is already exist.');
            exit;
        
    } else {

        if ($amount < 0) {
            $credit = 0;
            $debit = abs($amount);
        } else {
            $credit = abs($amount);
            $debit = 0;
        }

        //echo " the partner old amount: $amount || Credit:$credit || Debit:$debit";exit;

        $query = "INSERT INTO `partners`( `name`,`v_id`,`contact`, `percentage`,`status`) VALUES ('$name','$vehicle_id','$contact','$percentage',1)";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $last_id = mysqli_insert_id($conn);
            $type = "Old";
            $message = "New partner added";
            $queryCustomer = "INSERT INTO `partner_details`(`p_id`, `t_id`, `type`, `note`, `credit`, `debit`, `date`, `status`) VALUES ('$last_id','$last_id','$type','$message','$credit','$debit','$date',1)";

            if (mysqli_query($conn, $queryCustomer)) {
                header('location:../partners-index.php?SuccessMsg=Partner record added successfully.');
                exit;
            } else {
                header('location:../partners-index.php?SuccessErr=Partner adding error.');
                exit;
            }

        } else {
            header('location:../partners-index.php?SuccessErr=Partner adding error.');
                exit; 
        }
    }

}


if (isset($_POST['profit_withdraw']) && $_POST['profit_withdraw'] == "profit_withdraw") {

    $partner_id = mysqli_real_escape_string($conn,$_POST['partner_id']);
    $vehicle_id = mysqli_real_escape_string($conn,$_POST['vehicle_id']);
    $cash_in = 0; 
    $cash_out = mysqli_real_escape_string($conn,$_POST['amount']);
    $date = mysqli_real_escape_string($conn,$_POST['date']); 
    $notes  = mysqli_real_escape_string($conn,$_POST['note']);
    $status = 1;
 
    mysqli_begin_transaction($conn);

    // Set autocommit to false to start the transaction
    mysqli_autocommit($conn, false);

    $queryFired = mysqli_query($conn, "INSERT INTO cashbook (consume_id, operation_for,vehicle_id,cash_in, cash_out, date, notes, status) 
    VALUES ('$partner_id','Vehicle profit','$vehicle_id','$cash_in','$cash_out','$date','$notes','$status')");

    $profit_id = mysqli_insert_id($conn); 

    $queryFiredProfit = mysqli_query($conn, "INSERT INTO `partner_details`(`p_id`, `t_id`, `type`, `note`, `credit`, `debit`, `date`, `status`) 
    VALUES ('$partner_id','$profit_id','Profit Withdraw','$notes','$cash_in','$cash_out','$date','$status')");

    if ($queryFiredProfit ) {
        mysqli_commit($conn);
        header('location:../single_partner_details.php?p_id='.$partner_id.'&successMessage=Customer payment added');
    } else {
        mysqli_rollback($conn);
        header('location../single_partner_details.php?p_id='.$partner_id.'&errorMessage=Customer payment adding error');
    }
    // Set autocommit back to true
    mysqli_autocommit($conn, true);
}

if(isset($_POST['btn_edit_old']))
{
	$id = $_POST['id'];
    $note = $_POST['note'];
    $amount_in = $_POST['cash_in'];
    $amount_out = $_POST['cash_out'];
    $date= $_POST['date'];

    $t_id = $_POST['t_id'];
    $type = $_POST['type'];

    $customerDetailEntery = "UPDATE partner_details SET date='$date',debit='$amount_out',note='$note', credit='$amount_in' WHERE id=$id";
	$query = mysqli_query($conn, $customerDetailEntery);

     if($type == 'Profit Withdraw'){

        $querCashBookFired = mysqli_query($conn, "UPDATE cashbook SET cash_in = '$amount_in' ,notes='$note', cash_out = '$amount_out', date = '$date' WHERE id = '$t_id'");


     }
     
	if ($query) {
        header('location:../partners-index.php?SuccessMsg=Partner record updated successfully.');
        exit;
    } else {
        header('location:../partners-index.php?SuccessErr=Partner updating error.');
        exit;
    }
}

if(isset($_POST['btn_edit_profit']))
{
	$id = $_POST['id'];
    $p_id = $_POST['profit_id'];
    $profit_note = $_POST['note'];
    $expanse_note = $_POST['expanse_note'];
    $profit = $_POST['profit'];
    $expanse = $_POST['expanse'];
    $amount = $_POST['net_profit'];
    $month = $_POST['month'];
    $date= $_POST['date'];

    $customerDetailEntery = "UPDATE partner_details SET date='$date',debit=0,note='$profit_note', credit='$amount' WHERE id=$id";
	 
	if (mysqli_query($conn, $customerDetailEntery)) {

        $partnerDetailEntery = "UPDATE partner_profit_book SET profit_note='$profit_note',expanse_note='$expanse_note', gross_profit='$profit', expanse='$expanse', net_profit='$amount', date='$date', month='$month' WHERE id=$p_id";
	 
        if (mysqli_query($conn, $partnerDetailEntery)) {

            header('location:../partners-index.php?SuccessMsg=Partner record updated successfully.');
            exit;
        }
    } else {
        header('location:../partners-index.php?SuccessErr=Partner updating error.');
        exit;
    }
}

if(isset($_POST['btn_delete_profit']))
{
    
    $id  = $_POST['id'];
    $p_id = $_POST['profit_id'];

    $query1 = mysqli_query($conn,"DELETE FROM partner_details WHERE id = '$id'");
	  
     if ($query1) {
        $query2 = mysqli_query($conn,"DELETE FROM partner_profit_book WHERE id = '$p_id'");
	  
        if ($query2) {
            header('location:../partners-index.php?SuccessMsg=Partner record delete successfully.');
            exit;
        }
    } else {
        header('location:../partners-index.php?SuccessErr=Partner record delete error.');
        exit;
    }
	 
}

if(isset($_POST['btn_edit']))
{

    $name  = $_POST['user_name'];
    $contact  = $_POST['contact'];
    $percentage  = $_POST['percentage'];

    $id  = $_POST['id'];
	$query = mysqli_query($conn,"UPDATE partners SET name= '$name',contact = '$contact', percentage='$percentage' WHERE id = '$id'");
	  
     if ($query) {
        header('location:../partners-index.php?SuccessMsg=Partner record updated successfully.');
        exit;
    } else {
        header('location:../partners-index.php?SuccessErr=Partner record updating error.');
        exit;
    }
	 
}

if(isset($_POST['btn_p_delete']))
{
    
    $id  = $_POST['id'];

    $t_id = $_POST['t_id'];
    $type = $_POST['type'];
    
    $query1 = mysqli_query($conn,"DELETE FROM partner_details WHERE id = '$id'");

    if($type == 'Profit Withdraw'){

        $querCashBookFired = mysqli_query($conn, "DELETE FROM cashbook WHERE id = '$t_id'");


     }
	  
     if ($query1) {
        header('location:../partners-index.php?SuccessMsg=Partner record delete successfully.');
        exit;
    } else {
        header('location:../partners-index.php?SuccessErr=Partner record delete error.');
        exit;
    }
	 
}

if(isset($_POST['btn_delete']))
{
    $username  = $_POST['user_name'];
    $name  = $_POST['name'];
    $contact  = $_POST['contact'];
    $percentage  = $_POST['percentage'];

    $id  = $_POST['id'];
	$query = mysqli_query($conn,"DELETE FROM partners WHERE id = '$id'");
    $query1 = mysqli_query($conn,"DELETE FROM partner_details WHERE p_id = '$id'");
    $query2 = mysqli_query($conn,"DELETE FROM partner_profit_book WHERE p_id = '$id'");
	  
     if ($query && $query1 && $query2) {
        header('location:../partners-index.php?SuccessMsg=Partner record delete successfully.');
        exit;
    } else {
        header('location:../partners-index..php?SuccessErr=Partner record delete error.');
        exit;
    }
	 
}
?>
