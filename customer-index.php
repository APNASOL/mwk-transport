<?php include 'Master/head.php';
session_start();
$v_name = $_SESSION['current_vehicle_number'];
?>

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
      <div class="d-flex justify-content-end">
        <a class="btn btn-dark" href="customer-create.php"><i class="bi bi-plus-lg"></i> Add new customer
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
          <h5 class="card-title">Customers</h5>
          <div class="row text-center ">
        <?php
$conn = OpenCon();
$customers_totals = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");
        //$row1 = mysqli_fetch_array($customers_totals);
        $total = 0;
        $dues = 0;
        while ($selectCustomerRow = mysqli_fetch_array($customers_totals)) {
          $customer_id = $selectCustomerRow["id"];
          
          $t_details = "SELECT SUM(credit) AS t_c_credit,SUM(debit) AS t_c_debit FROM customer_details WHERE type='Trip' AND status=1 AND customer_id='$customer_id' ORDER BY date";
          $t_details_result = mysqli_query($conn, $t_details);
          $t_details_row = mysqli_fetch_array($t_details_result);
                                                      
          $t_c_credit = $t_details_row["t_c_credit"];
            if ($t_c_credit == "") {
                  $t_c_credit = 0.0;
            }
          $t_c_debit = $t_details_row["t_c_debit"];
            if ($t_c_debit == "") {
                  $t_c_debit = 0.0;
            }
            
            $details = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM customer_details WHERE type !='Trip' AND status=1 AND customer_id='$customer_id' ORDER BY date";
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
                                                      
          $dues = $dues + $c_debit - $c_credit+ $t_c_debit;
          $total++;
          
      }

      
?>
            <div class="col">
                <div class="card bg-white text-warning mb-3" style="">

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total</b></h5><hr>
                <h3 class="card-title"><?php echo $total; ?></h3>

                </div>
                </div>
            </div>

            <div class="col">
                <div class="card bg-white text-warning mb-3" style="">

                <div class="card-body p-3">
                <h5><b class="card-title text-dark text-left">Total Dues</b></h5><hr>
                <h3 class="card-title"><b><?php echo $dues; ?></b></h3>
                </div>
                </div>
            </div>

        </div>

          <?php
$conn = OpenCon();
$customers = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");
?>
          <!-- Table with stripped rows -->
          <table class="table table-striped" id="customersTable">
            <thead>

              <tr>
                <th scope="col">#</th>
                <th scope="col">Customer name</th>
                <th scope="col">Contact #</th>
                <th scope="col">Dues</th>
                <th scope="col">Last Trip</th>
                <th scope="col">Action</th>
              </tr>

            </thead>
            <tbody>
            <?php
$i = 1;

while ($customer = mysqli_fetch_array($customers)) {
  $due = 0;
 $customer_id = $customer['id'];
 $t_details = "SELECT SUM(credit) AS t_c_credit,SUM(debit) AS t_c_debit FROM customer_details WHERE type='Trip' AND status=1 AND customer_id='$customer_id' ORDER BY date";
 $t_details_result = mysqli_query($conn, $t_details);
 $t_details_row = mysqli_fetch_array($t_details_result);
                                             
 $t_c_credit = $t_details_row["t_c_credit"];
   if ($t_c_credit == "") {
         $t_c_credit = 0.0;
   }
 $t_c_debit = $t_details_row["t_c_debit"];
   if ($t_c_debit == "") {
         $t_c_debit = 0.0;
   }
   
   $details = "SELECT SUM(credit) AS c_credit,SUM(debit) AS c_debit FROM customer_details WHERE type !='Trip' AND status=1 AND customer_id='$customer_id' ORDER BY date";
 $details_result = mysqli_query($conn, $details);
 $details_row = mysqli_fetch_array($details_result);
                                             
 $c_credit = $details_row["c_credit"];
   if ($t_c_credit == "") {
         $t_c_credit = 0.0;
   }
 $c_debit = $details_row["c_debit"];
   if ($c_debit == "") {
         $c_debit = 0.0;
   }

   $c_details = "SELECT * FROM customer_details WHERE type='Trip' AND status=1 AND customer_id='$customer_id' ORDER BY date DESC LIMIT 1";
 $c_details_result = mysqli_query($conn, $c_details);
 $c_details_row = mysqli_fetch_array($c_details_result);
 if($c_details_row){
  $last_date=$c_details_row["date"];
 }else{
  $last_date=$customer["date"];
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


$currentdate = explode("-", $last_date);

$dat = $currentdate[2];
$month = $currentdate[1];
$year = $currentdate[0];
$month_name = $months[$month - 1];

$res = $dat . ' ' . $month_name . ' , ' . $year;
 
                                             
 $due = $due + $c_debit - $c_credit+ $t_c_debit;
 if($due != 0){

    ?>
                <tr>
                  <input type="hidden" id="v_name" value='<?php echo $v_name?>'>
                  <th scope="row"><?php echo $i++; ?></th>
                  <td>
                    <a href="Controllers/CustomerController.php?customer_id=<?php echo $customer['id']; ?>&process=details" type="button"
                         
                          title="Details">
                          <?php echo $customer['name']; ?>
                      </a>
                    </td>
                  <td><?php echo $customer['contact']; ?></td>
                  <td>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#customerDifferance" data-id="<?php echo $customer['id']; ?>" data-name="<?php echo $customer['name']; ?>" data-amount="<?php echo $due; ?>" class="setCustomer" title="setCustomer">
                          <?php echo $due; ?>
                        </a>
                  </td>
                
                  <td><?php echo $res; ?></td>
                  <td>

                    <a href="Controllers/CustomerController.php?customer_id=<?php echo $customer['id']; ?>&process=edit" type="button"
                        class="btn btn-sm fs-6"
                        title="Edit"><i class="bi bi-pencil-square"></i></a>

                        <a href="#" 
                          data-bs-toggle="modal" 
                          data-bs-target="#confirmDeleteModal"
                          data-id="<?php echo $customer['id']; ?>"
                          class="btn btn-sm fs-6 deleteRecord"
                          title="Delete">
                          <i class="bi bi-trash"></i>
                        </a>

                    <!-- <a href="Controllers/CustomerController.php?customer_id=<?php echo $customer['id']; ?>&process=delete" type="button"
                        class="btn btn-sm fs-6"
                        title="Delete"><i class="bi bi-trash"></i></a> -->

                  </td>
                </tr>
              <?php } }?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>
    </section>

    <!-- Modal Give to Vehicle -->
    <div class="modal fade" id="customerDifferance" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Set Customer Differance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Floating Labels Form -->
                        <form class="row g-3" action="Controllers/CustomerController.php" method="post" onsubmit="handleSubmit(event, this)">

                            <div class="col-md-12">
                                <div class="form-floating">
                                <input type="hidden" id="customer_id" name="customer_id">
                                    <input type="text" readonly class="form-control" id="customer">
                                    <label for="vehicle">Customer</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="amount" name="amount"
                                        required placeholder="Amount">
                                    <label for="amount">Amount</label>
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
                                    <input type="hidden" name="differance_transaction" value="1">
                                    <input type="submit"
                            class="btn btn-dark" value="Submit">
                                    <!--<input type="submit" name="customer_differance_transaction"
                                        class="btn btn-dark  btn-lg" value="Submit">-->

                                </div>

                            </div>

                        </form>
                        <!-- Floating Labels Form -->
                    </div>
                </div>
            </div>
          </div>
        </div>

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
  
  var today = new Date();
  
  var vehicle =document.getElementById("v_name").value; 
    
  $(document).on('click', '.deleteRecord', function () {
      var id = $(this).data("id");
    //alert("the customer ID:"+id);
      $('#recordId').val(id);
    });

  $(document).on('click', '.setCustomer', function () {
      var id = $(this).data("id");
      var name = $(this).data("name");
      var amount = $(this).data("amount");
      if(amount == 0){
          alert("This customer due amount is Zero");
      }
      //alert("the customer ID:"+id);
      $('#customer_id').val(id);
      $('#customer').val(name+' ( '+amount+' )');
    });

  function handleSubmit(event, form) {
        //event.preventDefault(); // Prevent the form from submitting immediately

        var button = form.querySelector("input[type='submit']");
        button.disabled = true;
        button.innerText = 'Loading...'; // Optional: Change button text to indicate loading

    // Simulate an async operation (e.g., an API call or some processing)
    
    } 

  function confirmDelete() {
    var customerId = $('#recordId').val(); 
    
    //var customerId = $('#confirmDeleteModal').data('id');
    var password = $('#deletePassword').val();
    //alert('Customer ID: '+customerId);
    // You may want to validate the password here before proceeding
    // For simplicity, let's assume a password "1234" for demonstration

    if (password === "Ittefaq") {
        window.location.href = 'Controllers/CustomerController.php?customer_id=' + customerId + '&process=delete';
    } else {
        alert('Incorrect password. Deletion canceled.');
    }
}


   
    $(document).ready(function() {
        
        $('#customersTable').DataTable({
        order: [],
        dom: 'Bfrtip',
        buttons: [{
            className: 'btn btn-dark',
            extend: 'pdfHtml5',
            text: 'Download details',
            title: 'All Customers \n Vehicle:  '+vehicle,
            messageTop: 'Print Date:'+today,
            init: function (api, node, config) {
                
            $(node).removeClass('btn-primary');
            $(node).on('click', function () {
                $(this).addClass('btn-success');
            });
            },
    
            exportOptions: {
            columns: [0, 1, 3,4] // export only columns
            },
            customize: function (doc) {
            // Add a header to the PDF document
            
            doc.styles.tableHeader.alignment="left";
            doc.content[2].table.widths = ["10%","40%","30%","20%"];
    
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