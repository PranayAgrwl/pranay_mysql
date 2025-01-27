<?php

include_once('connection.php');
include_once('navbar.php');

// Check if the form has been submitted and the SRNO is set in POST
if (isset($_POST['people_id_name'])) {
    // Get the SRNO value
    $people_id = $_POST['people_id_name'];
    
    // Prepare the DELETE query to remove the account entry
    $query = "DELETE FROM people_master WHERE people_id = $people_id";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {

        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Deleted !!!";

            header("Location: people_master.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare the query: " . $conn->error;
    }
}
?>
