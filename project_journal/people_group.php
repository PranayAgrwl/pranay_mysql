<?php
include_once ('connection.php');
include_once ('navbar.php');

if(isset($_REQUEST['add_group_name']))
{
    $group_name=$_REQUEST['group_name_name'];
    $notes=$_REQUEST['notes_name'];
    $notes2=$_REQUEST['notes2_name'];  

    $sql_srno = "SELECT MAX(group_id) + 1 AS max_group_id FROM people_group";
    $result_srno = $conn->query($sql_srno);
    $row_srno = $result_srno->fetch_assoc();
    $msrno = $row_srno['max_group_id'];
    if ($msrno == 0) {
        $msrno = 1;
    }

    $query = "INSERT INTO people_group (group_id, group_name, group_notes, group_notes2) VALUES ('$msrno','$group_name','$notes','$notes2')";
    
    if ($conn->query($query)) {
        echo "Entry Done !!!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$query1 = "SELECT *, DATE_FORMAT(created_time, '%d/%m/%Y %H:%i:%s') AS created_time1 FROM people_group ORDER BY group_name;";
$result1 = mysqli_query($conn, $query1);


if( $result1 === false) {
    die( print_r( mysqli_errors(), true) );
 }
// else {echo "ok";}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Names</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap file -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><!-- Bootstrap file -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- jQuery file -->
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script><!--  Font Awesome Icons   -->
</head>
<body>

<div class="container mt-3">
    <div class="container">
        <font style="font-size: 40px; font-weight: bold;">Group Names List</font>
        <button type="button" class="btn btn-outline-info" style="float: right;"  data-bs-toggle="modal" data-bs-target="#myModal">Add Group Name</button>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="myModal">
    <div class="modal-dialog">
    <div class="modal-content">

    <!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title">Add Group</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <!-- Modal body -->
    <div class="modal-body">
    <div class="container mt-3">
        
        <form method="post">
            <div class="mb-3 mt-3">
            <label>Group Name:</label>
            <input type="text" class="form-control" placeholder="Enter Group Name" name="group_name_name">
            </div>
            
            <div class="mb-3 mt-3">
            <label>Notes:</label>
            <input type="text" class="form-control" placeholder="Notes" name="notes_name">
            </div>
            <div class="mb-3 mt-3">
            <label>Notes 2:</label>
            <input type="text" class="form-control" placeholder="Notes 2" name="notes2_name">
            </div>
            <button type="submit" name="add_group_name" class="btn btn-primary">Submit</button>
        </form>
        </div>

    </div>

    <!-- Modal footer -->
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
    <th>Group ID</th>
    <th>Group Name</th>
    <th>Notes</th>
    <th>Notes 2</th>
    <th>Created At</th>
    <th>Action</th>
    </tr>
</thead>
<tbody>
<?php            
while ($row = mysqli_fetch_assoc($result1)){
?>
    <tr>
    <td><?php echo $row['group_id'] ?></td>
    <td><?php echo $row['group_name'] ?></td>
    <td><?php echo $row['group_notes'] ?></td>
    <td><?php echo $row['group_notes2'] ?></td>
    <td><?php echo $row['created_time1'] ?></td>
    <td style="display: flex; gap: 10px;">
        <form method="post" action="people_group_edit.php">
            <!-- Hidden input to store the SRNO -->
            <input type="hidden" name="group_id_name" value="<?php echo $row['group_id']; ?>">
            <button type="submit" class="btn btn-outline-info">
                <i class="fa-solid fa-pen-to-square"></i>
            </button>
        </form>
        <form method="post" action="people_group_del.php">
            <!-- Hidden input to store the SRNO -->
            <input type="hidden" name="group_id_name" value="<?php echo $row['group_id']; ?>">
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