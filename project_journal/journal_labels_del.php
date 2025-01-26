<?php

include_once ('connection.php');
include_once ('navbar.php');

// Check if the form has been submitted and the AC_TYPE_NO is set in POST
if (isset($_POST['label_id_name'])) {
    // Get the AC_TYPE_NO value
    $label_id = $_POST['label_id_name'];

    // Delete query to remove the account type based on AC_TYPE_NO
    $query = "DELETE FROM journal_labels WHERE label_id = $label_id";

    if ($conn->query($query) === TRUE) {
        echo "Entry Deleted !!!";

        // Redirect to the account types page after deletion
        header("Location: journal_labels.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
