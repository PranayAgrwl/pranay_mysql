<?php

include_once ('connection.php');
include_once ('navbar.php');

// Check if the form has been submitted and the AC_TYPE_NO is set in POST
if (isset($_POST['group_id_name'])) {
    // Get the AC_TYPE_NO value
    $group_id = $_POST['group_id_name'];

    // Delete query to remove the account type based on AC_TYPE_NO
    $query = "DELETE FROM people_group WHERE group_id = $group_id";

    if ($conn->query($query) === TRUE) {
        echo "Entry Deleted !!!";

        // Redirect to the account types page after deletion
        header("Location: people_group.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
