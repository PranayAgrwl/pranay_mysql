<?php
include_once('connection.php');
include_once('navbar.php');

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the data from the form
    $trx_id = $_POST['srno_name'];
    $from_ac = $_POST['from_ac_name'];
    $to_ac = $_POST['to_ac_name'];
    $amount = $_POST['amount_name'];
    $notes = $_POST['notes_name'];
    $notes2 = $_POST['notes2_name'];

    // Prepare the SQL query to update the transaction
    $query = "UPDATE ACCOUNTS_TRX SET FROM_AC = '$from_ac', TO_AC = '$to_ac', AMOUNT = '$amount', NOTES = '$notes', NOTES2 = '$notes2' WHERE TRX_ID = '$trx_id'";

    if ($stmt = $conn->prepare($query)) {
        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Updated !!!";
            header("Location: entry_view.php"); // Redirect to the page that lists the transactions
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
