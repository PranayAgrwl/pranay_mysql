<?php
include_once('connection.php');  // Make sure this file contains your DB connection

// Check if master_id is set in the POST request
if (isset($_POST['people_id_name'])) {
    $master_id = $_POST['people_id_name'];

    // Start a transaction to ensure both deletes happen together
    $conn->begin_transaction();

    try {
        // Step 1: Delete the entry from the journal_participants table
        $delete_participants_query = "DELETE FROM journal_participants WHERE journal_id = $master_id";
        if ($stmt = $conn->prepare($delete_participants_query)) {
            if (!$stmt->execute()) {
                throw new Exception("Error deleting participants: " . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare participants deletion query: " . $conn->error);
        }

        // Step 2: Delete the entry from the journal_master table
        $delete_master_query = "DELETE FROM journal_master WHERE master_id = $master_id";
        if ($stmt = $conn->prepare($delete_master_query)) {
            if (!$stmt->execute()) {
                throw new Exception("Error deleting journal entry: " . $stmt->error);
            }
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare journal entry deletion query: " . $conn->error);
        }

        // Commit the transaction if both deletes were successful
        $conn->commit();
        echo "Entry deleted successfully!";
        header("Location: journal_entry_view.php");
        exit;  // Make sure no further code is executed after the redirect

    } catch (Exception $e) {
        // Rollback the transaction if there was an error
        $conn->rollback();
        echo "Failed to delete entry: " . $e->getMessage();
    }
} else {
    echo "No master_id provided.";
}
?>

<a href="journal_entry_view.php">Go Back</a>  <!-- Link to redirect to the display page after deletion -->
