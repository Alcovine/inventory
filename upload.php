<?php
// Include your database connection file
include 'newkonek.php';

function function_alert($message){
  $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
  echo "<script type='text/javascript'>alert('$message');</script>";
}

if(isset($_POST['submit'])){
  // Get the data from POST
  $Product_Code = mysqli_real_escape_string($con, $_POST['Product_Code']);
  $Item_Description = mysqli_real_escape_string($con, $_POST['Item_Description']);
  $Packaging = mysqli_real_escape_string($con, $_POST['Packaging']);
  $Cost = mysqli_real_escape_string($con, $_POST['Cost']);
  $Selling_price = mysqli_real_escape_string($con, $_POST['Selling_price']);
  $Balance = mysqli_real_escape_string($con, $_POST['Balance']); // This will capture 0 as well

  // Check if any field is empty
  if (!empty($Product_Code) && !empty($Item_Description) && !empty($Packaging) && !empty($Cost) && !empty($Selling_price) && !empty($Balance)) {

    // Prepare the SQL query to avoid SQL injection
    $sql = "INSERT INTO inventorymasterfile (`Product Code`, `Item Description`, `Packaging`, `Cost`, `Selling price`, `Balance`) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare statement
    if ($stmt = mysqli_prepare($con, $sql)) {
      // Bind the parameters
      mysqli_stmt_bind_param($stmt, "ssssss", $Product_Code, $Item_Description, $Packaging, $Cost, $Selling_price, $Balance);

      // Execute the query
      if (mysqli_stmt_execute($stmt)) {
        function_alert("Data inserted successfully.");
        header("Location: link.php"); // Redirect to reset the form after successful insert
      } else {
        die("Database error: " . mysqli_error($con));
      }

      // Close the statement
      mysqli_stmt_close($stmt);
    } else {
      die("Prepared statement error: " . mysqli_error($con));
    }
  } else {
    function_alert("All fields are required.");
  }
}
?>
