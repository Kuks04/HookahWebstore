<?php
session_start();
if (isset($_SESSION['admin222']) && $_SESSION['admin222']) {
    include '../sql/connection.php';
    $id = $_GET['id'];
    $select = "SELECT image FROM proizvod WHERE id='$id'";
    $value = mysqli_fetch_assoc(mysqli_query($conn, $select));
    $delete = "DELETE FROM proizvod WHERE id='$id'";
    if (mysqli_query($conn, $delete)) {
        unlink('../assets/img/shop/' . basename($value['image']));
        echo "<script>alert('Uspesno izbrisan proizvod!'); </script>";
    }
}
echo "<script>window.location = '../index.php#shop'; </script>";
?>