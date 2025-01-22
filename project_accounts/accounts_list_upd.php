<?php
include_once('connection.php');
include_once('navbar.php');

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $ac_no = $_POST['srno_name'];
    $ac_name = $_POST['ac_name_name'];
    $ac_type = $_POST['ac_type_name'];
    $notes = $_POST['notes_name'];
    $notes2 = $_POST['notes2_name'];
    
    // Prepare the SQL query to update the record
    $query = "UPDATE ACCOUNTS_AC_LIST SET AC_NAME = '$ac_name', AC_TYPE = '$ac_type', NOTES = '$notes', NOTES2 = '$notes2' WHERE AC_NO = '$ac_no'";
    
    if ($stmt = $conn->prepare($query)) {
        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Updated !!!";
            header("Location: accounts_list.php");
            exit();
        } else {
            echo "Error: " . $stmt->error; // Show error if update fails
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare the query: " . $conn->error;
    }
}
?>
