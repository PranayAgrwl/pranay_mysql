<?php

include_once ('connection.php');
include_once ('navbar.php');

// Check if the form has been submitted and the AC_TYPE_NO is set in POST
if (isset($_POST['srno_name'])) {
    // Get the AC_TYPE_NO value
    $ac_type_no = $_POST['srno_name'];

    // Delete query to remove the account type based on AC_TYPE_NO
    $query = "DELETE FROM ACCOUNTS_AC_TYPES WHERE AC_TYPE_NO = $ac_type_no";

    if ($conn->query($query) === TRUE) {
        echo "Entry Deleted !!!";

        // Redirect to the account types page after deletion
        header("Location: accounts_types.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
