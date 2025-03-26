<?php
include 'newkonek.php';

$so_no = $_POST['so_no'];  // Changed from $po_no to $so_no
$product_code = $_POST['product_code'];
$packaging = $_POST['packaging'];
$qnty = $_POST['qnty'];
$amount = $_POST['amount'];

// Update so transactions table
$sql = "UPDATE `so transactions` SET `Quantity`=?, `Amount`=? WHERE `SO_no`=? AND `Product_Code`=?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $qnty, $amount, $so_no, $product_code);

if (mysqli_stmt_execute($stmt)) {
    // Update total amount in salesorder table
    $update_query = "UPDATE `salesorder` SET `Total_amount` = (SELECT SUM(`Amount`) FROM `so transactions` WHERE `SO_no`=?) WHERE `SO_no`=?";
    $update_stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ss", $so_no, $so_no);
    mysqli_stmt_execute($update_stmt);
    
    echo "success";
} else {
    echo "error";
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($update_stmt);
mysqli_close($con);
?>
