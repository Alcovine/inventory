<?php
// Include the database connection
include 'newkonek.php';

// Check if product_code is set in the URL
if (isset($_GET['product_code'])) {
    $product_code = mysqli_real_escape_string($con, $_GET['product_code']);
    
    // Delete query
    $sql = "DELETE FROM inventorymasterfile WHERE `Product Code` = '$product_code'";
    
    if (mysqli_query($con, $sql)) {
        echo "<script>alert('Record deleted successfully');</script>";
        echo "<script>window.location.href = 'display_inventory.php';</script>";
    } else {
        echo "<script>alert('Error deleting record: " . mysqli_error($con) . "');</script>";
    }
} else {
    echo "<script>alert('No product code specified');</script>";
    echo "<script>window.location.href = 'display_inventory.php';</script>";
}

// Close the database connection
mysqli_close($con);
?>
