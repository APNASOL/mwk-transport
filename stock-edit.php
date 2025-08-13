<?php
// stock-edit.php
include('Master/head.php');
$conn = OpenCon();
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('location:stock-index.php?errorMessage=Invalid stock id');
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT id, title, supplier, note, quantity, used_quantity, unit_price, total_bill, date, status FROM stock WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$stock = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$stock) {
    header('location:stock-index.php?errorMessage=Stock not found');
    exit;
}
?>

<body>
<?php include('Master/header.php');?>
<?php include('Master/aside.php');?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Stock</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item">Stock</li>
        <li class="breadcrumb-item active">Edit</li>
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
        <h5 class="card-title">Update Stock</h5>

        <form class="row g-3" action="Controllers/StockController.php" method="post">
          <input type="hidden" name="id" value="<?php echo (int)$stock['id']; ?>">

          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" class="form-control" id="title" name="title" required
                     value="<?php echo htmlspecialchars($stock['title']); ?>" placeholder="Title of item">
              <label for="title">Title</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" class="form-control" id="supplier" name="supplier" required
                     value="<?php echo htmlspecialchars($stock['supplier']); ?>" placeholder="Supplier name">
              <label for="supplier">Supplier</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <input type="date" class="form-control" id="date" name="date" required
                     value="<?php echo htmlspecialchars($stock['date']); ?>" placeholder="Date">
              <label for="date">Date</label>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-floating">
              <input type="text" class="form-control" id="note" name="note" required
                     value="<?php echo htmlspecialchars($stock['note']); ?>" placeholder="Enter note">
              <label for="note">Note</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <input type="number" step="0.001" class="form-control" id="quantity" name="quantity" required
                     value="<?php echo htmlspecialchars($stock['quantity']); ?>" placeholder="Quantity">
              <label for="quantity">Quantity</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" class="form-control" id="bill" name="bill" required
                     value="<?php echo htmlspecialchars($stock['total_bill']); ?>" placeholder="Total Bill">
              <label for="bill">Total Bill</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <input type="text" class="form-control" id="unit_price" name="unit_price" required readonly
                     value="<?php echo htmlspecialchars($stock['unit_price']); ?>" placeholder="Unit Price">
              <label for="unit_price">Unit Price</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <input type="number" class="form-control" id="used_quantity" name="used_quantity" readonly
                     value="<?php echo htmlspecialchars($stock['used_quantity']); ?>" placeholder="Used Quantity">
              <label for="used_quantity">Used Qty (read-only)</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-floating">
              <select class="form-select" id="status" name="status">
                <option value="1" <?php echo $stock['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo $stock['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
              </select>
              <label for="status">Status</label>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" name="update_stock" class="btn btn-dark">Update</button>
            <a href="stock-index.php" class="btn btn-secondary">Cancel</a>
          </div>
        </form>

      </div>
    </div>
  </section>
</main>

<?php include('Master/footer.php'); ?>
<?php include('Master/scripts.php'); ?>

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
