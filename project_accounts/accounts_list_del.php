<?php

include_once('connection.php');
include_once('navbar.php');

// Check if the form has been submitted and the SRNO is set in POST
if (isset($_POST['srno_name'])) {
    // Get the SRNO value
    $ac_no = $_POST['srno_name'];
    
    // Prepare the DELETE query to remove the account entry
    $query = "DELETE FROM ACCOUNTS_AC_LIST WHERE AC_NO = $ac_no";

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {

        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Deleted !!!";

            header("Location: accounts_list.php");
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
