<?php include 'Master/head.php';
$conn = OpenCon();
$stmt = mysqli_query($conn, "SELECT * FROM partners WHERE id='" . $_GET['id'] . "'");
$result = mysqli_fetch_array($stmt);
?>

 <body>

     <!-- ======= Header ======= -->
     <?php include 'Master/header.php';?>
     <!-- ======= Sidebar ======= -->
     <?php include 'Master/aside.php';?>
     <!-- End Sidebar-->

     <main id="main" class="main">

         <div class="pagetitle">
             <h1><?=$result['name']?></h1>
             <nav>
                 <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                     <li class="breadcrumb-item"><a href="partners.php"> Partners </a> </li>
                     <li class="breadcrumb-item active">Edit</li>
                 </ol>
             </nav>
         </div><!-- End Page Title -->

         <section class="section dashboard">
             <!-- BEGIN: Content-->
             <div class="app-content content">
                 <div class="content-wrapper">

                     <div class="content-body">
                         <!-- Input Validation start -->
                         <section class="input-validation">
                             <div class="row">
                                 <div class="col-md-12">
                                     <div class="card">
                                         <div class="card-header">
                                             <a class="heading-elements-toggle"><i
                                                     class="la la-ellipsis-v font-medium-3"></i></a>
                                             <div class="heading-elements">
                                                 <ul class="list-inline mb-0">
                                                     <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                                 </ul>
                                             </div>
                                         </div>
                                         <div class="card-content collapse show">
                                             <div class="card-body">

                                                 <form class="form-horizontal" method="POST"
                                                     action="Controllers/PartnersController.php">
                                                     <input type="hidden" name="id" value="<?=$result['id']?>">
                                                     <div class="row">
                                                         <div class="col-md-4">
                                                             <label>User Name</label>
                                                             <div>
                                                                 <input type="text" class="form-control"
                                                                     value="<?=$result['name']?>" name="user_name"
                                                                     placeholder="Enter unique name" required>
                                                             </div>
                                                         </div>

                                                         <div class="col-md-4">
                                                             <label>Contact</label>
                                                             <div>
                                                                 <input type="number" class="form-control"
                                                                     value="<?=$result['contact']?>" name="contact"
                                                                     placeholder="Contact number" required>
                                                             </div>
                                                         </div>
                                                         <div class="col-md-4">
                                                             <label>Percentage</label>
                                                             <div>
                                                                 <input type="number" class="form-control"
                                                                     value="<?=$result['percentage']?>" name="percentage"
                                                                     placeholder="Contact number" required>
                                                             </div>
                                                         </div>
                                                         
                                                     </div>

                                                    

                                                     </div>
                                                     <div class="row modal-footer">
                                                        <div class="row">
                                                        <div class="col-md-9"> </div>
                                                          <div class="col">   
                                                           <input type="submit" class="btn btn-dark" name="btn_edit"
                                                             value="Save">
                                                          </div>
                                                          <div class="col">   
                                                           <input type="submit" class="btn btn-danger" name="btn_delete"
                                                             value="Delete">
                                                          </div>
                                                        </div>

                                                        <!-- <a href="Controllers/CustomersController.php?detete_customer_id=<?php echo $result['id']; ?>"
                                                             class="btn btn-danger">Delete <i
                                                                 class="la la-close position-right"></i></a>-->
                                                     </div>
                                                 </form>



                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </section>
                         <!-- Input Validation end -->
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
     <!-- Scripts -->

 </body>

 </html>