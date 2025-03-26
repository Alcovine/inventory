<?php
include 'newkonek.php'; // Database connection

// Fetch the highest PO number and generate the next PO number
$sql = "SELECT MAX(`PO_no`) AS max_po FROM purchaseorder";
$result = mysqli_query($con, $sql);
$max_po = ($result) ? (int) mysqli_fetch_assoc($result)['max_po'] : 0;
$next_po = str_pad($max_po + 1, 5, '0', STR_PAD_LEFT);
$current_date = date('Y-m-d');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_po'])) {
    $po_no = $_POST['PO_no'];
    $supplier_name = mysqli_real_escape_string($con, $_POST['Suppliers_name']);
    $date = $_POST['Date'];
    $total_amount = $_POST['Total_amount'];

    $insertPO = "INSERT INTO purchaseorder (`PO_no`, `Suppliers_name`, `Date`, `Total_amount`) 
                 VALUES ('$po_no', '$supplier_name', '$date', '$total_amount')";
    
    if (mysqli_query($con, $insertPO)) {
        echo "<script>alert('Purchase Order Successfully Created!'); window.location.href='purchase_order.php?po_no=$po_no';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}
?>

