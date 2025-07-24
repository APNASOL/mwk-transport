<?php include('Master/head.php');?>

<body>
  
  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>New customer</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Customer</li>
          <li class="breadcrumb-item active">New customer create</li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
            <a class="btn btn-dark" href="customer-index.php"
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
    ?>
    <div class="card">
            <div class="card-body">
              <h5 class="card-title">New customer</h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="Controllers/CustomerController.php" method="post" onsubmit="handleSubmit(event, this)">

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="username" required placeholder="Customer Username">
                    <label for="name">Username</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Customer Name">
                    <label for="name">Name</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="contact" name="contact" required placeholder="Contact contact">
                    <label for="contact">Contact number</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="dues" name="dues" required placeholder="Dues">
                    <label for="dues">Dues</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="date" name="date" required placeholder="Date">
                    <label for="name">Date</label>
                  </div>
                </div>

                                   
                <div class="text-center">
                    <input type="hidden" name="create_customer" value="1">
                  <button type="submit" class="btn btn-dark">Submit</button>
                  <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
              </form><!-- End floating Labels Form -->

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