<?php
include 'newkonek.php'; // Database connection

$product_code = '';
$item_desc = '';
$balance = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_code = $_POST['search_product_code'];
    
    $stmt = $con->prepare("SELECT `Product Code` AS `Product Code`, `Item Description` AS `Item Description`, Balance FROM inventorymasterfile WHERE `Product Code` = ? LIMIT 1");

    $stmt->bind_param("s", $search_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $product_code = $row['Product Code'];
        $item_desc = $row['Item Description'];
        $balance = $row['Balance'];
    } else {
        $product_code = "Not Found";
        $item_desc = "Not Found";
        $balance = "0";
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Card</title>
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
            margin-bottom: 2rem;
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
        
        .btn-secondary {
            background: linear-gradient(135deg, var(--dark), #4a5568);
            border: none;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #4a5568, var(--dark));
        }
        
        .btn-light {
            background: #e2e8f0;
            border: none;
            color: var(--dark);
        }
        
        .btn-light:hover {
            background: #d1d9e6;
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
        
        /* Table styling */
        .table {
            border-collapse: separate;
            border-spacing: 0 5px;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }
        
        .table tbody tr {
            background-color: white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .table tbody td {
            padding: 1rem;
            border: none;
            vertical-align: middle;
        }
        
        /* Search form */
        .search-form {
            background-color: white;
            border-radius: 0.8rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        /* Product info panel */
        .product-info {
            background-color: white;
            border-radius: 0.8rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        /* Fade-in animation */
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
        
        /* Product group header */
        .product-group-header {
            background-color: #f8fafc;
            font-weight: 600;
            border-left: 4px solid var(--primary);
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            padding: 0.8rem 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            
            .search-form .form-group {
                margin-bottom: 1rem;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    <a class="nav-link dropdown-toggle" href="#" id="salesDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-receipt me-1"></i>Sales Order
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="sales_order.php"><i class="fas fa-chart-line me-2"></i>Sales</a></li>
                        <li><a class="dropdown-item" href="so_transactions.php"><i class="fas fa-file-circle-plus me-2"></i>Add New SO</a></li>
                        <li><a class="dropdown-item" href="addsales.php"><i class="fas fa-cart-plus me-2"></i>Add Item</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="stock_card.php">
                        <i class="fas fa-warehouse me-1"></i>Stock Card
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-wrapper fade-in">
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="fas fa-search me-2"></i>Search Stock Card</h3>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="search_product_code" class="form-label">
                        <i class="fas fa-barcode me-2"></i>Search by Product Code
                    </label>
                    <input type="text" class="form-control" id="search_product_code" name="search_product_code" 
                        placeholder="Enter Product Code" 
                        value="<?php echo isset($_POST['search_product_code']) ? htmlspecialchars($_POST['search_product_code']) : ''; ?>" required>
                </div>
                <div class="col-md-6 d-flex gap-2">
                    <button type="submit" name="search" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                    <button type="button" class="btn btn-light" onclick="window.location.href='stock_card.php'">
                        <i class="fas fa-times me-2"></i>Clear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($product_code)): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="fas fa-info-circle me-2"></i>Product Information</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5><i class="fas fa-barcode me-2"></i>Product Code: <span class="text-primary"><?php echo htmlspecialchars($product_code); ?></span></h5>
                    <h5><i class="fas fa-tag me-2"></i>Item Description: <span class="text-primary"><?php echo htmlspecialchars($item_desc); ?></span></h5>
                </div>
                <div class="col-md-4 text-end">
                    <h5><i class="fas fa-cubes me-2"></i>Current Balance: <span class="text-primary"><?php echo htmlspecialchars($balance); ?></span></h5>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="fas fa-history me-2"></i>Stock Card Transactions</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                if (!empty($product_code) && $product_code !== "Not Found") {
                    // If searching for a specific product code
                    $stmt = $con->prepare("SELECT * FROM stock_card WHERE Product_Code = ? 
                                        ORDER BY Date ASC, Reference_No ASC");
                    $stmt->bind_param("s", $product_code);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        echo '<table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col"><i class="fas fa-barcode me-2"></i>Product Code</th>
                                    <th scope="col"><i class="fas fa-tag me-2"></i>Item Description</th>
                                    <th scope="col"><i class="fas fa-calendar-alt me-2"></i>Date</th>
                                    <th scope="col"><i class="fas fa-file-invoice me-2"></i>Reference No</th>
                                    <th scope="col"><i class="fas fa-dollar-sign me-2"></i>Cost & Price</th>
                                    <th scope="col"><i class="fas fa-arrow-down me-2"></i>Quantity In</th>
                                    <th scope="col"><i class="fas fa-arrow-up me-2"></i>Quantity Out</th>
                                    <th scope="col"><i class="fas fa-balance-scale me-2"></i>Balance</th>
                                </tr>
                            </thead>
                            <tbody>';
                        
                        $running_balance = 0;
                        $rows_with_balance = array();
                        
                        // Calculate balance from oldest to newest
                        while ($row = $result->fetch_assoc()) {
                            // Calculate running balance
                            $running_balance += $row['Quantity_In'] - $row['Quantity_Out'];
                            
                            // Store the original reference number
                            $reference_no = $row['Reference_No'];
                            
                            // Ensure "PO#" or "SO#" is properly appended
                            if ($row['Quantity_In'] > 0 && strpos($reference_no, "PO#") === false) {
                                $reference_no = "PO#" . $reference_no;
                            } elseif ($row['Quantity_Out'] > 0 && strpos($reference_no, "SO#") === false) {
                                $reference_no = "SO#" . $reference_no;
                            }
                            
                            // Add formatted reference and calculated balance to row
                            $row['formatted_reference'] = $reference_no;
                            $row['calculated_balance'] = $running_balance;
                            $rows_with_balance[] = $row;
                        }
                        
                        // Sort rows to display newest to oldest (if needed)
                        usort($rows_with_balance, function($a, $b) {
                            $dateComparison = strtotime($b['Date']) - strtotime($a['Date']);
                            if ($dateComparison === 0) {
                                return strcmp($b['Reference_No'], $a['Reference_No']);
                            }
                            return $dateComparison;
                        });
                        
                        foreach ($rows_with_balance as $row) {
                            echo '<tr>
                                <td>'.htmlspecialchars($row['Product_Code']).'</td>
                                <td>'.htmlspecialchars($row['Item_Description']).'</td>
                                <td>'.htmlspecialchars($row['Date']).'</td>
                                <td>'.htmlspecialchars($row['formatted_reference']).'</td>
                                <td>₱'.htmlspecialchars($row['Cost&Price']).'</td>
                                <td>'.htmlspecialchars($row['Quantity_In']).'</td>
                                <td>'.htmlspecialchars($row['Quantity_Out']).'</td>
                                <td>'.$row['calculated_balance'].'</td>
                            </tr>';
                        }
                        
                        echo '</tbody></table>';
                    } else {
                        echo '<div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>No stock transactions found for this product code
                        </div>';
                    }
                } else {
                    // If no specific product code is searched, show all transactions grouped by product
                    $query = "SELECT DISTINCT Product_Code, Item_Description FROM stock_card ORDER BY Product_Code";
                    $product_result = $con->query($query);
                    
                    if ($product_result && mysqli_num_rows($product_result) > 0) {
                        while ($product = $product_result->fetch_assoc()) {
                            $product_code = $product['Product_Code'];
                            $item_desc = $product['Item_Description'];
                            
                            // Display product header
                            echo '<div class="product-group-header">
                                <i class="fas fa-barcode me-2"></i>Product Code: <span class="text-primary">'.htmlspecialchars($product_code).'</span> - 
                                <span class="text-secondary">'.htmlspecialchars($item_desc).'</span>
                            </div>';
                            
                            echo '<table class="table table-hover mb-4">
                                <thead>
                                    <tr>
                                        <th scope="col"><i class="fas fa-calendar-alt me-2"></i>Date</th>
                                        <th scope="col"><i class="fas fa-file-invoice me-2"></i>Reference No</th>
                                        <th scope="col"><i class="fas fa-dollar-sign me-2"></i>Cost & Price</th>
                                        <th scope="col"><i class="fas fa-arrow-down me-2"></i>Quantity In</th>
                                        <th scope="col"><i class="fas fa-arrow-up me-2"></i>Quantity Out</th>
                                        <th scope="col"><i class="fas fa-balance-scale me-2"></i>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>';
                            
                            // Get transactions for this product code - ORDER BY Date ASC for correct balance calculation
                            $stmt = $con->prepare("SELECT * FROM stock_card WHERE Product_Code = ? ORDER BY Date ASC, Reference_No ASC");
                            $stmt->bind_param("s", $product_code);
                            $stmt->execute();
                            $transactions = $stmt->get_result();
                            
                            // Reset running balance for each new product
                            $running_balance = 0;
                            $product_transactions = array();
                            
                            // Calculate balance from oldest to newest
                            while ($row = $transactions->fetch_assoc()) {
                                // Calculate running balance
                                $running_balance += $row['Quantity_In'] - $row['Quantity_Out'];
                                
                                // Format reference number
                                $reference_no = $row['Reference_No'];
                                if ($row['Quantity_In'] > 0 && strpos($reference_no, "PO#") === false) {
                                    $reference_no = "PO#" . $reference_no;
                                } elseif ($row['Quantity_Out'] > 0 && strpos($reference_no, "SO#") === false) {
                                    $reference_no = "SO#" . $reference_no;
                                }
                                
                                // Add formatted reference and calculated balance to row
                                $row['formatted_reference'] = $reference_no;
                                $row['calculated_balance'] = $running_balance;
                                $product_transactions[] = $row;
                            }
                            
                            // Sort transactions to display newest to oldest
                            usort($product_transactions, function($a, $b) {
                                $dateComparison = strtotime($b['Date']) - strtotime($a['Date']);
                                if ($dateComparison === 0) {
                                    return strcmp($b['Reference_No'], $a['Reference_No']);
                                }
                                return $dateComparison;
                            });
                            
                            if (count($product_transactions) > 0) {
                                foreach ($product_transactions as $row) {
                                    echo '<tr>
                                        <td>'.htmlspecialchars($row['Date']).'</td>
                                        <td>'.htmlspecialchars($row['formatted_reference']).'</td>
                                        <td>₱'.htmlspecialchars($row['Cost&Price']).'</td>
                                        <td>'.htmlspecialchars($row['Quantity_In']).'</td>
                                        <td>'.htmlspecialchars($row['Quantity_Out']).'</td>
                                        <td>'.$row['calculated_balance'].'</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center">No transactions found for this product</td></tr>';
                            }
                            
                            echo '</tbody></table>';
                        }
                    } else {
                        echo '<div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>No stock transactions found
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    // Hover effect for dropdown menus
    $('.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(300);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(300);
    });
});
</script>
</body>
</html>