 
<?php include('Master/head.php');
$conn = OpenCon();
$id =$_GET['p_id']; 

$stmtpartners = mysqli_query($conn, "SELECT * FROM partners WHERE id='$id' AND status = 1");
foreach ($stmtpartners as $value) {
  $partner_name= $value['name'];
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
          <li class="breadcrumb-item active">Details</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
    <div class="app-content content">
        <div class="content-wrapper">   
            <div class="content-body card card-body p-4">
            <?php if(isset($_GET['SuccessMsg'])){?>
                                         <div class="alert bg-theme  alert-dismissible fade show" role="alert">
                                             <strong><?php echo $_GET['SuccessMsg'];?></strong>
                                             <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                 aria-label="Close"></button>
                                         </div>
                                         <?php } if(isset($_GET['SuccessErr'])){ ?>
                                         <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                             <strong><?php echo @$_GET['SuccessErr'];?></strong>
                                             <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                 aria-label="Close"></button>
                                         </div>
                                         <?php  }?>
            <div class="row mt-2">
                         <div class="col-md-9"></div>
                         

                          <div class="col-md-3">
                              
                                <button type="button" class="btn btn-dark btn-block" data-bs-toggle="modal"
                                    data-bs-target="#proditWithdraw">
                                    Profit Withdraw
                                </button>
                            </div>
                          
                      </div>
                <div class="row mt-2">
                    <div class="col-md-10">
                    
                      <h4><?php echo $partner_name;?></h4>

                      
                   </div>

                   <div class="col-md-2" style="margin-top:0px;">
                      <table >
                         <tr>
                         <input type="hidden" id="p_id" name="p_id" value="<?php echo $id;?>">
                          <td width="5%">
                              <select class="form-control chosen" placeholder="" name="detailtype" id="sale_details"> 
                                <option value="2" selected>Yearly</option>
                                <option value="4">Custom</option>
                              </select>
                          </td>
                        
                          </tr>
                       </table>
                   </div>
                </div>
                <hr>
                <div id="result">
                </div>
            </div>
        </div>
</div>
    </section>

  </main><!-- End #main -->

   <!-- Modal Give to Vehicle -->
   <div class="modal fade" id="proditWithdraw" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Profit withdraw</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Floating Labels Form -->
                        <form class="row g-3" action="Controllers/PartnersController.php" method="post" onsubmit="handleSubmit(event, this)">

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control"
                                        value="<?php echo $current_vehicle_number;?>">
                                        
                                    <input type="hidden" name="partner_id" value="<?php echo $id;?>">
                                    <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id;?>">
                                    <label for="vehicle">Vehicle</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control"
                                        value="<?php echo $partner_name;?>">
                                    <label for="vehicle">Partner</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        required placeholder="Amount">
                                    <label for="amount">Amount</label>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="note" name="note">
                                    <label for="date">Note</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date" required placeholder="date">
                                    <label for="date">Date</label>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="text-center">
                                    
                                    <input type="hidden" name="profit_withdraw" value="profit_withdraw">
                                    <button type="submit"
                                        class="btn btn-dark btn-lg">Submit</button>

                                </div>

                            </div>

                        </form>
                        <!-- Floating Labels Form -->
                    </div>
                </div>
            </div>
          </div>
        </div>
    

<!-- Modal Give to Vehicle -->
        <div class="modal fade" id="deleteRecord" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Profit Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <p>Are you sure you want to delete this record?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger " id="confirmDelete">Yes</button>
                    </div>
                </div>
            </div>
        </div>

<input type="hidden" id="recordId">

  <!-- ======= Footer ======= -->
  <?php include('Master/footer.php');?>
  <!-- End Footer -->
  
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  
  <!-- Scripts -->
  <?php include('Master/scripts.php');?>
  <script>

            var selected_date = document.getElementById("sale_details");
            var selected_date_id =selected_date.value;
            var partner = document.getElementById("p_id");
            var partner_id = partner.value;

            getSalesRow(selected_date_id,partner_id);

            $('select[name="detailtype"]').on('change', function() {
              
            var selected_date = document.getElementById("sale_details");
            var selected_date_id =selected_date.value;
            var partner = document.getElementById("p_id");
            var partner_id = partner.value;

            getSalesRow(selected_date_id,partner_id);

                
            });
              
            function getSalesRow(selected_date_id,partner_id){
                  $.ajax({
                    type: 'POST',
                    url: 'get_partner_details.php',
                    data: {id:selected_date_id,p_id:partner_id},
                    dataType: 'text',
                    success: function(response){
                     $('#result').html(response)
                        
                    }
                  });
                  
                }
</script>
  <!-- Scripts -->

</body>

</html>