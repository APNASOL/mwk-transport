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
          <li class="breadcrumb-item active">Index</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <?php
if (@$_GET['successMessage']) {
    ?>
      <div class="alert alert-success bg-success text-light alert-dismissible fade show" role="alert">
        <?php echo $_GET['successMessage']; ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <?php
}
?>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Customers</h5>

          <?php 
            $conn = OpenCon();
            $customers = mysqli_query($conn, "SELECT DISTINCT username FROM customers");
          ?>
          <!-- Table with stripped rows -->
          <table class="table table-striped" id="vehicles">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Customer</th>
            <?php
            // Initialize an array to store total dues per vehicle
            
            $vehicle_totals = [];
            $vehicles_t = mysqli_query($conn, "SELECT * FROM vehicles");
            while($vehicle_t = mysqli_fetch_array($vehicles_t)) {
                $v_number = $vehicle_t['number'];
                // Initialize each vehicle total to 0
                $vehicle_totals[$vehicle_t['id']] = 0;
            ?>
                <th scope="col"> <?=$v_number?> </th>
            <?php } ?>    
            <th scope="col">Total</th>
        </tr>
    </thead>
    <tbody>
    <?php  
        $i = 1;
        
        while($customer = mysqli_fetch_array($customers)) {
            $customer_name = $customer['username'];
            $total_due = 0;
            $vehicle_dues = [];
            
            // Fetch vehicles again for each customer
            $vehicles = mysqli_query($conn, "SELECT * FROM vehicles");
            while($vehicle = mysqli_fetch_array($vehicles)) {
                $dues = 0;
                $v_id = $vehicle['id'];
                
                // Calculate dues for Trip
                $details_t = "SELECT customer_id, SUM(credit) AS t_c_credit, SUM(debit) AS t_c_debit 
                              FROM customer_details 
                              WHERE type='Trip' AND status=1 AND customer_id IN 
                              (SELECT id FROM customers WHERE username='$customer_name' AND vehicle_id='$v_id')";

                $details_result_t = mysqli_query($conn, $details_t);
                $details_row_t = mysqli_fetch_array($details_result_t);

                $t_c_credit = $details_row_t["t_c_credit"] ?: 0;
                $t_c_debit = $details_row_t["t_c_debit"] ?: 0;

                // Calculate dues for non-Trip
                $details = "SELECT customer_id, SUM(credit) AS c_credit, SUM(debit) AS c_debit 
                            FROM customer_details 
                            WHERE type != 'Trip' AND status=1 AND customer_id IN 
                            (SELECT id FROM customers WHERE username='$customer_name' AND vehicle_id='$v_id')";

                $details_result = mysqli_query($conn, $details);
                $details_row = mysqli_fetch_array($details_result);

                $c_credit = $details_row["c_credit"] ?: 0;
                $c_debit = $details_row["c_debit"] ?: 0;

                $dues = $dues + $c_debit - $c_credit + $t_c_debit;

                // Store the dues in the array with vehicle id as the key
                $vehicle_dues[$v_id] = round($dues, 2);

                // Add the dues to the total due for this customer
                $total_due += $vehicle_dues[$v_id];

                // Add the dues to the total dues for each vehicle
                $vehicle_totals[$v_id] += $vehicle_dues[$v_id];
            }
    ?>
        <tr>
            <th scope="row"><?php echo $i++; ?></th>
            <td><a href="single-customer-details.php?c_name=<?=$customer['username']?>"> <?php echo $customer['username']; ?> </a></td>
            <?php
                $vehicles_d = mysqli_query($conn, "SELECT * FROM vehicles");
                while($vehicle_d = mysqli_fetch_array($vehicles_d)) {
                    $v_id = $vehicle_d['id'];
            ?>
                <th scope="col"> <?=$vehicle_dues[$v_id]?> </th>
            <?php } ?>  
            <td><?=$total_due?></td>
        </tr>
    <?php } ?>
    
    <tr>
        <th scope="row"></th>
        <td><strong>Total:</strong></td>
        <?php
        // Loop through each vehicle and display the total dues for that vehicle
        $grand_total = 0;
        $vehicles_d = mysqli_query($conn, "SELECT * FROM vehicles");
        while($vehicle_d = mysqli_fetch_array($vehicles_d)) {
            $v_id = $vehicle_d['id'];
            $grand_total += $vehicle_totals[$v_id];
        ?>
            <th scope="col"><strong><?=$vehicle_totals[$v_id]?></strong></th>
        <?php } ?>  
        <td><strong><?=$grand_total?></strong></td>
    </tr>
    </tbody>
</table>

          <!-- End Table with stripped rows -->

        </div>
      </div>
    </section>

        <!-- Confirm Delete Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-bs-dismiss="modal"  aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Please enter your password to confirm deletion:</p>
        <input type="password" id="deletePassword" class="form-control" placeholder="Your Password">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="recordId">

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include 'Master/footer.php';?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Scripts -->
  <?php include 'Master/scripts.php';?>
  <!-- Scripts -->

  <script>
  // Handle delete button click in the modal
  /*$('.deleteRecord').on('click', function () {
        var id = $(this).data("id");
        $('#recordId').prop("value", id);
    });*/
    
    $(document).on('click', '.deleteRecord', function () {
      var id = $(this).data("id");
    //alert("the customer ID:"+id);
      $('#recordId').val(id);
    });

  function confirmDelete() {
    var customerId = $('#recordId').val(); 
    //var customerId = $('#confirmDeleteModal').data('id');
    var password = $('#deletePassword').val();
    //alert('Customer ID: '+customerId);
    // You may want to validate the password here before proceeding
    // For simplicity, let's assume a password "1234" for demonstration

    if (password === "IttefaqMaster") {
        window.location.href = 'Controllers/VehicleController.php?vehicle_id=' + customerId + '&process=delete';
    } else {
        alert('Incorrect password. Deletion canceled.');
    }
}

var today = new Date();
$(document).ready(function() {
    $('#vehicles').DataTable({
    order: [],
    dom: 'Bfrtip',
    buttons: [{
        className: 'btn btn-dark',
        extend: 'pdfHtml5',
        text: 'Download details',
        title: 'All Vehicles Details',
        messageTop: 'Print Date:'+today,
        init: function (api, node, config) {
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
            $(this).addClass('btn-success');
        });
        },

        
        customize: function (doc) {
        // Add a header to the PDF document
        
        doc.styles.tableHeader.alignment="left";
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
     $("input[type='search']").wrap("<form>");
        $("input[type='search']").closest("form").attr("autocomplete","off");
});

  </script>

</body>

</html>