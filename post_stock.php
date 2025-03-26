<?php
include 'newkonek.php';

if (isset($_POST['post_stock'])) {
    $po_no = $_POST['po_no'];

    // Fetch items from po_trans for the selected PO
    $query = "SELECT Product_Code, Item_Description, Cost, Quantity FROM po_trans WHERE PO_No = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $po_no);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product_code = $row['Product_Code'];
        $item_desc = $row['Item_Description'];
        $cost = $row['Cost'];
        $quantity_in = $row['Quantity'];
        $date = date("Y-m-d"); // Current date
        $reference_no = "PO#" . $po_no;

        // Insert into stock_card
        $insertQuery = "INSERT INTO stock_card (Product_Code, Item_Description, Date, Reference_No, `Cost&Price`, Quantity_In, Quantity_Out) 
                        VALUES (?, ?, ?, ?, ?, ?, 0)";
        $insertStmt = $con->prepare($insertQuery);
        $insertStmt->bind_param("ssssdi", $product_code, $item_desc, $date, $reference_no, $cost, $quantity_in);
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

    $stmt->close();
    echo "<script>alert('Stock successfully posted!'); window.location.href='purchase_order.php';</script>";
}
?>
