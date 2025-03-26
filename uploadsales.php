<?php
session_start();
include 'newkonek.php'; // Database connection

if (isset($_POST['submit'])) {
    $so_no = $_POST['SO_No'];
    $product_code = $_POST['Product_Code'];
    $item_desc = $_POST['Item_Description'];
    $packaging = $_POST['Packaging'];
    $price = $_POST['Price'];
    $quantity = $_POST['Quantity'];
    $amount = $price * $quantity;

    // Insert into `so_transactions` table
    $query = "INSERT INTO `so transactions` (SO_No, Product_Code, Item_Description, Packaging, Price, Quantity, Amount) 
              VALUES ('$so_no', '$product_code', '$item_desc', '$packaging', '$price', '$quantity', '$amount')";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Item added successfully!'); window.location.href='sales_order.php?so_no=$so_no';</script>";
    } else {
        echo "<script>alert('Error adding item!'); window.history.back();</script>";
    }
}
?>
