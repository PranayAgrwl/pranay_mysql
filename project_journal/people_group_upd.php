<?php
include_once('connection.php');
include_once('navbar.php');

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $group_id = $_POST['group_id_name'];
    $group_name = $_POST['group_name_name'];
    $notes = $_POST['group_notes_name'];
    $notes2 = $_POST['group_notes2_name'];
    
    // Prepare the SQL query to update the record
    $query = "UPDATE people_group SET group_name = '$group_name', group_notes = '$notes', group_notes2 = '$notes2' WHERE group_id = $group_id";
    
    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters to the query
        
        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Updated !!!";
            header("Location: people_group.php"); // Redirect after successful update
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
