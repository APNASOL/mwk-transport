<?php
include 'DatabaseController.php';
$conn = OpenCon();
session_start();
$current_vehicle_id = $_SESSION['current_vehicle_id'];
$current_vehicle_number = $_SESSION['current_vehicle_number'];


if ($current_vehicle_id == 0 || $current_vehicle_id == '') {
    header('location:../vehicle-index.php?successMessage=Return');
    exit;
}

if (isset($_POST['create_stock'])) {
    $title = trim($_POST['title'] ?? '');
    $supplier = trim($_POST['supplier'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 0;
    $totalBill = isset($_POST['bill']) ? floatval(str_replace(',', '', $_POST['bill'])) : 0;

    // Basic validation
    if ($title === '' || $supplier === '' || $note === '' || $date === '' || $quantity <= 0 || $totalBill < 0) {
        header('location:../stock-create.php?errorMessage=Please fill all fields correctly. Quantity must be > 0.');
        exit;
    }

    // Always compute unit price on server to prevent tampering
    $unitPrice = $totalBill / $quantity;
    $unitPrice = round($unitPrice, 2);

    $usedQuantity = 0;
    $status = 1;

    // Start transaction (optional but safe)
    mysqli_begin_transaction($conn);
    mysqli_autocommit($conn, false);

    $sql = "INSERT INTO stock
        (title, supplier, note, quantity, used_quantity, unit_price, total_bill, date, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";




    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param(
            $stmt,
            "sssddddsi",  // <-- 9 types for 9 values
            $title,       // s
            $supplier,    // s
            $note,        // s
            $quantity,    // d
            $usedQuantity,// d
            $unitPrice,   // d
            $totalBill,   // d
            $date,        // s (e.g. '2025-08-13')
            $status       // i
        );

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($ok) {
            mysqli_commit($conn);
            mysqli_autocommit($conn, true);
            header('location:../stock-index.php?successMessage=Stock added successfully');
            exit;
        } else {
            mysqli_rollback($conn);
            mysqli_autocommit($conn, true);
            header('location:../stock-create.php?errorMessage=Failed to add stock. DB error.');
            exit;
        }
    } else {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-create.php?errorMessage=Failed to prepare statement.');
        exit;
    }
}

if (isset($_POST['update_stock'])) {
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $supplier = trim($_POST['supplier'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $status = intval($_POST['status'] ?? 1);
    $quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 0;
    $totalBill = isset($_POST['bill']) ? floatval(str_replace(',', '', $_POST['bill'])) : 0;

    if ($id <= 0) {
        header('location:../stock-edit.php?id=' . $id . '&errorMessage=Invalid ID');
        exit;
    }

    if ($title === '' || $supplier === '' || $note === '' || $date === '' || $quantity <= 0 || $totalBill < 0) {
        header('location:../stock-edit.php?id=' . $id . '&errorMessage=Please fill all fields correctly.');
        exit;
    }

    // Fetch used_quantity to ensure new quantity >= used_quantity
    $stmtChk = mysqli_prepare($conn, "SELECT used_quantity FROM stock WHERE id = ?");
    mysqli_stmt_bind_param($stmtChk, "i", $id);
    mysqli_stmt_execute($stmtChk);
    $resChk = mysqli_stmt_get_result($stmtChk);
    $rowChk = mysqli_fetch_assoc($resChk);
    mysqli_stmt_close($stmtChk);

    if (!$rowChk) {
        header('location:../stock-edit.php?id=' . $id . '&errorMessage=Stock not found.');
        exit;
    }

    $usedQuantity = floatval($rowChk['used_quantity']);
    if ($quantity < $usedQuantity) {
        header('location:../stock-edit.php?id=' . $id . '&errorMessage=Quantity cannot be less than used quantity (' . $usedQuantity . ').');
        exit;
    }

    // Recompute on server
    $unitPrice = round($totalBill / $quantity, 2);

    mysqli_begin_transaction($conn);
    mysqli_autocommit($conn, false);

    $sql = "UPDATE stock
            SET title = ?, supplier = ?, note = ?, quantity = ?, unit_price = ?, total_bill = ?, date = ?, status = ?
            WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // s s s d d d s i i  => "sssdddsii"
        mysqli_stmt_bind_param(
            $stmt,
            "sssdddsii",
            $title,        // s
            $supplier,     // s
            $note,         // s
            $quantity,     // d
            $unitPrice,    // d
            $totalBill,    // d
            $date,         // s
            $status,       // i
            $id            // i
        );

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($ok) {
            mysqli_commit($conn);
            mysqli_autocommit($conn, true);
            header('location:../stock-edit.php?id=' . $id . '&successMessage=Stock updated successfully');
            exit;
        } else {
            mysqli_rollback($conn);
            mysqli_autocommit($conn, true);
            error_log('Stock update error: ' . mysqli_error($conn));
            header('location:../stock-edit.php?id=' . $id . '&errorMessage=Failed to update stock.');
            exit;
        }
    } else {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-edit.php?id=' . $id . '&errorMessage=Failed to prepare statement.');
        exit;
    }
}

if (isset($_POST['stock_out_create'])) {
    $sid = intval($_POST['s_id'] ?? 0);              // stock.id
    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
    $qtyReq = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 0;
    $note = trim($_POST['note'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $status = 1;

    if ($sid <= 0 || $vehicle_id <= 0 || $qtyReq <= 0 || $date === '') {
        header('location:../stock-out-create.php?errorMessage=Please fill all fields correctly.');
        exit;
    }

    mysqli_begin_transaction($conn);
    mysqli_autocommit($conn, false);

    /** 1) Lock the stock row and get current numbers */
    $sqlGet = "SELECT id, title, quantity, used_quantity, unit_price, status
           FROM stock
           WHERE id = ?
           FOR UPDATE";
    if (!($stmt = mysqli_prepare($conn, $sqlGet))) {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-out-create.php?errorMessage=Failed to prepare (get stock).');
        exit;
    }
    mysqli_stmt_bind_param($stmt, "i", $sid);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $stock = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$stock) {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-out-create.php?errorMessage=Stock item not found.');
        exit;
    }

    // Calculate availability
    $totalQty = floatval($stock['quantity']);
    $usedQty = floatval($stock['used_quantity']);
    $availableQty = $totalQty - $usedQty;

    if ($availableQty <= 0) {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-out-create.php?errorMessage=All quantity already used for this item.');
        exit;
    }

    if ($qtyReq > $availableQty) {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-out-create.php?errorMessage=Requested quantity exceeds available (Available: ' . rtrim(rtrim(number_format($availableQty, 3, '.', ''), '0'), '.') . ').');
        exit;
    }

    /** 2) Use the stock unit price; compute total */
    $unitPrice = floatval($stock['unit_price']);
    $total = round($unitPrice * $qtyReq, 2);

    /** 3) Insert stock_out row */
    $sqlOut = "INSERT INTO stock_out
          (s_id, quantity, unit_price, total, vehicle_id, note, date, status)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if (!($stmt = mysqli_prepare($conn, $sqlOut))) {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-out-create.php?errorMessage=Failed to prepare (insert stock_out).');
        exit;
    }
    mysqli_stmt_bind_param(
        $stmt,
        "idddissi",
        $sid,           // i
        $qtyReq,        // d
        $unitPrice,     // d
        $total,         // d
        $vehicle_id,    // i
        $note,          // s
        $date,          // s (YYYY-MM-DD)
        $status         // i
    );
    $okOut = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$okOut) {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-out-create.php?errorMessage=Failed to insert stock out.');
        exit;
    }

    /** 4) Update stock.used_quantity (and optionally status when fully used) */
    $newUsed = $usedQty + $qtyReq;
    $markInactive = ($newUsed >= $totalQty) ? 1 : 0;

    if ($markInactive) {
        $sqlUpd = "UPDATE stock SET used_quantity = ?, status = 0 WHERE id = ?";
        $types = "di";
        $args = [$newUsed, $sid];
    } else {
        $sqlUpd = "UPDATE stock SET used_quantity = ? WHERE id = ?";
        $types = "di";
        $args = [$newUsed, $sid];
    }

    if (!($stmt = mysqli_prepare($conn, $sqlUpd))) {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-out-create.php?errorMessage=Failed to prepare (update stock).');
        exit;
    }
    mysqli_stmt_bind_param($stmt, $types, ...$args);
    $okUpd = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if (!$okUpd) {
        mysqli_rollback($conn);
        mysqli_autocommit($conn, true);
        header('location:../stock-out-create.php?errorMessage=Failed to update stock usage.');
        exit;
    }

    mysqli_commit($conn);
    mysqli_autocommit($conn, true);

    $msg = $markInactive
        ? 'Stock out recorded. Item fully used and marked inactive.'
        : 'Stock out recorded successfully.';
    header('location:../stock-index.php?successMessage=' . urlencode($msg));
    exit;
}