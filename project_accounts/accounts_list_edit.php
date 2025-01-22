<?php
include_once('connection.php');
include_once('navbar.php');

// Check if the form is submitted and SRNO is set
if (isset($_POST['srno_name'])) {
    $ac_no = $_POST['srno_name'];
    
    // Prepare the query to get the account details based on SRNO
    $query = "SELECT * FROM ACCOUNTS_AC_LIST WHERE AC_NO = $ac_no";
    
    if ($stmt = $conn->prepare($query)) {
        
        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch the result if found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "Error: Record not found!";
            exit();
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to prepare the query: " . $conn->error;
    }
}

// Fetch account types for the dropdown
$query1 = "SELECT * FROM ACCOUNTS_AC_TYPES";
$result1 = $conn->query($query1);

if (!$result1) {
    die("Error fetching account types: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>

<div class="container mt-3">
  <h2>Edit Account</h2>
  <form method="post" action="accounts_list_upd.php">
    <div class="mb-3 mt-3">
      <label>AC No:</label>
      <input type="number" readonly class="form-control" name="srno_name" value="<?php echo $row['AC_NO'] ?>">
    </div>
    <div class="mb-3">
      <label>Account Name:</label>
      <input type="text" class="form-control" name="ac_name_name" value="<?php echo $row['AC_NAME'] ?>">
    </div>
    <div class="mb-3">
      <label>Account Type:</label>
      <select name="ac_type_name" class="form-control">
          <option value="">Select Account Type</option>
          <?php while ($row1 = $result1->fetch_assoc()) { ?>
              <option value="<?php echo $row1['AC_TYPE_NO']; ?>" <?php echo $row1['AC_TYPE_NO'] == $row['AC_TYPE'] ? 'selected' : ''; ?>>
                  <?php echo $row1['AC_TYPE']; ?>
              </option>
          <?php } ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Notes:</label>
      <input type="text" class="form-control" name="notes_name" value="<?php echo $row['NOTES'] ?>">
    </div>
    <div class="mb-3">
      <label>Notes 2:</label>
      <input type="text" class="form-control" name="notes2_name" value="<?php echo $row['NOTES2'] ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

</body>
</html>
