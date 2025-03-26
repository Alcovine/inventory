<?php
include 'newkonek.php';

$po_no = $_POST['po_no'];

$total_query = "SELECT SUM(`Amount`) AS total_amount FROM `po_trans` WHERE `PO_no` = ?";
$stmt = mysqli_prepare($con, $total_query);
mysqli_stmt_bind_param($stmt, "s", $po_no);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
echo $row['total_amount'] ?? '0.00';

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
