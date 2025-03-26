<?php
session_start(); // Start the session
include 'newkonek.php';

// Check if an SO number is stored in the session
if (isset($_SESSION['so_number'])) {
    $search_so = $_SESSION['so_number'];
} else {
    // Default to the latest SO number if no session exists
    $so_query = "SELECT * FROM `salesorder` ORDER BY `SO_No` DESC LIMIT 1";
    $so_result = mysqli_query($con, $so_query);
    $so_data = mysqli_fetch_assoc($so_result);
    $search_so = $so_data['SO_No'] ?? '';
}

// Handle quantity update via AJAX
if (isset($_POST['update_quantity'])) {
    $product_code = $_POST['product_code'];
    $new_quantity = $_POST['quantity'];
    $so_no = $_POST['so_no'];

    // First, get the current price to recalculate amount
    $price_query = "SELECT `Price` FROM `so transactions` WHERE `SO_No` = '$so_no' AND `Product_Code` = '$product_code'";
    $price_result = mysqli_query($con, $price_query);
    $price_row = mysqli_fetch_assoc($price_result);
    $price = $price_row['Price'];

    // Calculate new amount
    $new_amount = $new_quantity * $price;

    // Update quantity and amount
    $update_query = "UPDATE `so transactions` 
                     SET `Quantity` = '$new_quantity', 
                         `Amount` = '$new_amount' 
                     WHERE `SO_No` = '$so_no' AND `Product_Code` = '$product_code'";
    $update_result = mysqli_query($con, $update_query);

    // Recalculate total SO amount
    $total_query = "SELECT SUM(`Amount`) AS total_amount FROM `so transactions` WHERE `SO_No` = '$so_no'";
    $total_result = mysqli_query($con, $total_query);
    $total_data = mysqli_fetch_assoc($total_result);
    $total_amount = $total_data['total_amount'] ?? 0;

    // Update salesorder total amount
    $so_update_query = "UPDATE `salesorder` SET `Total_Amount` = '$total_amount' WHERE `SO_No` = '$so_no'";
    mysqli_query($con, $so_update_query);

    // Return the new amount for the specific row
    echo json_encode([
        'new_amount' => number_format($new_amount, 2),
        'total_amount' => number_format($total_amount, 2)
    ]);
    exit();
}

// Check if an SO number is stored in the session
if (isset($_SESSION['so_number'])) {
    $search_so = $_SESSION['so_number'];
} else {
    // Default to the latest SO number if no session exists
    $so_query = "SELECT * FROM `salesorder` ORDER BY `SO_No` DESC LIMIT 1";
    $so_result = mysqli_query($con, $so_query);
    $so_data = mysqli_fetch_assoc($so_result);
    $search_so = $so_data['SO_No'] ?? '';
}

// If a new SO number is searched, update the session
if (isset($_POST['search_so'])) {
    $search_so = $_POST['so_number'];
    $_SESSION['so_number'] = $search_so; // Store in session

    // Calculate the total amount for the current SO
    $total_query = "SELECT SUM(`Amount`) AS total_amount FROM `so transactions` WHERE `SO_No` = '$search_so'";
    $total_result = mysqli_query($con, $total_query);
    $total_data = mysqli_fetch_assoc($total_result);
    $total_amount = $total_data['total_amount'] ?? 0;

    // Update the salesorder table with the new total amount
    $update_query = "UPDATE `salesorder` SET `Total_Amount` = '$total_amount' WHERE `SO_No` = '$search_so'";
    mysqli_query($con, $update_query);
}

// Fetch the sales order data based on the session SO number
$so_query = "SELECT * FROM `salesorder` WHERE `SO_No` = '$search_so'";
$so_result = mysqli_query($con, $so_query);
$so_data = mysqli_fetch_assoc($so_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Order Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --accent: #4cc9f0;
            --success: #4ade80;
            --danger: #f87171;
            --warning: #fbbf24;
            --light: #f9fafb;
            --dark: #1f2937;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }
        
        /* Navbar styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: white !important;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 0.7rem 1rem;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover, 
        .navbar-nav .nav-link:focus {
            color: white !important;
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .navbar-nav .nav-link:hover::after {
            width: 70%;
        }
        
        .dropdown-menu {
            background-color: var(--dark);
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
            margin-top: 0.5rem;
            border-top: 3px solid var(--accent);
        }
        
        .dropdown-item {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 400;
            padding: 0.65rem 1.5rem;
            border-radius: 0.35rem;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        /* Main content styling */
        .content-wrapper {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            font-weight: 600;
            border: none;
            padding: 1.2rem 1.5rem;
        }
        
        .card-header h3 {
            margin: 0;
            font-size: 1.4rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Buttons styling */
        .btn {
            font-weight: 500;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success), #38b2ac);
            border: none;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #38b2ac, var(--success));
        }
        
        /* Table styling */
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .table th {
            font-weight: 500;
            border: none;
            padding: 1rem;
        }
        
        .table td {
            padding: 0.8rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #eeeeee;
        }
        
        .table tbody tr {
            transition: background-color 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Form controls */
        .form-control {
            border-radius: 0.5rem;
            padding: 0.65rem 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        /* Editable content */
        [contenteditable="true"] {
            padding: 0.5rem;
            border-radius: 0.35rem;
            transition: all 0.2s;
            cursor: pointer;
            position: relative;
        }
        
        [contenteditable="true"]:hover {
            background-color: rgba(67, 97, 238, 0.1);
        }
        
        [contenteditable="true"]:focus {
            background-color: rgba(67, 97, 238, 0.15);
            outline: none;
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.3);
        }
        
        /* Info sections */
        .so-info-card {
            margin-bottom: 2rem;
            background-color: white;
            border-radius: 0.8rem;
            overflow: hidden;
        }
        
        .so-header {
            padding: 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .so-details {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .so-details-left, .so-details-right {
            flex: 1;
            min-width: 200px;
        }
        
        .so-details-right {
            text-align: right;
        }
        
        .so-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .so-value {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        
        /* Actions section */
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        /* Modal styling */
        .modal-content {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 1.5rem;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border-top: 1px solid #edf2f7;
            padding: 1.2rem 1.5rem;
        }
        
        /* Utilities */
        .badge {
            padding: 0.5rem 0.8rem;
            border-radius: 0.35rem;
            font-weight: 500;
        }
        
        .amount {
            font-weight: 500;
        }
        
        .total-amount {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .so-details {
                flex-direction: column;
            }
            
            .so-details-right {
                text-align: left;
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="fas fa-boxes me-2"></i>Inventory System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="inventoryDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-clipboard-list me-1"></i>Inventory Monitoring
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="ForDisplay.php"><i class="fas fa-table me-2"></i>Inventory List</a></li>
                        <li><a class="dropdown-item" href="link.php"><i class="fas fa-plus-circle me-2"></i>Add Item</a></li>
                        <li><a class="dropdown-item" href="display_inventory.php"><i class="fas fa-edit me-2"></i>Edit Item</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="purchaseDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-shopping-cart me-1"></i>Purchase Order
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="purchase_order.php"><i class="fas fa-file-invoice me-2"></i>Purchases</a></li>
                        <li><a class="dropdown-item" href="po_transactions.php"><i class="fas fa-file-circle-plus me-2"></i>Add New PO</a></li>
                        <li><a class="dropdown-item" href="addpurchase.php"><i class="fas fa-cart-plus me-2"></i>Add Item</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="salesDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-receipt me-1"></i>Sales Order
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="sales_order.php"><i class="fas fa-chart-line me-2"></i>Sales</a></li>
                        <li><a class="dropdown-item" href="so_transactions.php"><i class="fas fa-file-circle-plus me-2"></i>Add New SO</a></li>
                        <li><a class="dropdown-item" href="addsales.php"><i class="fas fa-cart-plus me-2"></i>Add Item</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="stock_card.php">
                        <i class="fas fa-warehouse me-1"></i>Stock Card
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-wrapper fade-in">
    <!-- Actions bar -->
    <div class="actions-bar">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#soModal">
            <i class="fas fa-search me-2"></i>Search SO Number
        </button>
        
        <form method="POST" action="post_stock_so.php" class="d-inline">
            <input type="hidden" name="so_no" value="<?= $search_so ?>">
            <button type="submit" name="post_to_stock" class="btn btn-success">
                <i class="fas fa-clipboard-check me-2"></i>Post to Stock Card
            </button>
        </form>
    </div>
    
    <!-- SO Info Card -->
    <div class="so-info-card shadow">
        <div class="so-header">
            SALES ORDER
        </div>
        <div class="so-details">
            <div class="so-details-left">
                <div class="so-label">Customer's Name:</div>
                <div class="so-value" id="customer-name"><?php echo $so_data['Customers_name'] ?? 'Not specified'; ?></div>
                
                <div class="so-label">Date:</div>
                <div class="so-value" id="so-date"><?php echo $so_data['Date'] ?? 'Not specified'; ?></div>
            </div>
            <div class="so-details-right">
                <div class="so-label">SO No.:</div>
                <div class="so-value" id="so-number"><?php echo $so_data['SO_No'] ?? 'Not specified'; ?></div>
                
                <div class="so-label">Total Amount:</div>
                <div class="so-value total-amount" id="total-amount">₱ <?php echo number_format($so_data['Total_amount'] ?? 0, 2); ?></div>
            </div>
        </div>
    </div>
    
    <!-- SO Items Table Card -->
    <div class="card shadow fade-in">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="fas fa-list me-2"></i>Sales Order Items</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Product Code</th>
                            <th>Item Description</th>
                            <th>Packaging</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
    if (!empty($search_so)) {
        $sql = "SELECT * FROM `so transactions` WHERE `SO_No` = '$search_so'";
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                    <td><span class="badge bg-light text-dark">'.$row['Product_Code'].'</span></td>
                    <td>'.$row['Item_Description'].'</td>
                    <td>'.$row['Packaging'].'</td>
                    <td>₱ '.number_format($row['Price'], 2).'</td>
                    <td contenteditable="true" class="editable-quantity" 
                        data-product-code="'.$row['Product_Code'].'" 
                        data-so-no="'.$search_so.'">'.$row['Quantity'].'</td>
                    <td class="amount">₱ '.number_format($row['Amount'], 2).'</td>
                </tr>';
            }
        } else {
            echo '<tr><td colspan="6" class="text-center py-4">No items found for this sales order</td></tr>';
        }
    } else {
        echo '<tr><td colspan="6" class="text-center py-4">No sales order selected</td></tr>';
    }
    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- SO Search Modal -->
<div class="modal fade" id="soModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-search me-2"></i>Search Sales Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="so_number" class="form-label">Enter SO Number:</label>
                        <input type="text" id="so_number" name="so_number" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="search_so" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Quantity edit functionality
    $(".editable-quantity").on('blur', function() {
        var newQuantity = $(this).text().trim();
        var productCode = $(this).data('product-code');
        var soNo = $(this).data('so-no');
        var $amountCell = $(this).next('.amount');
        var $totalAmountCell = $("#total-amount");

        // Validate input is a number
        if (isNaN(newQuantity) || newQuantity <= 0) {
            alert('Please enter a valid quantity');
            return;
        }

        $.ajax({
            url: '',  // Same page
            type: 'POST',
            data: {
                update_quantity: true,
                product_code: productCode,
                quantity: newQuantity,
                so_no: soNo
            },
            dataType: 'json',
            success: function(response) {
                // Update amount for this row
                $amountCell.text('₱ ' + response.new_amount);
                
                // Update total amount
                $totalAmountCell.text('₱ ' + response.total_amount);
            },
            error: function() {
                alert('Error updating quantity');
            }
        });
    });

    // Prevent non-numeric input in editable quantity
    $(".editable-quantity").on('keypress', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>