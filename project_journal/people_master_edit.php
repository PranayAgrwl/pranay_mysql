<?php
include_once('connection.php');
include_once('navbar.php');

// Check if the form is submitted and SRNO is set
if (isset($_POST['people_id_name'])) {
    $people_id = $_POST['people_id_name'];
    
    // Prepare the query to get the account details based on SRNO
    $query = "SELECT * FROM people_master WHERE people_id = $people_id";
    
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
$query1 = "SELECT * FROM people_group";
$result1 = $conn->query($query1);

if (!$result1) {
    die("Error fetching people group: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit People</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>

<div class="container mt-3">
  <h2>Edit People</h2>
  <form method="post" action="people_master_upd.php">
    <div class="mb-3 mt-3">
      <label>People ID:</label>
      <input type="number" readonly class="form-control" name="people_id_name" value="<?php echo $row['people_id'] ?>">
    </div>
    <div class="mb-3">
      <label>People Name:</label>
      <input type="text" class="form-control" name="people_name_name" value="<?php echo $row['people_name'] ?>">
    </div>
    <div class="mb-3">
      <label>Nick Name:</label>
      <input type="text" class="form-control" name="people_nick_name" value="<?php echo $row['people_nick'] ?>">
    </div>
    <div class="mb-3">
      <label>People Group:</label>
      <select name="group_id_name" class="form-control">
          <option value="">Group Name</option>
          <?php while ($row1 = $result1->fetch_assoc()) { ?>
              <option value="<?php echo $row1['group_id']; ?>" 
              <?php echo $row1['group_id'] == $row['group_id'] ? 'selected' : ''; ?>>
                  <?php echo $row1['group_name']; ?>
              </option>
          <?php } ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Notes:</label>
      <input type="text" class="form-control" name="notes_name" value="<?php echo $row['people_notes'] ?>">
    </div>
    <div class="mb-3">
      <label>Notes 2:</label>
      <input type="text" class="form-control" name="notes2_name" value="<?php echo $row['people_notes2'] ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</div>

<br>

</body>
</html>
