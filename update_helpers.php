<?php
// update_helpers.php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['helper_id'];
    $source_table = $_POST['source_table'];
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $status = $_POST['status'];

    // Sanitize contact by removing non-digits first
    $contact = preg_replace('/\D/', '', $contact);
    
    // Validate contact number
    if (!preg_match('/^09\d{9}$/', $contact)) {
        echo "<script>
        alert('Invalid contact number. Please enter a number starting with 09 and exactly 11 digits.');
        window.location.href = 'helpers.php';
        </script>";
        exit;
    }

    // Determine the ID column name based on the source table
    $id_column = $source_table . "_id";

    $query = "UPDATE $source_table SET fullname=?, contact=?, status=? WHERE $id_column=?";
    $stmt = mysqli_prepare($conn, $query);
    
    // Using 'sssi' for string parameters and integer ID
    mysqli_stmt_bind_param($stmt, "sssi", $fullname, $contact, $status, $id);

    if (mysqli_stmt_execute($stmt)){
        echo "<script>
        alert('Helper details successfully updated!');
        window.location.href = 'helpers.php';
        </script>";
    } else {
        echo "<script>
        alert('Error updating record: " . mysqli_error($conn) . "');
        window.location.href = 'helpers.php';
        </script>";
    }
    
    mysqli_stmt_close($stmt);
}
?>