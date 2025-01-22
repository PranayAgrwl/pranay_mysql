<?php
include_once('connection.php');
include_once('navbar.php');

// Check if SRNO is set in POST (form submission)
if (isset($_POST['srno_name'])) {
    $ac_type_no = $_POST['srno_name'];
    
    // Query to fetch the account type details based on AC_TYPE_NO
    $query = "SELECT * FROM ACCOUNTS_AC_TYPES WHERE AC_TYPE_NO = $ac_type_no";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account Type</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>

<div class="container mt-3">
  <h2>Edit Account Type</h2>
  <form method="post" action="accounts_types_upd.php">
    <div class="mb-3 mt-3">
      <label>Account Type No:</label>
      <input type="number" readonly class="form-control" name="srno_name" value="<?php echo $row['AC_TYPE_NO']; ?>">
    </div>
    <div class="mb-3">
      <label>Account Type:</label>
      <input type="text" class="form-control" name="ac_type_name" value="<?php echo $row['AC_TYPE']; ?>">
    </div>
    <div class="mb-3">
      <label>Notes:</label>
      <input type="text" class="form-control" name="notes_name" value="<?php echo $row['NOTES']; ?>">
    </div>
    <div class="mb-3">
      <label>Notes 2:</label>
      <input type="text" class="form-control" name="notes2_name" value="<?php echo $row['NOTES2']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

</body>
</html>
