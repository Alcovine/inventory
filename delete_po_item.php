<?php
include 'newkonek.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['po_no']) && isset($_POST['product_code'])) {
    $po_no = mysqli_real_escape_string($con, $_POST['po_no']);
    $product_code = mysqli_real_escape_string($con, $_POST['product_code']);

    // Delete the specific row from po_trans
    $delete_query = "DELETE FROM `po_trans` WHERE `PO_No` = '$po_no' AND `Product_Code` = '$product_code'";
    
    if (mysqli_query($con, $delete_query)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($con);
    }
}
?>
