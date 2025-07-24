<?php include 'Master/head.php';?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Customers</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Customers</li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
            
        </div><!-- End Page Title -->

        <section class="section">
            <?php
if (@$_GET['successMessage']) {
    ?>
            <div class="alert alert-success bg-success text-light alert-dismissible fade show" role="alert">
                <?php echo $_GET['successMessage']; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>

            <?php
}
?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Customers</h5>

                    <?php
$conn = OpenCon();
$customer_name = "";
$customer_id = $_GET['customer_id'];
$customer = mysqli_query($conn, "SELECT * FROM customers WHERE id =  $customer_id");
$customer_details = mysqli_query($conn, "SELECT * FROM customer_details WHERE customer_id =  $customer_id");
?>
                    <div class="card card-body p-4">
                        <?php
             while ($single = mysqli_fetch_array($customer)) {

              $customer_name = $single['name'];
    
                           echo "<strong>Name: " .$single['name']."</strong> <strong>Contact : " . $single['contact']."</strong>";                  
                
             }?>
                    </div>

                </div>


                <!-- Table with stripped rows -->
                <div class="card  card-body p-3">
                    <table class="table table-striped datatable" id="example">
                        <thead>

                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Notes</th>
                                <th scope="col">Type</th>
                                <th scope="col">Credit</th>
                                <th scope="col">Debit</th>
                                <th scope="col">Balance</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php
$i = 1;
$balance = 0;

while ($customer = mysqli_fetch_array($customer_details)) {
  if($customer['type'] == 'Trip')
  {
    $balance = $balance + $customer['debit'];
  }  else{
    $balance = $balance + $customer['debit'] - $customer['credit'];
  }
  if($customer['type'] == 'Cash Payment' || $customer['type'] == 'Due'){
  ?>
                            <tr class="bg-danger">
                                <th class="text-white" scope="row"><?php echo $i++; ?></th>

                                <td class="text-white"><?php echo $customer['date']; ?></td>
                                <td class="text-white"><?php echo $customer['note']; ?></td>
                                <td class="text-white"><?php echo $customer['type']; ?></td>
                                <td class="text-white"><?php echo $customer['credit']; ?></td>
                                <td class="text-white"><?php echo $customer['debit']; ?></td>
                                <td class="text-white"><?php echo $balance; ?></td>

                            </tr>
                            <?php }else if($customer['type'] == 'Cash Return'){
  ?>
                            <tr class="bg-success">
                                <th class="text-white" scope="row"><?php echo $i++; ?></th>

                                <td class="text-white"><?php echo $customer['date']; ?></td>
                                <td class="text-white"><?php echo $customer['note']; ?></td>
                                <td class="text-white"><?php echo $customer['type']; ?></td>
                                <td class="text-white"><?php echo $customer['credit']; ?></td>
                                <td class="text-white"><?php echo $customer['debit']; ?></td>
                                <td class="text-white"><?php echo $balance; ?></td>

                            </tr><?php }else{?>
                            <tr>
                                <th scope="row"><?php echo $i++; ?></th>

                                <td><?php echo $customer['date']; ?></td>
                                <td><?php echo $customer['note']; ?></td>
                                <td><?php echo $customer['type']; ?></td>
                                <td><?php echo $customer['credit']; ?></td>
                                <td><?php echo $customer['debit']; ?></td>
                                <td><?php echo $balance; ?></td>

                            </tr>
                            <?php }}?>
                        </tbody>
                    </table>
                </div>
                <!-- End Table with stripped rows -->

            </div>
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
   
    </style>
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
          searching: false,
            "paging": false,
            "info": false,
          searching: false,
            order: [],

            dom: 'Bfrtip',
            buttons: [{
                extend: 'pdfHtml5',
                text: 'Download details',
                title: '<?php echo $customer_name;?>',
                className: 'btn btn-dark',
                
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // export only columns
                },
                customize: function(doc) {
                  // Add a header to the PDF document
                  doc.content.unshift({
                    text: 'Customer Deatils',
                    style: 'header'
                  });
                  
                  // Add a style for the header text
                  doc.styles.header = {
                    fontSize: 18,
                    bold: true,
                    margin: [0, 0, 0, 10]
                  };
                  
                },
                 
                orientation: 'portrait',
                pageSize: 'A4'
            }]
        });
    });
    </script>
</body>

</html>