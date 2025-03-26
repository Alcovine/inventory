<?php
include 'newkonek.php';

// Check if the product_code is set in the URL
if (isset($_GET['product_code'])) {
    $product_code = $_GET['product_code'];

    // Fetch product details from the database
    $sql = "SELECT * FROM `inventorymasterfile` WHERE `Product Code` = '$product_code'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the product data
        $row = mysqli_fetch_assoc($result);
        $item_description = $row['Item Description'];
        $packaging = $row['Packaging'];
        $cost = $row['Cost'];
        $selling_price = $row['Selling price'];
        $balance = $row['Balance'];
    } else {
        // If no product is found, show an error message
        echo "Product not found!";
        exit;
    }
} else {
    // If no product_code is passed, redirect or show an error
    echo "Invalid request.";
    exit;
}

// Handle the form submission for updating the inventory item
if (isset($_POST['submit'])) {
    $item_description = $_POST['Item_Description'];
    $packaging = $_POST['Packaging'];
    $cost = $_POST['Cost'];
    $selling_price = $_POST['Selling_Price'];
    $balance = $_POST['Balance'];

    // Update the product in the database
    $sql = "UPDATE inventorymasterfile 
            SET Item Description = '$item_description', 
                Packaging = '$packaging', 
                Cost = '$cost', 
                Selling price = '$selling_price', 
                Balance = '$balance'
            WHERE Product Code = '$product_code'";

    if (mysqli_query($con, $sql)) {
        echo "Product updated successfully!";
        // Redirect back to the inventory display page after updating
        header('Location: display_inventory.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Inventory Item</title>
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
        
        .form-select {
            border-radius: 0.5rem;
            padding: 0.65rem 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
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
            padding: 1.2rem 1.5rem;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-header .close {
            color: white;
            opacity: 0.8;
            transition: all 0.3s;
        }
        
        .modal-header .close:hover {
            opacity: 1;
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
            .content-wrapper {
                padding: 0 0.5rem;
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
    <!-- Edit Item Form Card -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0"><i class="fas fa-edit me-2"></i>Edit Inventory Item</h3>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="mb-3 row">
                            <label for="Product_Code" class="col-sm-4 col-form-label">
                                <i class="fas fa-barcode me-1"></i>Product Code
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="Product_Code" name="Product_Code" value="<?php echo htmlspecialchars($product_code); ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="Item_Description" class="col-sm-4 col-form-label">
                                <i class="fas fa-tag me-1"></i>Item Description <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="Item_Description" name="Item_Description" value="<?php echo htmlspecialchars($item_description); ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="Packaging" class="col-sm-4 col-form-label">
                                <i class="fas fa-box me-1"></i>Packaging <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-select" id="Packaging" name="Packaging" required>
                                    <option value="Box" <?php echo ($packaging == 'Box') ? 'selected' : ''; ?>>Box</option>
                                    <option value="Bot" <?php echo ($packaging == 'Bot') ? 'selected' : ''; ?>>Bot</option>
                                    <option value="Pcs" <?php echo ($packaging == 'Pcs') ? 'selected' : ''; ?>>Pcs</option>
                                    <option value="Kgs" <?php echo ($packaging == 'Kgs') ? 'selected' : ''; ?>>Kgs</option>
                                    <option value="Ctn" <?php echo ($packaging == 'Ctn') ? 'selected' : ''; ?>>Ctn</option>
                                    <option value="Bag" <?php echo ($packaging == 'Bag') ? 'selected' : ''; ?>>Bag</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="Cost" class="col-sm-4 col-form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Cost <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="Cost" name="Cost" value="<?php echo htmlspecialchars($cost); ?>" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="Selling_Price" class="col-sm-4 col-form-label">
                                <i class="fas fa-tag me-1"></i>Selling Price <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="Selling_Price" name="Selling_Price" value="<?php echo htmlspecialchars($selling_price); ?>" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="Balance" class="col-sm-4 col-form-label">
                                <i class="fas fa-cubes me-1"></i>Balance <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="Balance" name="Balance" value="<?php echo htmlspecialchars($balance); ?>" readonly>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary" name="submit">
                                <i class="fas fa-save me-1"></i>Update Item
                            </button>
                            <a href="display_inventory.php" class="btn btn-light ms-2">
                                <i class="fas fa-arrow-left me-1"></i>Back to Inventory
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
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