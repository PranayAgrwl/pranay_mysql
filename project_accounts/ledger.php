<?php

include_once ('connection.php');
include_once ('navbar.php');

// MySQL query to retrieve account balances
$query = "
SELECT 
    ACCOUNTS_AC_LIST.AC_NAME, 
    SUM(Query1.CRAMT) - SUM(Query1.DRAMT) AS BAL,
    MIN(ACCOUNTS_AC_LIST.AC_NO) AS AC_NO
FROM 
    (SELECT 
        ACCOUNTS_TRX.FROM_AC AS AC_NAME, 
        ACCOUNTS_TRX.AMOUNT AS DRAMT, 
        0 AS CRAMT
    FROM
        ACCOUNTS_TRX
    UNION ALL
    SELECT 
        ACCOUNTS_TRX.TO_AC AS AC_NAME, 
        0 AS DRAMT, 
        ACCOUNTS_TRX.AMOUNT AS CRAMT
    FROM 
        ACCOUNTS_TRX) AS Query1
INNER JOIN 
    ACCOUNTS_AC_LIST 
    ON Query1.AC_NAME = ACCOUNTS_AC_LIST.AC_NO
GROUP BY 
    ACCOUNTS_AC_LIST.AC_NAME;
";

$result = mysqli_query($conn, $query);

if ($result === false) {
    die( print_r( mysqli_error($conn), true) );
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container mt-3">
        <div class="container">
            <font style="font-size: 40px; font-weight: bold;">Ledger Accounts List</font>
        </div>
    
        <br><br>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Account Name</th>
                    <th>Balance</th>
                    <th>View Ledger</th>
                </tr>
            </thead>
            <tbody>
            <?php            
            while($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row['AC_NAME'] ?></td>
                    <td><?php echo $row['BAL'] ?></td>
                    <td>
                        <form method="post" action="ledger_party.php">
                            <input type="hidden" name="ac_no_name" value="<?php echo $row['AC_NO']; ?>">
                            <button type="submit" class="btn btn-outline-info">
                            <i class="fa-solid fa-eye"></i>
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
