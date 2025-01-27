<?php
include_once('connection.php');
include_once('navbar.php');

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $people_id = $_POST['people_id_name'];
    $people_name = $_POST['people_name_name'];
    $people_nick = $_POST['people_nick_name'];
    $group_id = $_POST['group_id_name'];
    $notes = $_POST['notes_name'];
    $notes2 = $_POST['notes2_name'];
    
    // Prepare the SQL query to update the record
    $query = "UPDATE people_master SET people_name = '$people_name', people_nick = '$people_nick', group_id = '$group_id', people_notes = '$notes', people_notes2 = '$notes2' WHERE people_id = '$people_id'";
    
    if ($stmt = $conn->prepare($query)) {
        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Updated !!!";
            header("Location: people_master.php");
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
