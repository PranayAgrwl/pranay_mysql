<?php
include_once('connection.php');
include_once('navbar.php');

// Check if SRNO is set in POST (form submission)
if (isset($_POST['label_id_name'])) {
    $label_id = $_POST['label_id_name'];
    
    // Query to fetch the account type details based on AC_TYPE_NO
    $query = "SELECT * FROM journal_labels WHERE label_id = $label_id";
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
    <title>Edit Journal Label</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>

<div class="container mt-3">
  <h2>Edit Journal Label</h2>
  <form method="post" action="journal_labels_upd.php">
    <div class="mb-3 mt-3">
      <label>Label ID:</label>
      <input type="number" readonly class="form-control" name="label_id_name" value="<?php echo $row['label_id']; ?>">
    </div>
    <div class="mb-3">
      <label>Label Name:</label>
      <input type="text" class="form-control" name="label_name_name" value="<?php echo $row['label_name']; ?>">
    </div>
    <div class="mb-3">
      <label>Label Notes:</label>
      <input type="text" class="form-control" name="label_notes_name" value="<?php echo $row['label_notes']; ?>">
    </div>
    <div class="mb-3">
      <label>Label Notes 2:</label>
      <input type="text" class="form-control" name="label_notes2_name" value="<?php echo $row['label_notes2']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

</body>
</html>
