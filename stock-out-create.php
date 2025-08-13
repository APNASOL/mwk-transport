<?php
// stock-out-create.php
include('Master/head.php');
$conn = OpenCon();
// Fetch active stock items for dropdown
$items = [];
if ($stmt = mysqli_prepare($conn, "SELECT id, title, quantity, used_quantity, unit_price FROM stock WHERE status = 1 ORDER BY title ASC")) {
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_assoc($res)) {
    $items[] = $row;
  }
  mysqli_stmt_close($stmt);
}

// Fetch vehicles for dropdown (optional – use your own source)
$vehicles = [];
if ($stmt = mysqli_prepare($conn, "SELECT id, number FROM vehicles WHERE status = 1 ORDER BY number ASC")) {
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_assoc($res)) {
    $vehicles[] = $row;
  }
  mysqli_stmt_close($stmt);
}
?>
<body>
<?php include('Master/header.php'); ?>
<?php include('Master/aside.php'); ?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Stock Out</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item">Stock</li>
        <li class="breadcrumb-item active"> Stock Out</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <?php if (@$_GET['errorMessage']) { ?>
      <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
        <?php echo $_GET['errorMessage']; ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
      </div>
    <?php } ?>

    <?php if (@$_GET['successMessage']) { ?>
      <div class="alert alert-success border-0 alert-dismissible fade show" role="alert">
        <?php echo $_GET['successMessage']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php } ?>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Create Stock Out</h5>

        <form class="row g-3" action="Controllers/StockController.php" method="post" id="stockOutForm">
          <!-- Stock Item -->
          <div class="col-md-6">
            <div class="form-floating">
              <select class="form-select" id="s_id" name="s_id" required>
                <option value="">Select Item</option>
                <?php foreach ($items as $it): 
                  $available = (float)$it['quantity'] - (float)$it['used_quantity'];
                ?>
                  <option 
                    value="<?php echo (int)$it['id']; ?>"
                    data-unit-price="<?php echo htmlspecialchars($it['unit_price']); ?>"
                    data-available="<?php echo htmlspecialchars($available); ?>">
                    <?php echo htmlspecialchars($it['title']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="s_id">Item</label>
            </div>
            <div class="form-text mt-1">
              <span id="availableInfo" class="text-muted"></span>
            </div>
          </div>

          <!-- Vehicle -->
          <div class="col-md-6">
            <div class="form-floating">
              <select class="form-select" id="vehicle_id" name="vehicle_id" required>
                <option value="">Select Vehicle</option>
                <?php foreach ($vehicles as $v): ?>
                  <option value="<?php echo (int)$v['id']; ?>">
                    <?php echo htmlspecialchars($v['number']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="vehicle_id">Vehicle</label>
            </div>
          </div>

          <!-- Quantity -->
          <div class="col-md-4">
            <div class="form-floating">
              <input type="number" step="0.001" min="0.001" class="form-control" id="quantity" name="quantity" required placeholder="Quantity">
              <label for="quantity">Quantity</label>
            </div>
          </div>

          <!-- Unit Price (readonly) -->
          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" class="form-control" id="unit_price" name="unit_price" placeholder="Unit Price" readonly>
              <label for="unit_price">Unit Price</label>
            </div>
          </div>

          <!-- Total (readonly, auto) -->
          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" class="form-control" id="total" name="total" placeholder="Total" readonly>
              <label for="total">Total</label>
            </div>
          </div>

          <!-- Date -->
          <div class="col-md-6">
            <div class="form-floating">
              <input type="date" class="form-control" id="date" name="date" required placeholder="Date" value="<?php echo date('Y-m-d'); ?>">
              <label for="date">Date</label>
            </div>
          </div>

          <!-- Note -->
          <div class="col-md-6">
            <div class="form-floating">
              <input type="text" class="form-control" id="note" name="note" placeholder="Note about usage">
              <label for="note">Note</label>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" name="stock_out_create" class="btn btn-dark" id="submitBtn">Save</button>
            <a href="stock-out-index.php" class="btn btn-secondary">Cancel</a>
          </div>
        </form>

      </div>
    </div>
  </section>
</main>

<?php include('Master/footer.php'); ?>
<?php include('Master/scripts.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const itemSelect   = document.getElementById('s_id');
  const qtyInput     = document.getElementById('quantity');
  const unitInput    = document.getElementById('unit_price');
  const totalInput   = document.getElementById('total');
  const submitBtn    = document.getElementById('submitBtn');
  const availableInfo= document.getElementById('availableInfo');

  let unitPrice   = 0;
  let available   = 0;

  function format(n) {
    if (isNaN(n)) return '';
    return Number(n).toFixed(2);
  }

  function updateTotals() {
    const qty = parseFloat(qtyInput.value);
    if (!isNaN(qty) && qty > 0 && unitPrice > 0) {
      totalInput.value = format(qty * unitPrice);
    } else {
      totalInput.value = '';
    }

    // Guard: quantity must not exceed available
    if (!isNaN(qty) && qty > available && available > 0) {
      qtyInput.classList.add('is-invalid');
      submitBtn.disabled = true;
    } else {
      qtyInput.classList.remove('is-invalid');
      submitBtn.disabled = false;
    }
  }

  itemSelect.addEventListener('change', function () {
    const opt = itemSelect.options[itemSelect.selectedIndex];
    unitPrice = parseFloat(opt.getAttribute('data-unit-price')) || 0;
    available = parseFloat(opt.getAttribute('data-available')) || 0;

    unitInput.value = unitPrice ? format(unitPrice) : '';
    availableInfo.textContent = available ? ('Available: ' + available) : '';
    updateTotals();
  });

  qtyInput.addEventListener('input', updateTotals);
});
</script>
</body>
</html>
