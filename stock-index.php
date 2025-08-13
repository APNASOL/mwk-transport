<?php include 'Master/head.php'; ?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php'; ?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>Stock</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Stock</li>
          <li class="breadcrumb-item active">Index</li>
        </ol>
      </nav>

      <div class="d-flex justify-content-end align-items-center gap-2 flex-nowrap">
        <a class="btn btn-success" href="stock-create.php">
          <i class="bi bi-plus-lg"></i> Add new Stock
        </a>
        <a class="btn btn-danger" href="stock-out-create.php">
          <i class="bi bi-dash-lg"></i> Stock Out
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
          <h5 class="card-title">Stock</h5>

          <?php
          $conn = OpenCon();
          $stocks = mysqli_query($conn, "SELECT * FROM stock");
          ?>
          <!-- Table with stripped rows -->
          <table class="table table-striped" id="vehicles">
            <thead>

              <tr>
                <th scope="col">#</th>

                <th scope="col">Date</th>
                <th scope="col">Title</th>
                <th scope="col">Supplier</th>
                <th scope="col">Note</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th scope="col">Total</th>
                <th scope="col">Remaining</th>
              </tr>

            </thead>
            <tbody>
              <?php
              $i = 1;
              while ($stock = mysqli_fetch_array($stocks)) {

                $id = $stock['id'];
                $title = $stock['title'];
                $supplier = $stock['supplier'];
                $note = $stock['note'];
                $quantity = $stock['quantity'];
                $unit_price = $stock['unit_price'];
                $total = $stock['total_bill'];
                $date = $stock['date'];
                $used_quantity = $stock['used_quantity'];

                $remained = $quantity - $used_quantity;



                ?>
                <tr>
                  <th scope="row"><?php echo $i++; ?></th>

                  <td scope="row"><a class="btn btn-sm btn-primary"
                      href="stock-edit.php?id=<?php echo $stock['id']; ?>"><?php echo $date; ?></a></td>
                  <td>

                    <a href="stock-out-index.php"
                      type="button" title="Details">
                      <?php echo $title; ?>
                    </a>
                  </td>
                  <td scope="row"><?php echo $supplier; ?></td>
                  <td scope="row"><?php echo $note; ?></td>
                  <td scope="row"><?php echo $quantity; ?></td>
                  <td scope="row"><?php echo $unit_price; ?></td>
                  <td scope="row"><?php echo $total; ?></td>
                  <td scope="row"><?php echo $remained; ?></td>

                </tr>
              <?php } ?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>
    </section>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
      aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
  <?php include 'Master/footer.php'; ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Scripts -->
  <?php include 'Master/scripts.php'; ?>
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
    $(document).ready(function () {
      $('#vehicles').DataTable({
        order: [],
        dom: 'Bfrtip',
        buttons: [{
          className: 'btn btn-dark',
          extend: 'pdfHtml5',
          text: 'Download details',
          title: 'All Stock Details',
          messageTop: 'Print Date:' + today,
          init: function (api, node, config) {
            $(node).removeClass('btn-primary');
            $(node).on('click', function () {
              $(this).addClass('btn-success');
            });
          },

          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // export only columns
          },
          customize: function (doc) {
            // Add a header to the PDF document

            doc.styles.tableHeader.alignment = "left";
            doc.content[2].table.widths = ["10%", "20%", "10%", "10%", "10%", "10%", "10%", "10%", "10%"];
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
      $("input[type='search']").closest("form").attr("autocomplete", "off");
    });

  </script>

</body>

</html>