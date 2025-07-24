<?php include 'Master/head.php';?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php';?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php';?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>Vehicles</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Vehicles</li>
          <li class="breadcrumb-item active">Index</li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
        <a class="btn btn-dark" href="vehicle-create.php"><i class="bi bi-plus-lg"></i> Add new vehicle
        </a>
      </div>
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
          <h5 class="card-title">Vehicles</h5>

          <?php 
            $conn = OpenCon();
            $vehicles = mysqli_query($conn, "SELECT * FROM vehicles");
          ?>
          <!-- Table with stripped rows -->
          <table class="table table-striped" id="vehicles">
            <thead>
              
              <tr>
                <th scope="col">#</th>
                <th scope="col">Owner</th>
                <th scope="col">Vehicle number</th>
                <th scope="col">Balance</th> 
                <th scope="col">Cashbook</th> 
                <th scope="col">Action</th> 
              </tr>

            </thead>
            <tbody>
            <?php  
                $i = 1;
                while($vehicle = mysqli_fetch_array($vehicles))
                {
                  $c_id = $vehicle['id'];
                  $vehicle_cash_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id='$c_id' ORDER BY date");

         $total_balance = 0;
         $cash_in_show = 0;
         $cash_out_show = 0;

            while ($record = mysqli_fetch_array($vehicle_cash_details)) {

                 if ($record['type'] == 'Trip') {
                    
                        if ($record['note'] == 'Not received') {
                            $cash_in_show = $cash_in_show + 0;
                             $cash_out_show = $cash_out_show + $record['debit'];
                            $total_balance = $total_balance - $record['debit'];
                        } else {
                             $cash_in_show = $cash_in_show + $record['credit'];
                             $cash_out_show = $cash_out_show + $record['debit'];
                             $total_balance = $total_balance + $record['credit'] - $record['debit'];
                         }
                 } else {
                    $cash_in_show = $cash_in_show + $record['credit'];
                    $cash_out_show = $cash_out_show + $record['debit'];
                    $total_balance = $total_balance + $record['credit'] - $record['debit'];
                                    }
                }

                $QueryForCashbook = mysqli_query($conn, "SELECT SUM(cash_out) AS cash_out,SUM(cash_in) AS cash_in FROM  cashbook WHERE vehicle_id='$c_id'");

                $cashbook_cash = mysqli_fetch_array($QueryForCashbook);
                $cash_in_shows = $cashbook_cash['cash_in'];
                $cash_out_shows = $cashbook_cash['cash_out'];

                $total_balances = $cash_in_shows - $cash_out_shows;

              ?>
                <tr>
                  <th scope="row"><?php echo $i++;?></th>
                  <td><?php echo $vehicle['owner_name'];?></td>
                  
                  <td> 
                  <a href="Controllers/VehicleController.php?vehicle_id=<?php echo $vehicle['id']; ?>&process=details" type="button"
                         
                          title="Details">
                          <?php echo $vehicle['number']; ?>
                      </a>
                  </td>
                  
                  <td><?php echo $total_balance;?></td> 
                  <td><?php echo $total_balances;?></td> 
                  <td>
                    
                    <a href="Controllers/VehicleController.php?vehicle_id=<?php echo $vehicle['id'];?>&process=edit" type="button"
                        class="btn btn-sm fs-6"
                        title="Edit"><i class="bi bi-pencil-square"></i></a>
                        <a href="#" 
                          data-bs-toggle="modal" 
                          data-bs-target="#confirmDeleteModal"
                          data-id="<?php echo $vehicle['id']; ?>"
                          class="btn btn-sm fs-6 deleteRecord"
                          title="Delete">
                          <i class="bi bi-trash"></i>
                        </a>
                    <!--<a href="Controllers/VehicleController.php?vehicle_id=<?php echo $vehicle['id'];?>&process=delete" type="button"-->
                    <!--    class="btn btn-sm fs-6"-->
                    <!--    title="Delete"><i class="bi bi-trash"></i></a>-->
                  </td>
                </tr>
              <?php }?>
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

        exportOptions: {
        columns: [0, 1, 2, 3, 4] // export only columns
        },
        customize: function (doc) {
        // Add a header to the PDF document
        
        doc.styles.tableHeader.alignment="left";
        doc.content[2].table.widths = ["10%","30%","20%","20%","20%"];
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