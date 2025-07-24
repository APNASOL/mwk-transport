 
<?php include('Master/head.php');
$connect = OpenCon();


if(isset($_GET['order_id'])){
    $id =$_GET['order_id']; 
    $queryCash = "SELECT * FROM partner_details WHERE id='$id'";  
                $resultCash = mysqli_query($connect, $queryCash);
                if(mysqli_num_rows($resultCash) > 0){
                    while($cashRow = mysqli_fetch_array($resultCash)){
                        $partner = $cashRow["p_id"]; 
                        $sale_id =$cashRow["id"];
                        $date = $cashRow["date"];           
                        $t_id = $cashRow["t_id"];
                        $type = $cashRow["type"];
                        $note = $cashRow["note"]; 
                        $amount_in =$cashRow["credit"];
                        $amount_out =$cashRow["debit"];

                           if($type == "monthly profit"){

                            $partnerProfit = mysqli_query($connect, "SELECT * FROM partner_profit_book WHERE id = $t_id");
                            $partnerProfitRow = mysqli_fetch_array($partnerProfit);
               
                           }
                    }
                }

                $months = array(
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July ',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December',
                );
    
    $currentdate = explode("-",$date);
        
    $dat = $currentdate[2];
    $month = $currentdate[1];
    $year = $currentdate[0];
    $month_name=$months[$month-1];
    
$res=$dat.' '.$month_name.' , '.$year; 

                $query3 = "SELECT name FROM partners WHERE id='$partner';";
                $result3 = mysqli_query($connect, $query3);
                if (mysqli_num_rows($result3) > 0) {
                  $row = mysqli_fetch_array($result3);
    
                  $username = $row['name'];
                } else {
                  $username = $customer_name;
                }

}


?>

<body>
  
  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Partner</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Update</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        
        <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                            <?php if($type != "monthly profit"){ ?>
                                <div>
                                    <form class="form-horizontal" method="POST" action="Controllers/PartnersController.php">
                                            
                                            <div class="row form-group">
                                            
                                                <div class="col">
                                                    <p>Partner</p> 
                                                    <input type="hidden" class="form-control" id="id" name="t_id" value="<?php echo $t_id;?>" readonly>
                                                    <input type="hidden" class="form-control" id="id" name="type" value="<?php echo $type;?>" readonly>
                                                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $sale_id;?>" readonly>
                                                    <input type="hidden" class="form-control" id="old_partner" name="old_partner" value="<?php echo $partner;?>" readonly>
                                                    <input type="text" class="form-control" id="note" name="u_name" value="<?php echo $username;?>" readonly>
                                                
                                                </div>
                                                <div class="col">
                                                    <p>Message</p>
                                                    <input type="text" class="form-control" id="note" name="note" value="<?php echo $note;?>" >
                                                
                                                </div>

                                            <div>

                                            <div class="row form-group">

                                                <div class="col">
                                                    <p>Debit Amount</p>
                                                    
                                                    <input type="text" class="form-control" id="cash_in" name="cash_in" value="<?php echo $amount_in;?>">
                                                </div>
                                            
                                            
                                                <div class="col">
                                                    <p>Credit Amount</p>
                                                    
                                                    <input type="text" class="form-control" id="cash_out" name="cash_out" value="<?php echo $amount_out;?>">
                                                </div>
                                            
                                                <div class="col">
                                                    <input type="hidden" class="form-control" id="o_date" name="o_date" value="<?php echo $date;?>">
                                                    <p>Date</p>
                                                    <input type="date" class="form-control" id="n_date" name="date" value="<?php echo $date; ?>" required>
                                                </div>
                                            
                                            </div>

                                            <div class="row mb-2">
                                                
                                                <div class="col-sm-6">
                                                    
                                                </div>
                                                <div class="col-sm-3 mt-3">
                                                    
                                                    <button type="submit" name="btn_edit_old" class=" form-control btn btn-dark">Update</button>
                                                </div>
                                                <div class="col-sm-3 mt-3">
                                                    
                                                    <button type="submit" name="btn_p_delete" class=" form-control btn btn-danger">Delete</button>
                                                </div>
                                                <br>
                                            </div>
                                   </form>
                                   
                                </div>
                            <?php }else{
                                ?>
                                <form class="form-horizontal" method="POST" action="Controllers/PartnersController.php">
                                        <div class="row form-group">
                                            
                                            <div class="col-md-3">
                                                <p>Partner</p>
                                                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $sale_id;?>" readonly>
                                                <input type="hidden" class="form-control" id="old_partner" name="profit_id" value="<?php echo $partnerProfitRow['id'];?>" readonly>
                                                <input type="text" class="form-control" id="note" name="u_name" value="<?php echo $username;?>" readonly>
                                            
                                            </div>
                                            <div class="col">
                                                <p>Profit Message</p>
                                                <input type="text" class="form-control" id="note" name="note" value="<?php echo $partnerProfitRow["profit_note"];?>">
                                            
                                            </div>
                                            <div class="col">
                                                <p>Expanse Message</p>
                                                <input type="text" class="form-control" id="note" name="expanse_note" value="<?php echo $partnerProfitRow["expanse_note"];?>">
                                            </div>

                                        <div>

                                        <div class="row form-group mt-2">

                                            <div class="col">
                                                <p>Gross Profit</p>
                                                <input type="text" class="form-control" id="profit" name="profit" value="<?php echo $partnerProfitRow["gross_profit"];?>" oninput="calculateDistributedAmounts()">
                                            </div>
                                        
                                        
                                            <div class="col">
                                                <p>Expanse</p>
                                                <input type="text" class="form-control" id="expanse" name="expanse" value="<?php echo $partnerProfitRow["expanse"];?>" oninput="calculateDistributedAmounts()">
                                            </div>

                                            <div class="col">
                                                <p>Net Profit</p>
                                                <input type="text" class="form-control" id="net_profit" name="net_profit" readonly>
                                            </div>

                                            <div class="col">
                                                <input type="hidden" class="form-control" id="o_date" name="o_date" value="<?php echo $date;?>">
                                                <p>Month</p>
                                                <input type="text" class="form-control" id="n_date" name="month" value="<?php echo $partnerProfitRow["month"]; ?>" >
                                            </div>
                                        
                                            <div class="col">
                                                <input type="hidden" class="form-control" id="o_date" name="o_date" value="<?php echo $date;?>">
                                                <p>Date</p>
                                                <input type="date" class="form-control" id="n_date" name="date" value="<?php echo $date; ?>" >
                                            </div>
                                        
                                        </div>

                                        <div class="row mb-2">
                                                
                                                <div class="col-sm-6">
                                                    
                                                </div>
                                                <div class="col-sm-3 mt-3">
                                                    
                                                    <button type="submit" name="btn_edit_profit" class=" form-control btn btn-dark">Update</button>
                                                </div>
                                                <div class="col-sm-3 mt-3">
                                                    
                                                    <button type="submit" name="btn_delete_profit" class=" form-control btn btn-danger">Delete</button>
                                                </div>
                                                <br>
                                            </div>

                            </form>
                                        
                                <?php 
                            }?>    
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
       
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include('Master/footer.php');?>
  <!-- End Footer -->
  
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
  <!-- Scripts -->
  <?php include('Master/scripts.php');?>
  <!-- Scripts -->

</body>

<script>
calculateDistributedAmounts();
        function calculateDistributedAmounts() {
            // Get the remaining amount
            var remainedAmount = parseFloat(document.getElementById('profit').value) || 0;
            var expanseAmount = parseFloat(document.getElementById('expanse').value) || 0;
            var profit = document.getElementById("net_profit")
            var netProfit=remainedAmount-expanseAmount;
            profit.value = isNaN(netProfit) ? '' : netProfit;

        }
    
    


</script>

</html>