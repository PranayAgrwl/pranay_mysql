<?php

include_once('connection.php');
include_once('navbar.php');

if (isset($_REQUEST['save_entry'])) {
    // Get the form data
    $from_ac = $_REQUEST['from_ac_name'];
    $to_ac = $_REQUEST['to_ac_name'];
    $amount = $_REQUEST['amount_name'];
    $notes = $_REQUEST['notes_name'];
    $notes2 = $_REQUEST['notes2_name'];

    // Get the next available SRNO
    $query_srno = "SELECT MAX(TRX_ID) + 1 AS MAXSRNO FROM ACCOUNTS_TRX";
    $result_srno = $conn->query($query_srno);
    $row_srno = $result_srno->fetch_assoc();
    $msrno = $row_srno['MAXSRNO'] ?? 1;

    // Prepare the insert query
    $query = "INSERT INTO ACCOUNTS_TRX (TRX_ID, FROM_AC, TO_AC, AMOUNT, NOTES, NOTES2) 
              VALUES ($msrno, '$from_ac', '$to_ac', '$amount', '$notes', '$notes2')";
    
    if ($stmt = $conn->prepare($query)) {
        // Execute the query
        if ($stmt->execute()) {
            echo "Entry Done !!!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare the query: " . $conn->error;
    }
}

// Get accounts list for "from" and "to" account selection
$query1 = "SELECT AC_NAME, AC_NO FROM ACCOUNTS_AC_LIST ORDER BY AC_NAME";
$result1 = $conn->query($query1);

$query2 = "SELECT AC_NAME, AC_NO FROM ACCOUNTS_AC_LIST ORDER BY AC_NAME";
$result2 = $conn->query($query2);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>

    <div class="container mt-3">
        <font style="font-size: 40px; font-weight: bold;">Create Entry</font><br><br>
        <form method="post">

            <div class="mb-3">
                <label>From Account:</label><br>
                <select name="from_ac_name" class="form-control">
                    <option value=""></option>
                    <?php while ($row1 = $result1->fetch_assoc()) { ?>
                        <option value="<?php echo $row1['AC_NO']; ?>">
                            <?php echo $row1['AC_NAME']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label>To Account:</label><br>
                <select name="to_ac_name" class="form-control">
                    <option value=""></option>
                    <?php while ($row2 = $result2->fetch_assoc()) { ?>
                        <option value="<?php echo $row2['AC_NO']; ?>">
                            <?php echo $row2['AC_NAME']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3 mt-3">
                <label>Amount:</label>
                <input type="number" step="0.01" min="0" class="form-control" name="amount_name" required>
            </div>

            <div class="mb-3 mt-3">
                <label>Notes:</label>
                <input type="text" class="form-control" name="notes_name">
            </div>

            <div class="mb-3 mt-3">
                <label>Notes2:</label>
                <input type="text" class="form-control" name="notes2_name">
            </div>

            <button type="submit" name="save_entry" class="btn btn-primary">Save</button>
        </form>
    </div>

    <br>

</body>

</html>
