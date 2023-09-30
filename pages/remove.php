<?php
session_start();

for($i=0; $i<100; $i++) {
    if(isset($_SESSION['cart'][$i])) {
        if($_SESSION['cart'][$i][0] == $_GET['id']) {
            unset($_SESSION['cart'][$i]);
        }
    }   
}
echo "<script>window.location = '../cart.php'; </script>";
?>