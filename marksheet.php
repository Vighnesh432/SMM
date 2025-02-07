<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "studentmarksheet");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Student Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $name = $_POST['name'] ?? '';
    $roll_no = $_POST['roll_no'] ?? '';
    $atc1 = $_POST['atc1'] ?? 0;
    $atc2 = $_POST['atc2'] ?? 0;
    $atc3 = $_POST['atc3'] ?? 0;
    $ml1 = $_POST['ml1'] ?? 0;
    $ml2 = $_POST['ml2'] ?? 0;
    $ml3 = $_POST['ml3'] ?? 0;
    $dbms1 = $_POST['dbms1'] ?? 0;
    $dbms2 = $_POST['dbms2'] ?? 0;
    $dbms3 = $_POST['dbms3'] ?? 0;
    $cn1 = $_POST['cn1'] ?? 0;
    $cn2 = $_POST['cn2'] ?? 0;
    $cn3 = $_POST['cn3'] ?? 0;

    // Calculate averages
    $atc_avg = ($atc1 + $atc2 + $atc3) / 3;
    $ml_avg = ($ml1 + $ml2 + $ml3) / 3;
    $dbms_avg = ($dbms1 + $dbms2 + $dbms3) / 3;
    $cn_avg = ($cn1 + $cn2 + $cn3) / 3;

    // Insert student data into database
    $sql = "INSERT INTO students (name, roll_no, atc1, atc2, atc3, ml1, ml2, ml3, dbms1, dbms2, dbms3, cn1, cn2, cn3, atc_avg, ml_avg, dbms_avg, cn_avg) 
            VALUES ('$name', '$roll_no', $atc1, $atc2, $atc3, $ml1, $ml2, $ml3, $dbms1, $dbms2, $dbms3, $cn1, $cn2, $cn3, $atc_avg, $ml_avg, $dbms_avg, $cn_avg)";

    if ($conn->query($sql) === TRUE) {
        $message = "Student added successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Delete Student Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_student'])) {
    $id = $_POST['id'] ?? 0;
    $sql = "DELETE FROM students WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Student deleted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Reset Database Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_data'])) {
    $sql = "TRUNCATE TABLE students";
    if ($conn->query($sql) === TRUE) {
        $message = "All records deleted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch All Records
$students = [];
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $students = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marksheet Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        .form-container {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container input, .form-container button {
            margin: 5px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-container button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
        .message {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Student Marksheet Management</h1>
    <?php if (isset($message)): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" class="form-container">
        <input type="text" name="name" placeholder="Student Name" required>
        <input type="text" name="roll_no" placeholder="Roll No" required>
        <input type="number" name="atc1" placeholder="ATC Internal 1" required>
        <input type="number" name="atc2" placeholder="ATC Internal 2" required>
        <input type="number" name="atc3" placeholder="ATC Internal 3" required>
        <input type="number" name="ml1" placeholder="ML Internal 1" required>
        <input type="number" name="ml2" placeholder="ML Internal 2" required>
        <input type="number" name="ml3" placeholder="ML Internal 3" required>
        <input type="number" name="dbms1" placeholder="DBMS Internal 1" required>
        <input type="number" name="dbms2" placeholder="DBMS Internal 2" required>
        <input type="number" name="dbms3" placeholder="DBMS Internal 3" required>
        <input type="number" name="cn1" placeholder="CN Internal 1" required>
        <input type="number" name="cn2" placeholder="CN Internal 2" required>
        <input type="number" name="cn3" placeholder="CN Internal 3" required>
        <button type="submit" name="add_student">Add Student</button>
        <button type="submit" name="reset_data">Reset Data</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Roll No</th>
                <th>ATC Marks</th>
                <th>ML Marks</th>
                <th>DBMS Marks</th>
                <th>CN Marks</th>
                <th>ATC Avg</th>
                <th>ML Avg</th>
                <th>DBMS Avg</th>
                <th>CN Avg</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= $student['id'] ?></td>
                        <td><?= $student['name'] ?></td>
                        <td><?= $student['roll_no'] ?></td>
                        <td><?= $student['atc1'] . ", " . $student['atc2'] . ", " . $student['atc3'] ?></td>
                        <td><?= $student['ml1'] . ", " . $student['ml2'] . ", " . $student['ml3'] ?></td>
                        <td><?= $student['dbms1'] . ", " . $student['dbms2'] . ", " . $student['dbms3'] ?></td>
                        <td><?= $student['cn1'] . ", " . $student['cn2'] . ", " . $student['cn3'] ?></td>
                        <td><?= number_format($student['atc_avg'], 2) ?></td>
                        <td><?= number_format($student['ml_avg'], 2) ?></td>
                        <td><?= number_format($student['dbms_avg'], 2) ?></td>
                        <td><?= number_format($student['cn_avg'], 2) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $student['id'] ?>">
                                <button type="submit" name="delete_student">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12">No records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
