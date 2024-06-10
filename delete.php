<?php
include 'conn.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM `data` WHERE id='$id';";
    if (mysqli_query($conn, $sql)) {
        header("Location:view_data.php");
        //exit;
    }
}
?>