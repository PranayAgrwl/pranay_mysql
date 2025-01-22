<?php
include_once('connection.php');
include_once('navbar.php');

if (isset($_REQUEST['add_ac_type'])) {
    $ac_type = $_REQUEST['ac_type_name'];
    $notes = $_REQUEST['notes_name'];
    $notes2 = $_REQUEST['notes2_name'];

    // Get the next SRNO (Account Type number)
    $sql_srno = "SELECT MAX(AC_TYPE_NO) + 1 AS MAXSRNO FROM ACCOUNTS_AC_TYPES";
    $result_srno = $conn->query($sql_srno);
    $row_srno = $result_srno->fetch_assoc();
    $msrno = $row_srno['MAXSRNO'];
    if ($msrno == 0) {
        $msrno = 1; // If no rows, start from 1
    }

    // Insert new account type
    $query = "INSERT INTO ACCOUNTS_AC_TYPES (AC_TYPE_NO, AC_TYPE, NOTES, NOTES2) VALUES ('$msrno', '$ac_type', '$notes', '$notes2')";
    
    if ($conn->query($query)) {
        echo "Entry Done !!!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$query1 = "SELECT *, DATE_FORMAT(CREATED_AT_TYPE, '%d/%m/%Y %H:%i:%s') AS DATE1 FROM ACCOUNTS_AC_TYPES ORDER BY AC_TYPE_NO";
$result1 = $conn->query($query1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Types List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container mt-3">
    <div class="container">
        <font style="font-size: 40px; font-weight: bold;">Account Types List</font>
        <button type="button" class="btn btn-outline-info" style="float: right;" data-bs-toggle="modal" data-bs-target="#myModal">Add Account Type</button>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Account Type</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="container mt-3">
                        <form method="post">
                            <div class="mb-3 mt-3">
                                <label>Account Type:</label>
                                <input type="text" class="form-control" placeholder="Enter Account Type" name="ac_type_name" required>
                            </div>

                            <div class="mb-3 mt-3">
                                <label>Notes:</label>
                                <input type="text" class="form-control" placeholder="Notes" name="notes_name" required>
                            </div>

                            <div class="mb-3 mt-3">
                                <label>Notes 2:</label>
                                <input type="text" class="form-control" placeholder="Notes 2" name="notes2_name">
                            </div>

                            <button type="submit" name="add_ac_type" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <br><br>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Ac Type No</th>
                <th>Account Type</th>
                <th>Notes</th>
                <th>Notes 2</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row = $result1->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['AC_TYPE_NO']; ?></td>
                <td><?php echo $row['AC_TYPE']; ?></td>
                <td><?php echo $row['NOTES']; ?></td>
                <td><?php echo $row['NOTES2']; ?></td>
                <td><?php echo $row['DATE1']; ?></td>
                <td style="display: flex; gap: 10px;">
                    <form method="post" action="accounts_types_edit.php">
                        <input type="hidden" name="srno_name" value="<?php echo $row['AC_TYPE_NO']; ?>">
                        <button type="submit" class="btn btn-outline-info">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </form>
                    <form method="post" action="accounts_types_del.php">
                        <input type="hidden" name="srno_name" value="<?php echo $row['AC_TYPE_NO']; ?>">
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
