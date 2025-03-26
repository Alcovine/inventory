<?php
// Include your database connection
include 'newkonek.php';

// Check if a product code is entered for search
if (isset($_GET['product_code'])) {
    $product_code = mysqli_real_escape_string($con, $_GET['product_code']);
    $sql = "SELECT * FROM inventorymasterfile WHERE Product Code LIKE '%$product_code%'";
} else {
    // If no search, select all products
    $sql = "SELECT * FROM inventorymasterfile";
}

// Execute the query
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Display</title>
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
        
        /* Search form styling */
        .search-form {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        /* Table styling */
        .table-container {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .table thead th {
            border: none;
            padding: 1rem;
            font-weight: 500;
        }
        
        .table tbody tr {
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
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
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #e05252);
            border: none;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #e05252, var(--danger));
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #495057, #6c757d);
        }
        
        .btn-light {
            background: white;
            border: 1px solid #e2e8f0;
        }
        
        .btn-light:hover {
            background: var(--light);
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
        
        /* Action buttons in table */
        .action-buttons .btn {
            padding: 0.4rem 0.75rem;
            margin-right: 0.25rem;
            font-size: 0.85rem;
        }
        
        /* Empty state */
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
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
            .search-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-form .form-control {
                margin-bottom: 1rem;
                width: 100%;
            }
            
            .search-form .btn {
                width: 100%;
                margin-left: 0 !important;
                margin-bottom: 0.5rem;
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
                    <a class="nav-link dropdown-toggle active" href="#" id="inventoryDropdown" role="button" data-bs-toggle="dropdown">
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
    <!-- Search Form Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0"><i class="fas fa-search me-2"></i>Search Inventory</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="display_inventory.php" class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    <input type="text" class="form-control" id="product_code" name="product_code" placeholder="Enter Product Code" value="<?php echo isset($_GET['product_code']) ? $_GET['product_code'] : ''; ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <button type="button" class="btn btn-light" onclick="window.location.href='display_inventory.php'">
                        <i class="fas fa-times me-1"></i>Clear
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0"><i class="fas fa-clipboard-list me-2"></i>Inventory List</h3>
            <a href="link.php" class="btn btn-light">
                <i class="fas fa-plus-circle me-1"></i>Add New Item
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-barcode me-1"></i>Product Code</th>
                            <th scope="col"><i class="fas fa-tag me-1"></i>Item Description</th>
                            <th scope="col"><i class="fas fa-box me-1"></i>Packaging</th>
                            <th scope="col"><i class="fas fa-dollar-sign me-1"></i>Cost</th>
                            <th scope="col"><i class="fas fa-tags me-1"></i>Selling Price</th>
                            <th scope="col"><i class="fas fa-cubes me-1"></i>Balance</th>
                            <th scope="col"><i class="fas fa-cogs me-1"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($con, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>
                                    <td>'.$row['Product Code'].'</td>
                                    <td>'.$row['Item Description'].'</td>
                                    <td>'.$row['Packaging'].'</td>
                                    <td>₱'.number_format($row['Cost'], 2).'</td>
                                    <td>₱'.number_format($row['Selling price'], 2).'</td>
                                    <td>'.$row['Balance'].'</td>
                                    <td class="action-buttons">
                                        <a href="update.php?product_code='.$row['Product Code'].'" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <a href="delete.php?product_code='.$row['Product Code'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this item?\')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </a>
                                    </td>
                                </tr>';
                            }
                        } else {
                            echo '<tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h4>No products found</h4>
                                        <p>Try adjusting your search or add a new item.</p>
                                        <a href="link.php" class="btn btn-primary mt-3">
                                            <i class="fas fa-plus-circle me-1"></i>Add New Item
                                        </a>
                                    </div>
                                </td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Hover effect for dropdown menus
    $('.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(300);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(300);
    });
    
    // Add highlight effect for table rows
    $('tbody tr').hover(
        function() {
            $(this).addClass('bg-light');
        },
        function() {
            $(this).removeClass('bg-light');
        }
    );
});
</script>
</body>
</html>