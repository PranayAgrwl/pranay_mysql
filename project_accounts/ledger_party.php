<?php
include_once ('connection.php');
include_once ('navbar.php');

if (isset($_POST['ac_no_name'])) {
    $ac_no = $_POST['ac_no_name'];
    
    $query = "SELECT 
    T.TRX_ID, 
    T.TRX_DATE, 
    T.TO_AC AS OTH_AC, 
    T.dr, 
    T.cr, 
    (
        SELECT SUM(T1.bal)
        FROM (
            SELECT 
                QUERY1.TRX_ID, 
                QUERY1.TRX_DATE, 
                QUERY1.FROM_AC, 
                QUERY1.DR, 
                QUERY1.TO_AC, 
                QUERY1.CR, 
                QUERY1.NOTES, 
                ACCOUNTS_AC_LIST.AC_NAME AS FROM_AC_NAME, 
                ACCOUNTS_AC_LIST_1.AC_NAME AS TO_AC_NAME, 
                (QUERY1.CR - QUERY1.DR) AS BAL, 
                CONCAT(DATE_FORMAT(QUERY1.TRX_DATE, '%Y%m%d'), QUERY1.TRX_ID) AS SRNO
            FROM (
                SELECT 
                    ACCOUNTS_TRX.TRX_ID, 
                    ACCOUNTS_TRX.TRX_DATE, 
                    ACCOUNTS_TRX.FROM_AC, 
                    ACCOUNTS_TRX.AMOUNT AS DR, 
                    ACCOUNTS_TRX.TO_AC, 
                    0 AS CR, 
                    ACCOUNTS_TRX.NOTES 
                FROM ACCOUNTS_TRX
                UNION ALL
                SELECT 
                    ACCOUNTS_TRX.TRX_ID, 
                    ACCOUNTS_TRX.TRX_DATE, 
                    ACCOUNTS_TRX.TO_AC AS FROM_AC, 
                    0 AS DR, 
                    ACCOUNTS_TRX.FROM_AC AS TO_AC, 
                    ACCOUNTS_TRX.AMOUNT AS CR, 
                    ACCOUNTS_TRX.NOTES 
                FROM ACCOUNTS_TRX
            ) AS QUERY1
            INNER JOIN ACCOUNTS_AC_LIST ON QUERY1.FROM_AC = ACCOUNTS_AC_LIST.AC_NO
            INNER JOIN ACCOUNTS_AC_LIST AS ACCOUNTS_AC_LIST_1 ON QUERY1.TO_AC = ACCOUNTS_AC_LIST_1.AC_NO
            WHERE QUERY1.FROM_AC = $ac_no
        ) AS T1
        WHERE T1.SRNO <= T.SRNO
    ) AS RUNBAL, 
    T.NOTES, 
    T.FROM_AC_NAME, 
    T.TO_AC_NAME
FROM (
    SELECT 
        QUERY1.TRX_ID, 
        QUERY1.TRX_DATE, 
        QUERY1.FROM_AC, 
        QUERY1.DR, 
        QUERY1.TO_AC, 
        QUERY1.CR, 
        QUERY1.NOTES, 
        ACCOUNTS_AC_LIST.AC_NAME AS FROM_AC_NAME, 
        ACCOUNTS_AC_LIST_1.AC_NAME AS TO_AC_NAME, 
        (QUERY1.CR - QUERY1.DR) AS BAL, 
        CONCAT(DATE_FORMAT(QUERY1.TRX_DATE, '%Y%m%d'), QUERY1.TRX_ID) AS SRNO
    FROM (
        SELECT 
            ACCOUNTS_TRX.TRX_ID, 
            ACCOUNTS_TRX.TRX_DATE, 
            ACCOUNTS_TRX.FROM_AC, 
            ACCOUNTS_TRX.AMOUNT AS DR, 
            ACCOUNTS_TRX.TO_AC, 
            0 AS CR, 
            ACCOUNTS_TRX.NOTES 
        FROM ACCOUNTS_TRX
        UNION ALL
        SELECT 
            ACCOUNTS_TRX.TRX_ID, 
            ACCOUNTS_TRX.TRX_DATE, 
            ACCOUNTS_TRX.TO_AC AS FROM_AC, 
            0 AS DR, 
            ACCOUNTS_TRX.FROM_AC AS TO_AC, 
            ACCOUNTS_TRX.AMOUNT AS CR, 
            ACCOUNTS_TRX.NOTES 
        FROM ACCOUNTS_TRX
    ) AS QUERY1
    INNER JOIN ACCOUNTS_AC_LIST ON QUERY1.FROM_AC = ACCOUNTS_AC_LIST.AC_NO
    INNER JOIN ACCOUNTS_AC_LIST AS ACCOUNTS_AC_LIST_1 ON QUERY1.TO_AC = ACCOUNTS_AC_LIST_1.AC_NO
    WHERE QUERY1.FROM_AC = $ac_no
) AS T
ORDER BY T.SRNO DESC

";

    $result = mysqli_query($conn, $query);

    if ($result === false) {
        echo "Error fetching ledger entries.";
    }


$query2 = "SELECT * FROM ACCOUNTS_AC_LIST WHERE AC_NO = $ac_no ";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_assoc($result2);


}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger Party</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a634adb2b2.js" crossorigin="anonymous"></script>
</head>

<body>

<div class="container mt-3">
    <div class="container">
        <font style="font-size: 40px; font-weight: bold;"><?php echo $row2['AC_NAME'] ?> Ledger</font>
    </div>

    <br><br>    
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Trx ID</th>
                <th>Date</th>
                <th>Account</th>
                <th>Debit</th>
                <th>Credit</th>
                <th>Balance</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
        <?php            
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $row['TRX_ID'] ?></td>
                <td><?php echo $row['TRX_DATE'] ?></td>
                <td><?php echo $row['TO_AC_NAME'] ?></td>
                <td><?php echo $row['DR'] ?></td>
                <td><?php echo $row['CR'] ?></td>
                <td><?php echo $row['RUNBAL'] ?></td>
                <td><?php echo $row['NOTES'] ?></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>

</div>

</body>
</html>
