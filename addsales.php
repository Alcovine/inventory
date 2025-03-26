<?php
session_start();
include 'newkonek.php';

// Store in session if received
if (isset($_GET['so_no'])) {
    $_SESSION['so_number'] = $_GET['so_no'];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Retrieve from session
$so_number = $_SESSION['so_number'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Order Details</title>
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
        
        /* Modal animations */
        @keyframes expandModal {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .modal.fade .modal-dialog {
            transform: scale(0.5);
            opacity: 0;
            transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        }
        
        .modal.show .modal-dialog {
            animation: expandModal 0.3s ease-out forwards;
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
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .actions-bar {
                flex-direction: column;
                align-items: stretch;
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
    <!-- Inventory Form Card -->
    <div class="inventory-form-card shadow">
    <div class="inventory-header">
            <i class="fas fa-box-open me-2"></i>INVENTORY DETAILS
        </div>
        <div class="inventory-form">
            <form method="POST" action="uploadsales.php">
                <!-- Hidden SO Number Field -->
                <input type="hidden" name="SO_No" value="<?= htmlspecialchars($so_number) ?>">
                
                <div class="row mb-3">
                    <label for="Product_Code" class="col-sm-4 col-form-label">
                        <i class="fas fa-barcode me-2"></i>Product Code <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="Product_Code" name="Product_Code" value="" readonly>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productModal">
                            <i class="fas fa-plus me-1"></i>Add
                        </button>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="Item_Description" class="col-sm-4 col-form-label">
                        <i class="fas fa-tag me-2"></i>Item Description <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="Item_Description" name="Item_Description" readonly>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="Packaging" class="col-sm-4 col-form-label">
                        <i class="fas fa-box me-2"></i>Packaging <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="Packaging" name="Packaging" readonly>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="Price" class="col-sm-4 col-form-label">
                        <i class="fas fa-dollar-sign me-2"></i>Price <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="Price" name="Price" required step="0.01">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="Quantity" class="col-sm-4 col-form-label">
                        <i class="fas fa-hashtag me-2"></i>Quantity <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="Quantity" name="Quantity" required>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="Amount" class="col-sm-4 col-form-label">
                        <i class="fas fa-money-bill-wave me-2"></i>Amount <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" class="form-control" id="Amount" name="Amount" readonly step="0.01">
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary" name="submit">
                        <i class="fas fa-save me-2"></i>Submit
                    </button>
                    <a href="sales_order.php" class="btn btn-light ms-2">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="productModalLabel">
                    <i class="fas fa-search me-2"></i>Select a Product
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="productSearch" placeholder="Search products...">
                </div>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><i class="fas fa-barcode me-1"></i>Product Code</th>
                            <th><i class="fas fa-tag me-1"></i>Description</th>
                            <th><i class="fas fa-box me-1"></i>Packaging</th>
                            <th><i class="fas fa-dollar-sign me-1"></i>Price</th>
                            <th><i class="fas fa-cog me-1"></i>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM inventorymasterfile";
                        $result = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['Product Code']}</td>
                                    <td>{$row['Item Description']}</td>
                                    <td>{$row['Packaging']}</td>
                                    <td>₱".number_format($row['Selling price'], 2)."</td>
                                    <td>
                                        <button class='btn btn-primary btn-sm select-product' 
                                            data-code='{$row['Product Code']}' 
                                            data-description='{$row['Item Description']}'
                                            data-packaging='{$row['Packaging']}'
                                            data-price='{$row['Selling price']}'>
                                            <i class='fas fa-check me-1'></i>Select
                                        </button>
                                    </td>
                                  </tr>";
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
<script>
$(document).ready(function(){
    // Hover effect for dropdown menus
    $('.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(300);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(300);
    });
    
    // Auto-calculate amount
    $('#Quantity, #Price').on('input', function () {
        let quantity = parseFloat($('#Quantity').val()) || 0;
        let price = parseFloat($('#Price').val()) || 0;
        $('#Amount').val((quantity * price).toFixed(2));
    });
    
    // Product selection functionality
    $(document).on('click', '.select-product', function () {
        let productCode = $(this).data("code");
        let description = $(this).data("description");
        let packaging = $(this).data("packaging");
        let price = $(this).data("price");

        // Fill in the input fields
        $("#Product_Code").val(productCode);
        $("#Item_Description").val(description);
        $("#Packaging").val(packaging);
        $("#Price").val(price);
        
        // Calculate amount if quantity is already filled
        let quantity = parseFloat($('#Quantity').val()) || 0;
        if(quantity > 0) {
            $('#Amount').val((quantity * price).toFixed(2));
        }

        // Close the modal
        $("#productModal").modal("hide");
    });
    
    // Product search functionality
    $("#productSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>
</body>
</html>