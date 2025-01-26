<?php
include_once('connection.php');
include_once('navbar.php');

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $label_id = $_POST['label_id_name'];
    $label_name = $_POST['label_name_name'];
    $notes = $_POST['label_notes_name'];
    $notes2 = $_POST['label_notes2_name'];
    
    // Prepare the SQL query to update the record
    $query = "UPDATE journal_labels SET label_name = '$label_name', label_notes = '$notes', label_notes2 = '$notes2' WHERE label_id = $label_id";
    
    // Prepare the statement to prevent SQL injection
    if ($stmt = $conn->prepare($query)) {
        // Bind the parameters to the query
        
        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Updated !!!";
            header("Location: journal_labels.php"); // Redirect after successful update
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
