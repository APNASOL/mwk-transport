<?php include('Master/head.php');?>

<body>
  
  <!-- ======= Header ======= -->
  <?php include('Master/header.php');?>
  <!-- ======= Sidebar ======= -->
  <?php include('Master/aside.php');?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1><?= __t('New vehicle')?></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html"><?= __t('Home')?></a></li>
          <li class="breadcrumb-item"><?= __t('Vehicle')?></li>
          <li class="breadcrumb-item active"><?= __t('New vehicle create')?></li>
        </ol>
      </nav>
      <!-- <div class="d-flex justify-content-end">
            <a class="btn btn-dark" href="pages-blank-index.php"
            ><i class="bi bi-back"></i> Go to all vehicles
          </a>
        </div> -->
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
              <h5 class="card-title"><?= __t('New Stock')?></h5>

              <!-- Floating Labels Form -->
              <form class="row g-3" action="Controllers/StockController.php" method="post">

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="title" name="title" required placeholder="Title of item">
                    <label for="owner_name"><?= __t('Title')?></label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="supplier" name="supplier" required placeholder="Supplier name">
                    <label for="number"><?= __t('Supplier')?></label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="date" class="form-control" id="date" name="date" required placeholder="Date">
                    <label for="name"><?= __t('Date')?></label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="note" name="note" required placeholder="Enter note for purchased">
                    <label for="balnce"><?= __t('Note')?></label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="number" class="form-control" id="quantity" name="quantity" required placeholder="enter quantity">
                    <label for="balnce"><?= __t('Quantity')?></label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="bill" name="bill" required placeholder="Total Bill">
                    <label for="balnce"><?= __t('Total Bill')?></label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating">
                    <input type="text" class="form-control" id="unit_price" name="unit_price" required placeholder="unit_price" readonly>
                    <label for="balnce"><?= __t('Unit Price')?></label>
                  </div>
                </div>

                  
                <div class="text-center">
                  <button type="submit" name="create_stock" class="btn btn-dark"><?= __t('Add')?></button>
                  <button type="reset" class="btn btn-secondary"><?= __t('Reset')?></button>
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
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const billInput = document.getElementById('bill');
    const unitPriceInput = document.getElementById('unit_price');

    function calculateUnitPrice() {
        const quantity = parseFloat(quantityInput.value);
        const bill = parseFloat(billInput.value);

        if (!isNaN(quantity) && quantity > 0 && !isNaN(bill)) {
            unitPriceInput.value = (bill / quantity).toFixed(2);
        } else {
            unitPriceInput.value = '';
        }
    }

    quantityInput.addEventListener('input', calculateUnitPrice);
    billInput.addEventListener('input', calculateUnitPrice);
});
</script>

</body>

</html>