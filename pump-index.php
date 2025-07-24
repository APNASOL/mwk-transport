<?php include 'Master/head.php';?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php';?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php';?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>Pumps</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Pumps</li>
          <li class="breadcrumb-item active">Index</li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
        <a class="btn btn-dark" href="pump-create.php"><i class="bi bi-plus-lg"></i> Add new pump
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
          <h5 class="card-title">Pumps</h5>
          
          <div class="row text-center ">
        <?php
$conn = OpenCon();
$customers_totals = mysqli_query($conn, "SELECT COUNT(id) AS total_customer,SUM(balance) AS value_sum FROM pumps where vehicle_id = '$current_vehicle_id'");
        $row1 = mysqli_fetch_array($customers_totals);
        $t_due = $row1["value_sum"];
        $total_customer = $row1["total_customer"];
        
        $total_due = 0;
        $pumps = mysqli_query($conn, "SELECT * FROM pumps where vehicle_id = '$current_vehicle_id'");
        
        
  while ($pump = mysqli_fetch_array($pumps)) {
      $pump_id = $pump["id"];
     
      $a_details = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM pumps_details WHERE status=1 AND pump_id = '$pump_id' ORDER BY date";
      $a_details_result = mysqli_query($conn, $a_details);
      $a_details_row = mysqli_fetch_array($a_details_result);
                                                 
      $a_c_credit = $a_details_row["c_credit"];
       if ($a_c_credit == "") {
             $a_c_credit = 0.0;
       }
      $a_c_debit = $a_details_row["c_debit"];
       if ($a_c_debit == "") {
             $a_c_debit = 0.0;
       }
                                                 
      $total_due = $total_due + $a_c_debit - $a_c_credit;
  }
        
        
?>
            <div class="col">
                <div class="card bg-white text-warning mb-3" style="">

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total</b></h5><hr>
                <h3 class="card-title"><?php echo $total_customer; ?></h3>

                </div>
                </div>
            </div>

            <div class="col">
                <div class="card bg-white text-warning mb-3" style="">

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total Dues</b></h5><hr>
                <h3 class="card-title"><b><?php echo $total_due; ?></b></h3>
                </div>
                </div>
            </div>

        </div>

          <?php
$pumps = mysqli_query($conn, "SELECT * FROM pumps where vehicle_id = '$current_vehicle_id'");
?>
          <!-- Table with stripped rows -->
          <table class="table table-striped" id="pumpsTable">
            <thead>

              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Dues</th>
                <th scope="col">Address</th>
                <th scope="col">Action</th>
              </tr>

            </thead>
            <tbody>
            <?php
$i = 1;

while ($pump = mysqli_fetch_array($pumps)) {
  $pump_id = $pump["id"];
  $due = 0;
  $details = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM pumps_details WHERE status=1 AND pump_id = '$pump_id' ORDER BY date";
 $details_result = mysqli_query($conn, $details);
 $details_row = mysqli_fetch_array($details_result);
                                             
 $c_credit = $details_row["c_credit"];
   if ($c_credit == "") {
         $c_credit = 0.0;
   }
 $c_debit = $details_row["c_debit"];
   if ($c_debit == "") {
         $c_debit = 0.0;
   }
                                             
 $due = $due + $c_debit - $c_credit;

    ?>
                <tr>
                  <th scope="row"><?php echo $i++; ?></th>
                  <td>
                    <a href="Controllers/PumpController.php?pump_id=<?php echo $pump['id']; ?>&process=details"  
                        class="btn btn-sm fs-6"
                        title="Details">
                  <?php echo $pump['name']; ?></a>
                </td>
                  <td><?php echo $due; ?></td>
                  <td><?php echo $pump['address']; ?></td>
                  <td>

                    <a href="Controllers/PumpController.php?pump_id=<?php echo $pump['id']; ?>&process=edit" type="button"
                        class="btn btn-sm fs-6"
                        title="Edit"><i class="bi bi-pencil-square"></i></a>
                        <a href="#" 
                          data-bs-toggle="modal" 
                          data-bs-target="#confirmDeleteModal"
                          data-id="<?php echo $pump['id']; ?>"
                          class="btn btn-sm fs-6 deleteRecord"
                          title="Delete">
                          <i class="bi bi-trash"></i>
                        </a>
                    
                  </td>
                </tr>
              <?php }?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>
    </section>

  </main><!-- End #main -->

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

  <!-- ======= Footer ======= -->
  <?php include 'Master/footer.php';?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Scripts -->
  <?php include 'Master/scripts.php';?>
  <!-- Scripts -->

  <script>
  var today = new Date();
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

    if (password === "Ittefaq") {
        window.location.href = 'Controllers/PumpController.php?pump_id=' + customerId + '&process=delete';
    } else {
        alert('Incorrect password. Deletion canceled.');
    }
}

$(document).ready(function() {
        
        $('#pumpsTable').DataTable({
        order: [],
        dom: 'Bfrtip',
        buttons: [{
            className: 'btn btn-dark',
            extend: 'pdfHtml5',
            text: 'Download details',
            title: 'All Pumps',
            messageTop: 'Print Date:'+today,
            init: function (api, node, config) {
                
            $(node).removeClass('btn-primary');
            $(node).on('click', function () {
                $(this).addClass('btn-success');
            });
            },
    
            exportOptions: {
            columns: [0, 1, 2] // export only columns
            },
            customize: function (doc) {
            // Add a header to the PDF document
            
            doc.styles.tableHeader.alignment="left";
            doc.content[2].table.widths = ["10%","60%","30%"];
    
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