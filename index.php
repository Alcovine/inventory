<?php
$average = null;
$remarks = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lrn = $_POST['lrn'];
    $name = $_POST['name'];
    $gradeLevel = $_POST['gradeLevel'];
    $section = $_POST['section'];
    
    $grades = [
        (float)$_POST['grade1'],
        (float)$_POST['grade2'],
        (float)$_POST['grade3'],
        (float)$_POST['grade4']
    ];
    
    $average = array_sum($grades) / count($grades);
    $remarks = $average < 75 ? "Failed" : "Passed";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Student Information Form</h1>
        <form method="POST" action="">
            <label for="lrn">LRN:</label>
            <input type="text" id="lrn" name="lrn" required>

            <label for="name">Name of the Student:</label>
            <input type="text" id="name" name="name" required>

            <label for="gradeLevel">Grade Level:</label>
            <input type="text" id="gradeLevel" name="gradeLevel" required>

            <label for="section">Section:</label>
            <input type="text" id="section" name="section" required>

            <label for="grade1">1st Grade:</label>
            <input type="number" id="grade1" name="grade1" required>

            <label for="grade2">2nd Grade:</label>
            <input type="number" id="grade2" name="grade2" required>

            <label for="grade3">3rd Grade:</label>
            <input type="number" id="grade3" name="grade3" required>

            <label for="grade4">4th Grade:</label>
            <input type="number" id="grade4" name="grade4" required>

            <button type="submit">Submit</button>
        </form>

        <?php if ($average !== null): ?>
            <div class="results">
                <h2>Student Data</h2>
                <p><strong>LRN:</strong> <?= htmlspecialchars($lrn) ?></p>
                <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
                <p><strong>Grade Level:</strong> <?= htmlspecialchars($gradeLevel) ?></p>
                <p><strong>Section:</strong> <?= htmlspecialchars($section) ?></p>
                <p><strong>Average Grade:</strong> <?= number_format($average, 2) ?></p>
                <p><strong>Remarks:</strong> <?= $remarks ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

