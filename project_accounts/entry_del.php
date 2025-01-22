<?php
include_once ('connection.php');
include_once ('navbar.php');

if (isset($_POST['srno_name'])) {
    $trx_id = $_POST['srno_name'];
    
    // MySQL query to delete entry
    $query = "DELETE FROM ACCOUNTS_TRX WHERE TRX_ID = $trx_id";

    $result = mysqli_query($conn, $query);

    if($result)
    {
        echo "Entry Deleted !!!";
        header("Location: entry_view.php");
        exit();
    }
    else
    {
        echo "Error !!!";
    }
}
?>
