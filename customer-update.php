<?php include('Master/head.php');?>

<body>
  
  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Update customer</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Customer</li>
          <li class="breadcrumb-item active">Update customer</li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
            <a class="btn btn-dark" href="pages-blank-index.php"
            ><i class="bi bi-back"></i> Go to all customers
          </a>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
    <?php
      if (@$_GET['errorMessage']) {
          ?> 
              <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                <?php echo $_GET['errorMessage']; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              

              <?php
      }
    //   here going to fetch the record according to customer_id
    $conn = OpenCon();
    $customer_id = @$_GET['customer_id'];
     
    $customer_object = mysqli_query($conn,"SELECT * FROM customers WHERE id = $customer_id");
    $customer_details_object = mysqli_query($conn,"SELECT * FROM customer_details WHERE customer_id = $customer_id AND note = 'Old amount'");
    $customer_details_table = mysqli_fetch_array($customer_details_object);
  
    $customer_details_object_debit = $customer_details_table['debit'];
    $customer_details_object_id = $customer_details_table['id'];
    
    $customer = mysqli_fetch_array($customer_object);
    ?>
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">Update customer</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="Controllers/CustomerController.php" method="post" onsubmit="handleSubmit(event, this)">
              <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">

              <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="username" required placeholder="Customer Username" value="<?php echo $customer['username']; ?>">
                    <label for="name">Username</label>
                  </div>
                </div>

                <div class="col-md-4">
                    <div class="form-floating">
                      <input type="text" class="form-control" id="name" name="name" required placeholder="Customer Name" value="<?php echo $customer['name']; ?>">
                      <label for="name">Customer name</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                  <div class="form-floating">
                      <input type="hidden" name="customer_details_table_id"
                                  value="<?php echo $customer_details_object_id; ?>">
                    <input type="date" class="form-control" id="date" name="date" required placeholder="Date" value="<?php echo $customer['date']; ?>">
                    <label for="name">Date</label>
                  </div>
                </div>

                  <!--<div class="col-md-6">
                  <div class="form-floating">
                    <input type="number" readonly class="form-control"  value="<?php echo $customer['dues']; ?>">
                    <label for="dues">Current Dues</label>
                  </div>
                  </div>

                  <div class="col-md-6">
                  <div class="form-floating">
                    <input type="hidden" name="old_dues" value="<?php echo $customer_details_object_debit ?>">
                    <input type="hidden" name="customer_details_table_id"
                                  value="<?php echo $customer_details_object_id; ?>">

                    <input type="number" step="0.01" class="form-control" id="dues" name="new_dues" required placeholder="Dues" value="<?php echo $customer['dues']; ?>">
                    <label for="dues">New Dues</label>
                  </div>

                </div>-->

                
 
                    
                  <div class="text-center">
                      <input type="hidden" name="update" value="1">
                    <button type="submit" class="btn btn-dark">Update</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                  </div>
                  </form>
                  <!-- End floating Labels Form -->

            </div>
          </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include('Master/footer.php');?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <?php include('Master/scripts.php');?>
  <!-- Scripts -->
  
  <script> 
  
  function handleSubmit(event, form) {
    //event.preventDefault(); // Prevent the form from submitting immediately

        var button = form.querySelector("button[type='submit']");
        button.disabled = true;
        button.innerText = 'Loading...'; // Optional: Change button text to indicate loading

    // Simulate an async operation (e.g., an API call or some processing)
    
    }   
  
  </script>

</body>

</html>