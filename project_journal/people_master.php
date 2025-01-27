<?php

include_once ('connection.php');
include_once ('navbar.php');

// Check if the form has been submitted to add a new account
if (isset($_REQUEST['add_people_button'])) {
    $people_name = $_REQUEST['people_name_name'];
    $group_id = $_REQUEST['group_id_name'];
    $people_nick = $_REQUEST['people_nick_name'];
    $notes = $_REQUEST['notes_name'];
    $notes2 = $_REQUEST['notes2_name'];

    $q = "SELECT IFNULL(MAX(people_id), 0) + 1 AS max_people_id FROM people_master";
    $r = mysqli_query($conn, $q);
    $row_srno = mysqli_fetch_assoc($r);

    $msrno = $row_srno['max_people_id'];
    if ($msrno == 0) {
        $msrno = 1;
    }


    // Insert new account name
    $query = "INSERT INTO people_master (people_id, people_name, group_id, people_nick, people_notes, people_notes2) 
              VALUES ('$msrno', '$people_name', '$group_id', '$people_nick', '$notes', '$notes2')";

    if ($conn->query($query)) {
        echo "Entry Done !!!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Get the list of account types
$query1 = "SELECT * FROM people_group ORDER BY group_name";
$result1 = $conn->query($query1);

// Get the list of accounts with types
$query2 = "SELECT people_group.group_name AS pranay_group, people_master.*, 
                  CONCAT(people_master.people_nick, ' ', people_group.group_name) AS complete_name,
                  DATE_FORMAT(people_master.created_time, '%d/%m/%Y %H:%i:%s') AS date1 
           FROM people_master
           INNER JOIN people_group 
           ON people_group.group_id = people_master.group_id
           ORDER BY people_master.people_name";
$result2 = $conn->query($query2);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>People List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="container mt-3">
        <div class="container">
            <font style="font-size: 40px; font-weight: bold;">People List</font>
            <button type="button" class="btn btn-outline-info" style="float: right;"  data-bs-toggle="modal" data-bs-target="#myModal">Add People</button>
        </div>

        <!-- The Modal -->
        <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add People</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
        <div class="container mt-3">
            
            <form method="post">
                <div class="mb-3 mt-3">
                <label>People Name:</label>
                <input type="text" class="form-control" placeholder="Enter People Name" name="people_name_name" required>
                </div>
                <div class="mb-3">
                <label>People Group:</label><br>
                    <select name="group_id_name" required class="form-control"> 
                        <option value=""></option>    
                        <?php while ($row = $result1->fetch_assoc()) { ?>
                            <option value="<?php echo $row['group_id']; ?>">
                                <?php echo $row['group_name']; ?> 
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3 mt-3">
                <label>People Nick Name:</label>
                <input type="text" class="form-control" placeholder="Enter Nick Name" name="people_nick_name">
                </div>
                <div class="mb-3 mt-3">
                <label>Notes:</label>
                <input type="text" class="form-control" placeholder="Notes" name="notes_name">
                </div>
                <div class="mb-3 mt-3">
                <label>Notes 2:</label>
                <input type="text" class="form-control" placeholder="Notes 2" name="notes2_name">
                </div>
                <button type="submit" name="add_people_button" class="btn btn-primary">Submit</button>
            </form>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>

        </div>
        </div>
        </div>

    
    <br><br>    
    <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>People ID</th>
        <th>People Name</th>
        <th>Nick Name</th>
        <th>Complete Name</th>
        <th>People Group</th>
        <th>Notes</th>
        <th>Notes 2</th>
        <th>Created Time</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php            
    while ($row1 = $result2->fetch_assoc()) {
    ?>
      <tr>
        <td><?php echo $row1['people_id']; ?></td>
        <td><?php echo $row1['people_name']; ?></td>
        <td><?php echo $row1['people_nick']; ?></td>
        <td><?php echo $row1['complete_name']; ?></td>
        <td><?php echo $row1['pranay_group']; ?></td>
        <td><?php echo $row1['people_notes']; ?></td>
        <td><?php echo $row1['people_notes2']; ?></td>
        <td><?php echo $row1['date1']; ?></td>
        <td style="display: flex; gap: 10px;">
            <form method="post" action="people_master_edit.php">
                <input type="hidden" name="people_id_name" value="<?php echo $row1['people_id']; ?>">
                <button type="submit" class="btn btn-outline-info">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </form>
            <form method="post" action="people_master_del.php">
                <input type="hidden" name="people_id_name" value="<?php echo $row1['people_id']; ?>">
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this entry?')">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </td>
      </tr>
    <?php
    }
    ?>
    </tbody>
    </table>

</div>

</body>
</html>
