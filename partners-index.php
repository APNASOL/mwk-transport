<?php include 'Master/head.php';
// session_start();
$conn = OpenCon();
$current_vehicle_id = $_SESSION['current_vehicle_id'];
$partners_totals = mysqli_query($conn, "SELECT COUNT(id) AS total_customer,SUM(percentage) AS value_sum FROM partners where v_id = '$current_vehicle_id'");
$row1 = mysqli_fetch_array($partners_totals);
$t_percentage = $row1["value_sum"];
$total_partners = $row1["total_customer"];

$remained = 100 - $t_percentage;

?>

 <body>

     <!-- ======= Header ======= -->
     <?php include 'Master/header.php';?>
     <!-- ======= Sidebar ======= -->
     <?php include 'Master/aside.php';?>
     <!-- End Sidebar-->

     <main id="main" class="main">

         <div class="pagetitle">
             <h1>Partners</h1>
             <nav>
                 <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                     <li class="breadcrumb-item active">Partners</li>
                 </ol>
             </nav>
         </div><!-- End Page Title -->

         <section class="section dashboard">
             <!-- BEGIN: Content-->
             <div class="app-content content">
                 <div class="content-wrapper">

                     <div class="content-body">
                         <!-- DOM - jQuery events table -->

                         <div class="row">
                             <div class="col-12">
                                 <div class="card">
                                     <div class="card-header">
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
                                         </div>
                                         <div class="row mt-3">
                         <div class="col-md-7"></div>
                                         <div class="col-md-2">
                                         <h4 class="card-title d-flex justify-content-end"><a  class="btn btn-dark btn-block" href="profit-create.php">Add Profit</a></h4>

                          </div><div class="col-md-3">
                                         <h4 class="card-title new_partner d-flex justify-content-end me-2"><a href=""
                                                 class="btn btn-dark"><i class="bi bi-plus-lg"></i> Add New Partner</a>
                                         </h4>
                                         </div>
                                     </div>
                                     <div class="card-content p-2">
                                         
                                         <!-- Left side columns -->
                                         
                                         <?php
                                         
                                         $stmtpatient = mysqli_query($conn, "SELECT * FROM partners WHERE v_id='$current_vehicle_id' AND status = 1");
                                         $profit1 = 0;
                                         $partners = 0;
                                                            foreach ($stmtpatient as $value) {
                                                                $partners++;
                                                                $mine = $value['v_id'];
        
                                                                $id = $value['id'];
                                         $details1 = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM partner_details WHERE status=1 AND p_id='$id' ORDER BY date";
                                                                $details_result1= mysqli_query($conn, $details1);
                                                                $details_row1 = mysqli_fetch_array($details_result1);
                                                                
                                                                $c_credit1 = $details_row1["c_credit"];
                                                                if ($c_credit1 == "") {
                                                                    $c_credit1 = 0;
                                                                }
                                                                $c_debit1 = $details_row1["c_debit"];
                                                                if ($c_debit1 == "") {
                                                                    $c_debit1 = 0;
                                                                }
                                                                
                                                                $profit1 = $profit1 + $c_credit1 - $c_debit1;
                                                                
                                                                }
                                                                
                                                                ?>
                                <div class="col-lg-12">
                                    <div class="row">

                                        <!-- Revenue Card -->
                                        <div class="col-xxl-6 col-md-6">
                                            <div class="card info-card revenue-card">


                                                <div class="card-body">
                                                    <h5 class="card-title">Partners</h5>

                                                    <div class="d-flex align-items-center">

                                                        <div class="font-weight">
                                                            <h4><?=$partners;?></h4>


                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div><!-- End Revenue Card -->

                                        <!-- expense Card -->
                                        <div class="col-xxl-6 col-md-6">


                                            <div class="card info-card revenue-card">

                                                <div class="card-body">
                                                    <h5 class="card-title">Total</h5>

                                                    <div class="d-flex align-items-center">

                                                        <div class="font-weight">
                                                            <h4><?=$profit1;?></h4>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div><!-- End expense Card -->

                                    </div>
                                </div>
                            </div>
                                         
                                         <div class="dt-bootstrap">
                                             <?php // require_once('../assets/constants/check-reply.php') ;?>
                                             <div class="table-responsive  p-4">
                                             <table id="example" class="table">
                                                     <thead>
                                                         <tr>
                                                             <th>#</th>
                                                             <th>Username</th>
                                                             <th>Vehicle</th>
                                                             <th>Percentage %</th>
                                                             <th>Profit</th>
                                                             <th class="text-center">Action</th>

                                                         </tr>
                                                     </thead>
                                                     <tbody>
                                                         <?php
                                                            $i = 1;
                                                            
$stmtpatient = mysqli_query($conn, "SELECT * FROM partners WHERE v_id='$current_vehicle_id' AND status = 1");
                                                            foreach ($stmtpatient as $value) {
                                                                $mine = $value['v_id'];
                                                                $id = $value['id'];
                                                                $profit = 0;
                                                                $query3 = "SELECT number FROM vehicles WHERE id='$mine';";
                                                                $result3 = mysqli_query($conn, $query3);
                                                                if (mysqli_num_rows($result3) > 0) {
                                                                    $row3 = mysqli_fetch_array($result3);
                                                                    $mine_name = $row3['number'];
                                                                }else{
                                                                    $mine_name ="number";
                                                                }
                                                                
                                                                
                                                                $details = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM partner_details WHERE status=1 AND p_id='$id' ORDER BY date";
                                                                $details_result = mysqli_query($conn, $details);
                                                                $details_row = mysqli_fetch_array($details_result);
                                                                
                                                                $c_credit = $details_row["c_credit"];
                                                                if ($c_credit == "") {
                                                                    $c_credit = 0;
                                                                }
                                                                $c_debit = $details_row["c_debit"];
                                                                if ($c_debit == "") {
                                                                    $c_debit = 0;
                                                                }
                                                                
                                                                $profit = $c_credit - $c_debit;
                                                                ?>
                                                         <tr>
                                                             <td><?=$i?></td>
                                                             <td>
                                                             <a href="single_partner_details.php?p_id=<?php echo $id ?>"><?=$value['name']?></a>
                                                             
                                                            
                                                            </td>
                                                             <td><?=$mine_name?></td>
                                                             <td><?=$value['percentage']?></td>
                                                             <td><?=$profit ?></td>

                                                             <td class="d-flex justify-content-center">
                                                                 <a title="Edit"
                                                                     href="editpartner.php?id=<?=$value['id']?>"
                                                                     class=" mr-1 mb-1"><i
                                                                         class="bi text-dark bi-pencil"></i></a>

                                                             </td>
                                                         </tr>
                                                         <?php $i++;}?>
                                                     </tbody>
                                                 </table>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <!-- DOM - jQuery events table -->
                     </div>
                 </div>
             </div>


             <div class="modal fade" id="new_partner">
                 <div class="modal-dialog ">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h4 class="modal-title card-title">Add New Partner ( <?=$t_percentage;?>% / 100 %)</h4>
                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                         </div>
                         <h6 style="color: red;" class="m-2">Only this Percentage <?=$remained;?>% Remained</h6>
                         <form class="form-horizontal" method="POST" action="Controllers/PartnersController.php">
                           <div class="modal-body">
                            
                                 <div class="row">
                                     <div class="col-md-6">
                                         <label>User Name</label>
                                         <input type="hidden" class="form-control" id="remained" name="remained"
                                                 value="<?=$remained; ?>">
                                         <div>
                                             <input type="text" class="form-control" name="new_partner_username"
                                                 placeholder="Enter unique name" required>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <label>Contact</label>
                                         <div>
                                             <input type="number" class="form-control" name="contact"
                                                 placeholder="Contact number" required>
                                         </div>
                                     </div>
                                 </div>

                                 <br>
                                 <div class="row">

                                    <div class="col-md-4">
                                            <label>Old Amount</label>
                                            <div>
                                                <input type="number" class="form-control" name="amount"
                                                    placeholder="Partner Old Amount" required>
                                            </div>
                                 
                                    </div>
                                     
                                     <div class="col-md-4">
                                         <label>Percentage</label>
                                         <div>
                                             <input type="number" step="0.0001" class="form-control" name="percentage" id="percentage"
                                                 placeholder="Enter partner percentage" required oninput="checkPercentage()">
                                         </div>
                                     </div>
                                     
                                     <div class="col-md-4">
                                         <label>Date</label>
                                         <div>
                                             <input type="date" class="form-control" name="date"
                                                 placeholder="Enter date" required>
                                             <!-- <p>Date must be before starting order entry date</p> -->
                                         </div>
                                     </div>
                                 </div>

                            </div>

                            <div class="row modal-footer">
                                     <input type="submit" class="btn btn-dark" name="add_new_unique_partner"
                                         value="Save">
                            </div>
                         </form>
                     </div>
                 </div>
             </div>

             <!-- END: Content-->
         </section>

     </main><!-- End #main -->

     <!-- ======= Footer ======= -->
     <?php include 'Master/footer.php';?>
     <!-- End Footer -->

     <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
             class="bi bi-arrow-up-short"></i></a>

     <!-- Scripts -->
     <?php include 'Master/scripts.php';?>
     <script>

         var remainedPercentage = document.getElementById("remained").value;

         function checkPercentage(){
            var percentageInput = document.getElementById('percentage');
            var percentage = parseFloat(document.getElementById('percentage').value) || 0;
            var remainedPercentage = parseFloat(document.getElementById('remained').value) || 0;

            if(percentage > remainedPercentage){
                alert("Please enter valid Percentage");
                percentageInput.value = '';
            }

         }
          
         $(document).on('click', '.new_partner', function (e) {
             e.preventDefault();
             $('#new_partner').modal('show');
         });

         $(document).ready(function () {
            var table = $('#example').DataTable({
                "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
                ]
            });
         });



     </script>
     <!-- Scripts -->

 </body>

 </html>