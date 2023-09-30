<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Nargila Shop Admin</title>
    <link href="../assets/js/scripts/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="row" method="post">
        <div class="form-group mt-5">
            <input type="text" name="nick" placeholder="Korisnicko ime"
                class="form-control m-auto w-75" minlength="3" required>
        </div>

        <div class="form-group mt-5">
            <input type="password" name="pass" placeholder="Lozinka"
                class="form-control m-auto w-75" minlength="3" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary mt-5 m-auto w-25">LOGIN</button>
    </form>
    <?php
    if (isset($_POST['submit'])) {
        if ($_POST['nick'] == "glodjo" && $_POST['pass'] == "GlodjovaNargil@222") {
            $_SESSION['admin222'] = true;
            echo "<script>alert('Uspesno Glodjare!');
            window.location='../index.php'</script>";
        } else
            $_SESSION['admin222'] = false;
    }
    ?>
</body>

</html>