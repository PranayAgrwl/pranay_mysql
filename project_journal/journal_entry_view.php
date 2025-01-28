<?php

include_once ('connection.php');
include_once ('navbar.php');


$query1 = "SELECT 
    jm.master_id AS master_id,
    jm.created_time AS created_time,
    DATE_FORMAT(jm.created_time, '%d %M %Y %H:%i:%s') AS formatted_created_time,
    jm.label_id,
    jl.label_name AS label_name,
    jm.title AS title,
    jm.details AS details,
    jm.notes AS notes,
    jm.notes2 AS notes2,
    jm.time_start AS time_start,
    jm.time_end AS time_end,
    GROUP_CONCAT(pm.people_name ORDER BY pm.people_name ASC) AS participant_name1,

    GROUP_CONCAT(CONCAT(' ',pm.people_nick, ' ', pg.group_name, '') ORDER BY pm.people_name ASC) AS participant_name
FROM 
    journal_master jm
LEFT JOIN 
    journal_labels jl ON jm.label_id = jl.label_id
LEFT JOIN 
    journal_participants jp ON jm.master_id = jp.journal_id
LEFT JOIN 
    people_master pm ON jp.participant_name = pm.people_id
LEFT JOIN 
    people_group pg ON pm.group_id = pg.group_id
GROUP BY 
    jm.master_id
ORDER BY 
    jm.created_time DESC;

";
$result1 = $conn->query($query1);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Journal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>

<div class="container mt-3">
    <div class="container">
        <font style="font-size: 40px; font-weight: bold;">View Journal</font>
        <button class="btn btn-outline-info" style="float: right;"   onclick="window.location.href='journal_entry.php'">Create Journal Entry</button>
    </div>
    
    <br><br>    
    <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Master ID</th>
        <th>Created Time</th>
        <th>Label</th>
        <th>Title</th>
        <th>View Details</th>
        <th>Notes</th>
        <th>Participants</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php            
    while ($row1 = $result1->fetch_assoc()) {
    ?>
      <tr>
        <td><?php echo $row1['master_id']; ?></td>
        <td><?php echo $row1['formatted_created_time']; ?></td>
        <td><?php echo $row1['label_name']; ?></td>
        <td><?php echo $row1['title']; ?></td>
        <td><?php echo $row1['details']; ?></td>
        <td><?php echo $row1['notes']; ?></td>
        <td><?php echo $row1['participant_name']; ?></td>
        <td style="display: flex; gap: 10px;">
            <form method="post" action="journal_entry_edit.php">
                <input type="hidden" name="people_id_name" value="<?php echo $row1['master_id']; ?>">
                <button type="submit" class="btn btn-outline-info">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </form>
            <form method="post" action="journal_entry_del.php">
                <input type="hidden" name="people_id_name" value="<?php echo $row1['master_id']; ?>">
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
