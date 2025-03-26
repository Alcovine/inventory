<?php
include 'newkonek.php';

$po_no = $_POST['po_no'];
$product_code = $_POST['product_code'];
$packaging = $_POST['packaging'];
$qnty = $_POST['qnty'];
$amount = $_POST['amount'];

// Update po_trans table
$sql = "UPDATE `po_trans` SET `Quantity`=?, `Amount`=? WHERE `PO_no`=? AND `Product_Code`=?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $qnty, $amount, $po_no, $product_code);

if (mysqli_stmt_execute($stmt)) {
    // Update total amount in purchaseorder table
    $update_query = "UPDATE `purchaseorder` SET `Total_amount` = (SELECT SUM(`Amount`) FROM `po_trans` WHERE `PO_no`=?) WHERE `PO_no`=?";
    $update_stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ss", $po_no, $po_no);
    mysqli_stmt_execute($update_stmt);
    
    echo "success";
} else {
    echo "error";
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
