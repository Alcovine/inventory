<?php
session_start();
include 'newkonek.php';

if (isset($_POST['post_to_stock'])) {
    $so_no = $_POST['so_no'];

    // Fetch SO transaction details
    $query = "SELECT * FROM `so transactions` WHERE `SO_No` = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $so_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_code = $row['Product_Code'];
            $item_desc = $row['Item_Description'];
            $price = $row['Price'];
            $quantity_out = $row['Quantity'];
            $date = date("Y-m-d"); // Current date
            $reference_no = "SO#" . $so_no;

            // Insert into stock_card
            $insert_stock = "INSERT INTO stock_card 
                (Product_Code, Item_Description, Date, Reference_No, `Cost&Price`, Quantity_In, Quantity_Out) 
                VALUES 
                (?, ?, ?, ?, ?, 0, ?)";
            $insertStmt = $con->prepare($insert_stock);
            $insertStmt->bind_param("ssssdi", $product_code, $item_desc, $date, $reference_no, $price, $quantity_out);
            $insertStmt->execute();
            $insertStmt->close();

            // Update Quantity_Balance continuously
            $update_balance = "UPDATE stock_card 
                               SET Quantity_Balance = 
                               (SELECT COALESCE(SUM(Quantity_In) - SUM(Quantity_Out), 0) 
                                FROM stock_card WHERE Product_Code = ?) 
                               WHERE Product_Code = ?";
            $updateStmt = $con->prepare($update_balance);
            $updateStmt->bind_param("ss", $product_code, $product_code);
            $updateStmt->execute();
            $updateStmt->close();

            // Update inventory balance
            $update_inventory = "UPDATE inventorymasterfile 
                                 SET Balance = 
                                 (SELECT COALESCE(SUM(Quantity_In) - SUM(Quantity_Out), 0) 
                                  FROM stock_card WHERE Product_Code = ?) 
                                 WHERE `Product Code` = ?";
            $updateInvStmt = $con->prepare($update_inventory);
            $updateInvStmt->bind_param("ss", $product_code, $product_code);
            $updateInvStmt->execute();
            $updateInvStmt->close();
        }

        echo "<script>alert('Stock successfully posted!'); window.location.href='sales_order.php';</script>";
    } else {
        echo "<script>alert('No transactions found for this SO!'); window.location.href='sales_order.php';</script>";
    }

    $stmt->close();
}
?>
