<?php
include 'newkonek.php';

if (isset($_POST['submit'])) {
    // Fetch form data
    $product_code = $_POST['Product_Code'];
    $item_description = $_POST['Item_Description'];
    $packaging = $_POST['Packaging'];
    $cost = $_POST['Cost'];
    $selling_price = $_POST['Selling_price'];
    $balance = $_POST['Balance'];

    // Step 2: Insert the new record with the generated product code
    $sql = "INSERT INTO inventorymasterfile (Product_Code, Item_Description, Packaging, Cost, Selling_price, Balance) 
            VALUES ('$product_code', '$item_description', '$packaging', '$cost', '$selling_price', '$balance')";

    if (mysqli_query($con, $sql)) {
        echo "Inventory item added successfully with Product Code: $product_code";
        header('Location: ForDisplay.php');  // Redirect to the display page
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
