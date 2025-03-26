<?php
session_start();
include 'newkonek.php'; // Database connection

if (isset($_POST['submit'])) {
    $po_no = $_POST['PO_No'];
    $product_code = $_POST['Product_Code'];
    $item_desc = $_POST['Item_Description'];
    $packaging = $_POST['Packaging'];
    $cost = $_POST['Cost'];
    $quantity = $_POST['Quantity'];
    $amount = $cost * $quantity;

    // Insert into `po_trans` table
    $query = "INSERT INTO po_trans (PO_No, Product_Code, Item_Description, Packaging, Cost, Quantity, Amount) 
              VALUES ('$po_no', '$product_code', '$item_desc', '$packaging', '$cost', '$quantity', '$amount')";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Item added successfully!'); window.location.href='addpurchase.php?po_no=$po_no';</script>";
    } else {
        echo "<script>alert('Error adding item!'); window.history.back();</script>";
    }
}
?>
