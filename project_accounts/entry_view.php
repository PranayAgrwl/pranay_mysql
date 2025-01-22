<?php

include_once ('connection.php');
include_once ('navbar.php');

// MySQL query to fetch recent transactions with necessary joins
$query1 = " SELECT 
  ACCOUNTS_TRX.*, 
  ACCOUNTS_AC_LIST.AC_NAME AS FROM_AC, 
  ACCOUNTS_AC_LIST1.AC_NAME AS TO_AC, 
  DATE_FORMAT(ACCOUNTS_TRX.TRX_DATE, '%d/%m/%Y %H:%i:%s') AS DATE1 
FROM 
  ACCOUNTS_TRX 
INNER JOIN 
  ACCOUNTS_AC_LIST ON ACCOUNTS_AC_LIST.AC_NO = ACCOUNTS_TRX.FROM_AC
INNER JOIN 
  ACCOUNTS_AC_LIST AS ACCOUNTS_AC_LIST1 ON ACCOUNTS_AC_LIST1.AC_NO = ACCOUNTS_TRX.TO_AC
ORDER BY 
  DATE1 DESC;
";
$result1 = mysqli_query($conn, $query1);

if( $result1 === false) {
    die( print_r(mysqli_error($conn), true) );
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap file -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><!-- Bootstrap file -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- jQuery file -->
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script><!--  Font Awesome Icons   -->
</head>

<body>

    <div class="container mt-3">
        <div class="container">
            <font style="font-size: 40px; font-weight: bold;">Recent Transactions</font>
            <button onclick="window.location.href='entry_create.php';" type="button" class="btn btn-outline-info" style="float: right;">Create New Transaction</button>
        </div>

    <br><br>    
    <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Trx Id</th>
        <th>From Account</th>
        <th>To Account</th>
        <th>Amount</th>
        <th>Created At</th>
        <th>Notes</th>
        <th>Notes 2</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php            
    while( $row = mysqli_fetch_assoc($result1)) {
    ?>
      <tr>
        <td><?php echo $row['TRX_ID'] ?></td>
        <td><?php echo $row['FROM_AC'] ?></td>
        <td><?php echo $row['TO_AC'] ?></td>
        <td><?php echo $row['AMOUNT'] ?></td>
        <td><?php echo $row['DATE1'] ?></td>
        <td><?php echo $row['NOTES'] ?></td>
        <td><?php echo $row['NOTES2'] ?></td>
        <td style="display: flex; gap: 10px;">
            <form method="post" action="entry_edit.php">
                <!-- Hidden input to store the SRNO -->
                <input type="hidden" name="srno_name" value="<?php echo $row['TRX_ID']; ?>">
                <button type="submit" class="btn btn-outline-info">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </form>
            <form method="post" action="entry_del.php">
                <!-- Hidden input to store the SRNO -->
                <input type="hidden" name="srno_name" value="<?php echo $row['TRX_ID']; ?>">
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
