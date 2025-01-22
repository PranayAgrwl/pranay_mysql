<?php
include_once ('connection.php');
include_once ('navbar.php');

if (isset($_POST['srno_name'])) {
    $trx_id = $_POST['srno_name'];
    
    // MySQL query to fetch transaction data based on the SRNO
    $query = "SELECT 
    ACCOUNTS_TRX.*, 
    ACCOUNTS_AC_LIST.AC_NAME AS FROM_AC, 
    ACCOUNTS_TRX.FROM_AC AS FROM_NO, 
    ACCOUNTS_AC_LIST1.AC_NAME AS TO_AC
    FROM ACCOUNTS_TRX 
    INNER JOIN ACCOUNTS_AC_LIST ON ACCOUNTS_AC_LIST.AC_NO = ACCOUNTS_TRX.FROM_AC
    INNER JOIN ACCOUNTS_AC_LIST AS ACCOUNTS_AC_LIST1 ON ACCOUNTS_AC_LIST1.AC_NO = ACCOUNTS_TRX.TO_AC
    WHERE ACCOUNTS_TRX.TRX_ID = $trx_id
    ";

    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "Error !!!";
    }
}

// Fetch the list of accounts for 'From Account' and 'To Account' dropdowns
$query1 = "SELECT * FROM ACCOUNTS_AC_LIST ORDER BY AC_NAME";
$result1 = mysqli_query($conn, $query1);

if ($result1 === false) {
    die(print_r(mysqli_error($conn), true));
}
$query11 = " SELECT 
A.AC_NAME AS FROM_NAME,
A.AC_NO AS FROM_NO
FROM ACCOUNTS_TRX T
INNER JOIN ACCOUNTS_AC_LIST A ON T.FROM_AC = A.AC_NO
WHERE T.TRX_ID = $trx_id
";
$result11 = mysqli_query($conn, $query11);

if ($result11 === false) {
    die(print_r(mysqli_error($conn), true));
}
else {
    $row11 = mysqli_fetch_assoc($result11);
}



$query2 = "SELECT * FROM ACCOUNTS_AC_LIST ORDER BY AC_NAME";
$result2 = mysqli_query($conn, $query2);

if ($result2 === false) {
    die(print_r(mysqli_error($conn), true));
}

$query22 = " SELECT 
A.AC_NAME AS TO_NAME,
A.AC_NO AS TO_NO
FROM ACCOUNTS_TRX T
INNER JOIN ACCOUNTS_AC_LIST A ON T.TO_AC = A.AC_NO
WHERE T.TRX_ID = $trx_id
";
$result22 = mysqli_query($conn, $query22);

if ($result22 === false) {
    die(print_r(mysqli_error($conn), true));
}
else {
    $row22 = mysqli_fetch_assoc($result22);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap file -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><!-- Bootstrap file -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- jQuery file -->
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script><!--  Font Awesome Icons   -->
</head>

<body>

<div class="container mt-3">
  <h2>Edit Transaction</h2>
  <form method="post" action="entry_upd.php">
    <div class="mb-3 mt-3">
      <label>Transaction No:</label>
      <input type="number" readonly class="form-control" name="srno_name" value="<?php echo $row['TRX_ID'] ?>">
    </div>
    <div class="mb-3">
      <label>From Account:</label><br>
      <select name="from_ac_name" class="form-control"> 
          <option value=""></option>    
          <?php while ($row1 = $result1->fetch_assoc()) { ?>
                  <option value="<?php echo $row1['AC_NO'] ; ?>" <?php echo $row11['FROM_NAME'] == $row1['AC_NAME'] ? 'selected' : ''; ?> >
                      <?php echo $row1['AC_NAME'];?> 
                  </option>
          <?php } ?>
      </select>
    </div>
    <div class="mb-3">
      <label>To Account:</label><br>
      <select name="to_ac_name" class="form-control"> 
          <option value=""></option>    
          <?php while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                  <option value="<?php echo $row2['AC_NO'] ; ?>" <?php echo $row22['TO_NAME'] == $row2['AC_NAME'] ? 'selected' : ''; ?> >
                      <?php echo $row2['AC_NAME'];?> 
                  </option>
          <?php } ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Amount:</label>
      <input type="text" class="form-control" name="amount_name" value="<?php echo $row['AMOUNT'] ?>">
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
<br>

</body>
</html>