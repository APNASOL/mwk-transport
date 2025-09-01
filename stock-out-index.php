<?php
// stock-out-index.php
include('Master/head.php');
$conn = OpenCon();
function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function nf2($n){ return number_format((float)$n, 2, '.', ','); }

// ---------- Fetch items for filter ----------
$items = [];
if ($stmt = mysqli_prepare($conn, "SELECT id, title FROM stock ORDER BY title ASC")) {
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_assoc($res)) $items[] = $row;
  mysqli_stmt_close($stmt);
}

// ---------- Read filters ----------
$s_id      = isset($_GET['s_id']) ? intval($_GET['s_id']) : 0;
$date_from = trim($_GET['date_from'] ?? '');
$date_to   = trim($_GET['date_to'] ?? '');
$valid_from = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from) ? $date_from : '';
$valid_to   = preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to) ? $date_to : '';

// ---------- Pagination ----------
$perPage = intval($_GET['per_page'] ?? 25);
if (!in_array($perPage, [10,25,50,100], true)) $perPage = 25;
$page    = max(1, intval($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

// Build WHERE + params once to reuse
$where  = " WHERE 1=1";
$types  = "";
$params = [];

if ($s_id > 0) { $where .= " AND so.s_id = ?"; $types .= "i"; $params[] = $s_id; }
if ($valid_from && $valid_to) {
  $where .= " AND so.date BETWEEN ? AND ?";
  $types .= "ss"; $params[] = $valid_from; $params[] = $valid_to;
} elseif ($valid_from) {
  $where .= " AND so.date >= ?"; $types .= "s"; $params[] = $valid_from;
} elseif ($valid_to) {
  $where .= " AND so.date <= ?"; $types .= "s"; $params[] = $valid_to;
}

// ---------- Total count ----------
$countSql = "SELECT COUNT(*) AS cnt FROM stock_out so" . $where;
$totalRows = 0;
if ($stmt = mysqli_prepare($conn, $countSql)) {
  if ($types !== "") mysqli_stmt_bind_param($stmt, $types, ...$params);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $totalRows = (int)mysqli_fetch_assoc($res)['cnt'];
  mysqli_stmt_close($stmt);
}

$totalPages = max(1, (int)ceil($totalRows / $perPage));

// ---------- Data query ----------
$listSql =
  "SELECT so.id, so.date, s.title AS item_title, v.number AS vehicle_number,
          so.quantity, so.unit_price, so.total, so.note, so.status
   FROM stock_out AS so
   LEFT JOIN stock AS s ON s.id = so.s_id
   LEFT JOIN vehicles AS v ON v.id = so.vehicle_id" .
   $where .
  " ORDER BY so.date DESC, so.id DESC
    LIMIT $perPage OFFSET $offset"; // safe: ints from server

$data = [];
if ($stmt = mysqli_prepare($conn, $listSql)) {
  if ($types !== "") mysqli_stmt_bind_param($stmt, $types, ...$params);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_assoc($res)) $data[] = $row;
  mysqli_stmt_close($stmt);
}

// ---------- Page totals (current page only) ----------
$pageQty = 0; $pageAmt = 0;
foreach ($data as $r) { $pageQty += (float)$r['quantity']; $pageAmt += (float)$r['total']; }

// Helper to keep query string on links
function keep_qs($extra = []) {
  $keep = [
    's_id' => $_GET['s_id'] ?? null,
    'date_from' => $_GET['date_from'] ?? null,
    'date_to' => $_GET['date_to'] ?? null,
    'per_page' => $_GET['per_page'] ?? null,
  ];
  return http_build_query(array_filter(array_merge($keep, $extra), fn($v) => $v !== null && $v !== ''));
}
?>
<body>
<?php include('Master/header.php'); ?>
<?php include('Master/aside.php'); ?>

<main id="main" class="main">
  <div class="pagetitle">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h1><?= __t('Stock Out')?></h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><?= __t('Home')?></a></li>
            <li class="breadcrumb-item"><?= __t('Stock')?></li>
            <li class="breadcrumb-item active"><?= __t('Stock Out List')?></li>
          </ol>
        </nav>
      </div>
      <div class="d-flex justify-content-end align-items-center gap-2 flex-nowrap">
        <!-- <a class="btn btn-outline-primary rounded-pill shadow-sm"
           href="<?php echo 'export-stock-out.php?' . keep_qs(['format'=>'csv']); ?>">
          <i class="bi bi-download"></i> Export CSV
        </a>
        <a class="btn btn-outline-success rounded-pill shadow-sm"
           href="<?php echo 'export-stock-out.php?' . keep_qs(['format'=>'excel']); ?>">
          <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a> -->
        
      </div>
    </div>
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

    <div class="card shadow-sm rounded-4">
      <div class="card-body">
        <h5 class="card-title"><?= __t('Filter')?></h5>

        <form class="row g-3 mb-3" method="get" action="stock-out-index.php">
          <div class="col-md-3">
            <div class="form-floating">
              <select class="form-select" id="s_id" name="s_id">
                <option value="0"><?= __t('All Items')?></option>
                <?php foreach ($items as $it): ?>
                  <option value="<?php echo (int)$it['id']; ?>" <?php echo $s_id==(int)$it['id']?'selected':''; ?>>
                    <?php echo h($it['title']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="s_id"><?= __t('Item')?></label>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-floating">
              <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo h($valid_from); ?>">
              <label for="date_from"><?= __t('From')?></label>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-floating">
              <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo h($valid_to); ?>">
              <label for="date_to"><?= __t('To')?></label>
            </div>
          </div>

          <div class="col-md-3">
            <div class="form-floating">
              <select class="form-select" id="per_page" name="per_page">
                <?php foreach ([10,25,50,100] as $opt): ?>
                  <option value="<?php echo $opt; ?>" <?php echo $perPage==$opt?'selected':''; ?>>
                    <?php echo $opt; ?> per page
                  </option>
                <?php endforeach; ?>
              </select>
              <label for="per_page"><?= __t('Page Size')?></label>
            </div>
          </div>

          <div class="col-12 d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary rounded-pill shadow-sm">
              <i class="bi bi-funnel"></i> <?= __t('Apply')?>
            </button>
            <a href="stock-out-index.php" class="btn btn-outline-secondary rounded-pill shadow-sm"><?= __t('Reset')?></a>
          </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="text-muted small">
            <?php
              $fromN = $totalRows ? $offset + 1 : 0;
              $toN   = min($offset + $perPage, $totalRows);
              echo "Showing $fromN to $toN of $totalRows entries";
            ?>
          </div>
          <!-- Pagination controls -->
          <nav>
            <ul class="pagination pagination-sm mb-0">
              <?php
                $prev = max(1, $page-1);
                $next = min($totalPages, $page+1);
              ?>
              <li class="page-item <?php echo $page<=1?'disabled':''; ?>">
                <a class="page-link" href="?<?php echo keep_qs(['page'=>$prev]); ?>">&laquo;</a>
              </li>
              <?php
                // simple windowed pagination
                $start = max(1, $page-2);
                $end   = min($totalPages, $page+2);
                for ($p=$start; $p<=$end; $p++):
              ?>
                <li class="page-item <?php echo $p==$page?'active':''; ?>">
                  <a class="page-link" href="?<?php echo keep_qs(['page'=>$p]); ?>"><?php echo $p; ?></a>
                </li>
              <?php endfor; ?>
              <li class="page-item <?php echo $page>=$totalPages?'disabled':''; ?>">
                <a class="page-link" href="?<?php echo keep_qs(['page'=>$next]); ?>">&raquo;</a>
              </li>
            </ul>
          </nav>
        </div>

        <div class="table-responsive rounded-3 shadow-sm">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-primary">
              <tr>
                <th style="width:70px;">#</th>
                <th> <?= __t('Date')?></th>
                <th> <?= __t('Item')?></th>
                <th> <?= __t('Vehicle')?></th>
                <th class="text-end"> <?= __t('Qty')?></th>
                <th class="text-end"> <?= __t('Unit Price')?></th>
                <th class="text-end"> <?= __t('Total')?></th>
                <th>Note</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$data): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">No stock out entries found.</td></tr>
              <?php else: foreach ($data as $r): ?>
                <tr>
                  <td><?php echo (int)$r['id']; ?></td>
                  <td><?php echo h($r['date']); ?></td>
                  <td><?php echo h($r['item_title'] ?? '—'); ?></td>
                  <td><?php echo h($r['vehicle_number'] ?? '—'); ?></td>
                  <td class="text-end"><?php echo nf2($r['quantity']); ?></td>
                  <td class="text-end"><?php echo nf2($r['unit_price']); ?></td>
                  <td class="text-end fw-semibold"><?php echo nf2($r['total']); ?></td>
                  <td><?php echo h($r['note']); ?></td>
                  <td>
                    <?php if ((int)$r['status'] === 1): ?>
                      <span class="badge bg-success">Active</span>
                    <?php else: ?>
                      <span class="badge bg-secondary">Inactive</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
            <tfoot>
              <tr class="table-light">
                <th colspan="4" class="text-end">Page totals:</th>
                <th class="text-end"><?php echo nf2($pageQty); ?></th>
                <th class="text-end">—</th>
                <th class="text-end"><?php echo nf2($pageAmt); ?></th>
                <th colspan="2"></th>
              </tr>
            </tfoot>
          </table>
        </div>

      </div>
    </div>
  </section>
</main>

<?php include('Master/footer.php'); ?>
<?php include('Master/scripts.php'); ?>
</body>
</html>
