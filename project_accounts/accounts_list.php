<?php

include_once ('connection.php');
include_once ('navbar.php');

// Check if the form has been submitted to add a new account
if (isset($_REQUEST['add_ac_name'])) {
    $ac_name = $_REQUEST['ac_name_name'];
    $ac_type = $_REQUEST['ac_type_name'];
    $notes = $_REQUEST['notes_name'];
    $notes2 = $_REQUEST['notes2_name'];

    // // Get the next SRNO (Account Name number)
    // $q = "SELECT MAX(AC_NO) + 1 AS MAXSRNO FROM ACCOUNTS_AC_LIST";
    // $r = $conn->query($q);
    // $row_srno = $r->fetch_assoc();
    // $msrno = $row_srno['MAXSRNO'];
    // if ($msrno == 0) {
    //     $msrno = 1;
    // }


    $q = "SELECT IFNULL(MAX(AC_NO), 0) + 1 AS MAXSRNO FROM ACCOUNTS_AC_LIST";
    $r = mysqli_query($conn, $q);
    $row_srno = mysqli_fetch_assoc($r);

    $msrno = $row_srno['MAXSRNO'];
    if ($msrno == 0) {
        $msrno = 1;
    }


    // Insert new account name
    $query = "INSERT INTO ACCOUNTS_AC_LIST (AC_NO, AC_NAME, AC_TYPE, NOTES, NOTES2) 
              VALUES ('$msrno', '$ac_name', '$ac_type', '$notes', '$notes2')";

    if ($conn->query($query)) {
        echo "Entry Done !!!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Get the list of account types
$query1 = "SELECT * FROM ACCOUNTS_AC_TYPES";
$result1 = $conn->query($query1);

// Get the list of accounts with types
$query2 = "SELECT ACCOUNTS_AC_TYPES.AC_TYPE AS PRANAY_TYPE, ACCOUNTS_AC_LIST.*, 
                  DATE_FORMAT(ACCOUNTS_AC_LIST.CREATED_AT, '%d/%m/%Y %H:%i:%s') AS DATE1 
           FROM ACCOUNTS_AC_LIST
           INNER JOIN ACCOUNTS_AC_TYPES 
           ON ACCOUNTS_AC_TYPES.AC_TYPE_NO = ACCOUNTS_AC_LIST.AC_TYPE
           ORDER BY ACCOUNTS_AC_LIST.AC_NAME";
$result2 = $conn->query($query2);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="container mt-3">
        <div class="container">
            <font style="font-size: 40px; font-weight: bold;">Accounts List</font>
            <button type="button" class="btn btn-outline-info" style="float: right;"  data-bs-toggle="modal" data-bs-target="#myModal">Add Account</button>
        </div>

        <!-- The Modal -->
        <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add Account</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
        <div class="container mt-3">
            
            <form method="post">
                <div class="mb-3 mt-3">
                <label>Account Name:</label>
                <input type="text" class="form-control" placeholder="Enter Account Name" name="ac_name_name" required>
                </div>
                <div class="mb-3">
                <label>Account Type:</label><br>
                    <select name="ac_type_name" required class="form-control"> 
                        <option value=""></option>    
                        <?php while ($row = $result1->fetch_assoc()) { ?>
                            <option value="<?php echo $row['AC_TYPE_NO']; ?>">
                                <?php echo $row['AC_TYPE']; ?> 
                            </option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="mb-3 mt-3">
                <label>Notes:</label>
                <input type="text" class="form-control" placeholder="Notes" name="notes_name" required>
                </div>
                <div class="mb-3 mt-3">
                <label>Notes 2:</label>
                <input type="text" class="form-control" placeholder="Notes 2" name="notes2_name">
                </div>
                <button type="submit" name="add_ac_name" class="btn btn-primary">Submit</button>
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
        <th>Account No</th>
        <th>Account Name</th>
        <th>Account Type</th>
        <th>Notes</th>
        <th>Notes 2</th>
        <th>Created At</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php            
    while ($row1 = $result2->fetch_assoc()) {
    ?>
      <tr>
        <td><?php echo $row1['AC_NO']; ?></td>
        <td><?php echo $row1['AC_NAME']; ?></td>
        <td><?php echo $row1['PRANAY_TYPE']; ?></td>
        <td><?php echo $row1['NOTES']; ?></td>
        <td><?php echo $row1['NOTES2']; ?></td>
        <td><?php echo $row1['DATE1']; ?></td>
        <td style="display: flex; gap: 10px;">
            <form method="post" action="accounts_list_edit.php">
                <input type="hidden" name="srno_name" value="<?php echo $row1['AC_NO']; ?>">
                <button type="submit" class="btn btn-outline-info">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </form>
            <form method="post" action="accounts_list_del.php">
                <input type="hidden" name="srno_name" value="<?php echo $row1['AC_NO']; ?>">
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
