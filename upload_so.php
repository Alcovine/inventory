<?php
include 'newkonek.php'; // Database connection

// Fetch the highest SO number and generate the next SO number
$sql = "SELECT MAX(`SO_No`) AS max_so FROM salesorder";
$result = mysqli_query($con, $sql);
$max_so = ($result) ? (int) mysqli_fetch_assoc($result)['max_so'] : 0;
$next_so = str_pad($max_so + 1, 5, '0', STR_PAD_LEFT);
$current_date = date('Y-m-d');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_so'])) {
    $so_no = $_POST['SO_no'];
    $customer_name = mysqli_real_escape_string($con, $_POST['Customers_name']);
    $date = $_POST['Date'];
    $total_amount = $_POST['Total_amount'];

    $insertSO = "INSERT INTO salesorder (`SO_No`, `Customers_name`, `Date`, `Total_amount`) 
                 VALUES ('$so_no', '$customer_name', '$date', '$total_amount')";
    
    if (mysqli_query($con, $insertSO)) {
        echo "<script>alert('Sales Order Successfully Created!'); window.location.href='sales_order.php?so_no=$so_no';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}
?>