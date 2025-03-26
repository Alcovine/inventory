<?php
include 'newkonek.php';

// Fetch the highest product code from the database
$sql = "SELECT MAX(`Product Code`) AS max_code FROM inventorymasterfile";
$result = mysqli_query($con, $sql);

// Get the highest product code
$max_code = 0;
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $max_code = (int) $row['max_code'];
}

// Increment the highest product code to generate the next one
$next_code = str_pad($max_code + 1, 5, '0', STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Form</title>
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
        
        /* Inventory form card */
        .inventory-form-card {
            margin-bottom: 2rem;
            background-color: white;
            border-radius: 0.8rem;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        
        .inventory-header {
            padding: 1.5rem;
            text-align: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .inventory-form {
            padding: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
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
    <!-- Inventory Form Card -->
    <div class="inventory-form-card shadow">
        <div class="inventory-header">
            INVENTORY DETAILS
        </div>
        <div class="inventory-form">
            <form method="post" action="upload.php">
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-barcode me-2"></i>Product Code</label>
                    <input type="text" class="form-control" name="Product_Code" value="<?php echo $next_code; ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-tag me-2"></i>Item Description</label>
                    <input type="text" class="form-control" name="Item_Description" placeholder="Enter item description" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-box me-2"></i>Packaging</label>
                    <select class="form-select" name="Packaging" required>
                        <option value="Box">Box</option>
                        <option value="Bot">Bot</option>
                        <option value="Pcs">Pcs</option>
                        <option value="Kgs">Kgs</option>
                        <option value="Ctn">Ctn</option>
                        <option value="Bag">Bag</option>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-dollar-sign me-2"></i>Cost</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" name="Cost" placeholder="0.00" required step="0.01">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-tags me-2"></i>Selling Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" name="Selling_price" placeholder="0.00" required step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-cubes me-2"></i>Balance</label>
                    <input type="text" class="form-control" name="Balance" value="00" readonly>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary" name="submit">
                        <i class="fas fa-save me-2"></i>Submit
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='ForDisplay.php'">
                        <i class="fas fa-home me-2"></i>Menu
                    </button>
                </div>
            </form>
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
    
    // Optional: Add input validation for numeric fields
    $('input[name="Cost"], input[name="Selling_price"]').on('input', function() {
        // Allow only numbers and decimal point
        this.value = this.value.replace(/[^0-9.]/g, '');
        
        // Ensure only one decimal point
        if(this.value.split('.').length > 2) {
            this.value = this.value.substring(0, this.value.lastIndexOf('.'));
        }
    });
});
</script>
</body>
</html>