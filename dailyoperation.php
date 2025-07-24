<?php include('Master/head.php');
$conn = OpenCon();

date_default_timezone_set('Asia/Karachi');
$currentDate = date('Y-m-d');
echo $currentDate;

?>

 <body>

     <!-- ======= Header ======= -->
     <?php include('Master/header.php');?>
     <!-- ======= Sidebar ======= -->
     <?php include('Master/aside.php');?>
     <!-- End Sidebar-->

     <main id="main" class="main">

         <div class="pagetitle">
             <h1>Order Entry</h1>
             <nav>
                 <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                     <li class="breadcrumb-item active">Order Entry</li>
                 </ol>
             </nav>
         </div><!-- End Page Title -->

         <section class="section dashboard">
             <div class="app-content content">
                 <div class="content-wrapper">


                     <div class="content-body">
                         <!-- DOM - jQuery events table -->
                         <section id="dom">
                             <div class="row">
                                 <div class="col-12">
                                     <div class="card">
                                         <div class="card-header">
                                             <div class="row form-group">
                                                 <div class="col-sm-5">

                                                     <font class="card-title">Daily Operation </font>
                                                 </div>

                                                  
                                                 <div class="col-sm-2">
                                                    <label for="No's of Entry">No's of Trips</label>
                                                     <input type="number" class="form-control" id="number"
                                                         name="number">
                                                 </div>
 
                                                 <div class="col-sm-3">
                                                 <label for="Date">Date</label>             
                                                     <input type="date" class="form-control" id="dateInput" name="date" max="<?php echo $currentDate; ?>" required>
                                                 </div>
                                                 <div class="col-sm-2 mt-4">
                                                     <button name="no" class="form-control btn btn-dark order">+</button>
                                                 </div>
                                             </div>
                                         </div>
                                         <div id="result">
                                             <div class="card-content">
                                                 <div class="card-body card-dashboard dataTables_wrapper dt-bootstrap">
                                                     <div class="table-responsive">
                                                         <table id="example3" class="table table-bordered">
                                                             <thead>
                                                                 <tr>
                                                                     <th>Customer</th>
                                                                     <th>Mine</th>
                                                                     <th>Vehicle</th>
                                                                     <th>Truck Weight</th>
                                                                     <th>Price per ton</th>
                                                                     <th>Truck Amount</th>
                                                                 </tr>
                                                             </thead>
                                                             <tr>
                                                                 <td colspan="6" align="center"> Please select data and
                                                                     numbers of trips entries </td>
                                                             </tr>
                                                         </table>
                                                     </div>
                                                 </div>
                                             </div>

                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </section>
                         <!-- DOM - jQuery events table -->
                     </div>

                     <!--   <div class="content-body">
            
        
    </div> -->
                 </div>
             </div>
         </section>

     </main><!-- End #main -->

     <!-- ======= Footer ======= -->
     <?php include('Master/footer.php');?>
     <!-- End Footer -->

     <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
             class="bi bi-arrow-up-short"></i></a>

     <!-- Scripts -->
     <?php include('Master/scripts.php');?>
     <script>


         $(document).on('click', '.order', function (e) {
             var date = $('#dateInput').val();
             var number = $('#number').val();
             if(date==""){
                alert("Please select date")
             }else{
             getRow(date, number);
             }
         });

         function getRow(date, number) {
             $.ajax({
                 type: 'POST',
                 url: 'get_trips_entries.php',
                 data: {
                     date: date,
                     number: number
                 },
                 dataType: 'text',
                 success: function (response) {
                     $('#result').html(response)
                 }
             });

         }

     </script>
     <!-- Scripts -->

 </body>

 </html>