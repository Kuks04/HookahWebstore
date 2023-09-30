<?php session_start();
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Nargila Shop 222 - Plaćanje</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="./assets/img/favicon.png" rel="icon">
    <link href="./assets/img/favicon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link href="./assets/js/scripts/aos/aos.css" rel="stylesheet">
    <link href="./assets/js/scripts/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/js/scripts/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="./assets/js/scripts/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="./assets/js/scripts/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="./assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    $fname = $lname = $phone = $email = $address = $city = $zip = "";

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay'])) {
        $fname = test_input($_POST['firstName']);
        $lname = test_input($_POST['lastName']);
        $phone = test_input($_POST['phone']);
        $email = test_input($_POST['email']);
        $address = test_input($_POST['address']);
        $city = test_input($_POST['city']);
        $zip = test_input($_POST['zip']);
        $radio = $_POST['paymentMethod'];
        // provera da li su sva polja popunjena
        if (
            !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['phone']) && !empty($_POST['email'])
            && !empty($_POST['address']) && !empty($_POST['city']) && !empty($_POST['zip'])
        ) {
            // provera da li je ime sastavljeno samo od slova
            if (!preg_match('/^[a-zA-Z]{3,30}$/', $fname)) {
                $errors['fname'] = 'Ime sadrži samo slova i više od 3!';
            } else if (isset($errors['fname']))
                unset($errors['fname']);

            // provera da li je prezime sastavljeno samo od slova
            if (!preg_match('/^[a-zA-Z]{3,30}$/', $lname)) {
                $errors['lname'] = 'Prezime sadrži samo slova i više od 3!';
            } else if (isset($errors['lname']))
                unset($errors['lname']);

            // provera da li je naslov sastavljen samo od slova
            if (!preg_match('/^[0-9]{8,10}$/', $phone)) {
                $errors['phone'] = 'Neispravan broj telefona!';
            } else if (isset($errors['phone']))
                unset($errors['phone']);

            // provera da li je email u ispravnom formatu
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Niste uneli ispravan email!';
            } else if (isset($errors['email']))
                unset($errors['email']);

            // provera da li adresa ima manje od 6 karaktera
            if (strlen($address) < 6) {
                $errors['address'] = 'Ulica mora imati više od 6 karaktera!';
            } else if (isset($errors['address']))
                unset($errors['address']);

            // provera da li je grad sastavljen samo od slova
            if (!preg_match('/^[a-zA-Z]{3,30}$/', $city)) {
                $errors['city'] = 'Grad sadrži samo slova i više od 3!';
            } else if (isset($errors['city']))
                unset($errors['city']);

            // provera da li poruka ima manje od 10 karaktera
            if (!preg_match('/^[0-9]{3,6}$/', $zip)) {
                $errors['zip'] = 'Zip se sastoji samo od brojeva!';
            } else if (isset($errors['zip']))
                unset($errors['zip']);

            // ako nema gresaka mozemo poslati email
            if (empty($errors)) {
                if ($radio == 1) {
                    $to = $email;
                    $subject = "Kupovina - Nargila Shop 222";
                    include './sql/connection.php';

                    $total = 0;

                    $message =
                        '<div style="max-width: 400px;">
                    <p> Zdravo ' . $fname . ' ' . $lname . ',<br>Primili smo Vasu porudzbinu!</p>
                    <table>
                        <tr>
                            <th style="border: 1px solid;">Proizvod</th>
                            <th style="border: 1px solid;">Kolicina</th>
                            <th style="border: 1px solid;">Cena</th>
                        </tr>';
                    for ($i = 0; $i < 50; $i++) {
                        if (isset($_SESSION['cart'][$i])) {
                            $select = "SELECT * FROM proizvod WHERE id = " . $_SESSION['cart'][$i][0];
                            $result = $conn->query($select);
                            $row = $result->fetch_assoc();
                            $message .= "<tr>
                                <td style='border: 1px solid;'>" . $row['name'] . "</td>
                                <td style='border: 1px solid;'>" . $_SESSION['cart'][$i][1] . "</td>
                                <td style='border: 1px solid;'>" . (($_SESSION['cart'][$i][1] * $row['price']) - 1) . " rsd</td>
                            </tr>";
                            $total += ($_SESSION['cart'][$i][1] * $row['price']) - 1;
                        }
                    }
                    $message .= '</table><br>
                    <h4>Ukupno za uplatu: ' . $total . ' RSD</h4>
                    <p>Novac uplacujete na nas ziro racun!<br>
                    Sliku uplatnice saljete na ovaj email!</p>
                    </div>';

                    $headers = "From: order@nargilashop222.com \r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html\r\n";

                    if (mail($to, $subject, $message, $headers))
                        echo "<script> Swal.fire({
                        icon: 'success',
                        title: 'Proverite email sanduče!',
                        showConfirmButton: false,
                        timer: 2000
                      }) </script>";

                    $poruka = '<div style="max-width: 400px;">
                    <p> Porudzbina od: ' . $fname . ' ' . $lname . ',<br>
                    Nacin uplate: Uplatnica<br>
                    Za uplatu: ' . $total . ' rsd<br>
                    Broj telefona: ' . $phone . '<br>
                    Email: ' . $email . '<br>
                    Adresa: ' . $address . ' - ' . $city . ' - ' . $zip . '<br>
                    ----------------------
                    </p></div>';
                    $poruka .= $message;
                    mail("order@nargilashop222.com", "Narudzbina - Uplatnica", $poruka, $headers);
                    
                    session_destroy();
                    echo "<script> setTimeout(function() { window.location = './index.php#shop'; }, 2000);</script>";
                } else if ($radio == 2) {
                    echo "<script>
			            Swal.fire({
			            icon: 'error',
			            title: 'Plaćanje karticom je u izradi, uskoro će biti gotovo!',
			            showConfirmButton: false,
			            timer: 3000
			        })</script>";
                }
            }
        }
    }




    ?>

    <!-- Header -->
    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="logo">
                <h1><a href="./index.php">222</a></h1>
            </div>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a class="nav-link scrollto" href="./index.php#hero">Početna</a></li>
                    <li><a class="nav-link scrollto" href="./index.php#about">O nama</a></li>
                    <li><a class="nav-link scrollto " href="./index.php#shop">Shop</a></li>
                    <li><a class="nav-link scrollto" href="./index.php#services">Usluge</a></li>
                    <li><a class="nav-link scrollto" href="./index.php#contact">Kontakt</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header><!-- End Header -->

    <main id="main">

        <!-- Breadcrumbs Section -->
        <section class="breadcrumbs">
        </section><!-- End Breadcrumbs Section -->

        <section class="inner-page">
            <div class="container">
                <div class="pb-5 text-center">
                    <h2>Plaćanje</h2>
                    <p class="lead">Izaberite način plaćanja. Sva polja su obavezna!</p>
                </div>

                <div class="row g-5">
                    <div class="col-md-5 col-lg-4 order-md-last">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span style="color: #3498db">Vaša korpa</span>
                            <span class="badge bg-theme rounded-pill">
                                <?php echo sizeof($_SESSION['cart']); ?>
                            </span>
                        </h4>
                        <ul class="list-group mb-3">
                            <?php
                            include './sql/connection.php';
                            $total = 0;
                            for ($i = 0; $i < 50; $i++) {
                                if (isset($_SESSION['cart'][$i])) {
                                    $select = "SELECT * FROM proizvod WHERE id = " . $_SESSION['cart'][$i][0];
                                    $result = $conn->query($select);
                                    $row = $result->fetch_assoc();
                                    $pname = "qty" . $row['id'];
                                    echo '<li class="list-group-item d-flex justify-content-between lh-sm">
                                        <div>
                                            <h6 class="my-0">' . $row['name'] . '</h6>
                                            <small class="text-muted">Komada: ' . $_SESSION['cart'][$i][1] . '</small>
                                        </div>
                                        <span class="text-muted">' . (($_SESSION['cart'][$i][1] * $row['price']) - 1) . ' RSD</span>
                                    </li>';
                                    $total += ($_SESSION['cart'][$i][1] * $row['price']) - 1;
                                }
                            }
                            echo '<li class="list-group-item d-flex justify-content-between">
                                <span>Ukupno (RSD)</span>
                                <strong>' . $total . ' RSD</strong>
                            </li>';
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-7 col-lg-8">
                        <h4 class="mb-3">Lični podaci</h4>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="firstName" class="form-label">Ime</label>
                                    <input type="text" id="firstName" name="firstName" placeholder="Ime" value="<?php echo $fname; ?>" required 
                                    class="form-control <?php if (!empty($errors['fname'])) { echo 'is-invalid'; }?>">
                                    <?php
                                        if (!empty($errors['fname'])) 
                                            echo '<p class="text-danger mt-3">' . $errors['fname'] . '</p>';
                                    ?>  
                                </div>

                                <div class="col-sm-6">
                                    <label for="lastName" class="form-label">Prezime</label>
                                    <input type="text" id="lastName" name="lastName" placeholder="Prezime" value="<?php echo $lname; ?>" required
                                    class="form-control <?php if (!empty($errors['lname'])) { echo 'is-invalid'; }?>">
                                    <?php
                                        if (!empty($errors['lname'])) 
                                            echo '<p class="text-danger mt-3">' . $errors['lname'] . '</p>';
                                    ?> 
                                </div>

                                <div class="col-12">
                                    <label for="tel" class="form-label">Telefon</label>
                                    <input type="text" id="tel" name="phone" placeholder="064 123 4567" value="<?php echo $phone; ?>" required
                                    class="form-control <?php if (!empty($errors['phone'])) { echo 'is-invalid'; }?>">
                                    <?php
                                        if (!empty($errors['phone'])) 
                                            echo '<p class="text-danger mt-3">' . $errors['phone'] . '</p>';
                                    ?> 
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" id="email" name="email" placeholder="emailadresa@gmail.com" value="<?php echo $email; ?>" required
                                    class="form-control <?php if (!empty($errors['email'])) { echo 'is-invalid'; }?>">
                                    <?php
                                        if (!empty($errors['email'])) 
                                            echo '<p class="text-danger mt-3">' . $errors['email'] . '</p>';
                                    ?> 
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Ulica</label>
                                    <input type="text" id="address" name="address" placeholder="Moja ulica 15/2" value="<?php echo $address; ?>" required
                                    class="form-control <?php if (!empty($errors['address'])) { echo 'is-invalid'; }?>">
                                    <?php
                                        if (!empty($errors['address'])) 
                                            echo '<p class="text-danger mt-3">' . $errors['address'] . '</p>';
                                    ?> 
                                </div>

                                <div class="col-md-5">
                                    <label for="country" class="form-label">Zemlja</label>
                                    <select class="form-select" id="country">
                                        <option value="1">Srbija</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="city" class="form-label">Grad</label>
                                    <input type="text" id="city" name="city" placeholder="Beograd" value="<?php echo $city; ?>" required
                                    class="form-control <?php if (!empty($errors['city'])) { echo 'is-invalid'; }?>">
                                    <?php
                                        if (!empty($errors['city'])) 
                                            echo '<p class="text-danger mt-3">' . $errors['city'] . '</p>';
                                    ?> 
                                </div>

                                <div class="col-md-3">
                                    <label for="zip" class="form-label">Poštanski broj</label>
                                    <input type="text" id="zip" name="zip" placeholder="11 000" value="<?php echo $zip; ?>" required
                                    class="form-control <?php if (!empty($errors['zip'])) { echo 'is-invalid'; }?>">
                                    <?php
                                        if (!empty($errors['zip'])) 
                                            echo '<p class="text-danger mt-3">' . $errors['zip'] . '</p>';
                                    ?> 
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="save-info" required>
                                <label class="form-check-label" for="save-info">Upoznat/upoznata sam i saglasan/saglasna
                                    sam da se lako lomljiva roba šalje isključivo na ličnu odgovornost kupca nakon
                                    uplate iste. </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="save-info" required>
                                <label class="form-check-label" for="save-info">Imam preko 18 godina</label>
                            </div>

                            <hr class="my-4">

                            <h4 class="mb-3">Način plaćanja</h4>

                            <div class="mt-3">
                                <div class="form-check">
                                    <input id="racun" name="paymentMethod" type="radio" class="form-check-input"
                                        value="1" onchange="Radios();" checked>
                                    <label class="form-check-label" for="racun">Uplata na račun</label>
                                </div>
                                <div class="form-check">
                                    <input id="credit" name="paymentMethod" type="radio" class="form-check-input"
                                        value="2" onchange="Radios();">
                                    <label class="form-check-label" for="credit">Plaćanje karticom</label>
                                </div>
                            </div>

                            <div class="row gy-3 mt-3 kartica" style="display:none">
                                <p>Plaćanje karticom će uskoro biti omogućeno!</p>
                                <!-- <div class="col-md-6">
                                    <label for="cc-name" class="form-label">Ime na kartici</label>
                                    <input type="text" class="form-control" id="cc-name" placeholder="">
                                    <small class="text-muted">Puno ime sa kartice</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="cc-number" class="form-label">Broj kartice</label>
                                    <input type="text" class="form-control" id="cc-number" placeholder="">
                                </div>

                                <div class="col-md-3">
                                    <label for="cc-expiration" class="form-label">Datum</label>
                                    <input type="text" class="form-control" id="cc-expiration" placeholder="">
                                </div>

                                <div class="col-md-3">
                                    <label for="cc-cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cc-cvv" placeholder="">
                                </div> -->
                            </div>

                            <div class="row mt-3 racun">
                                <div class="col-auto">
                                    <p>Poslaćemo Vam email poruku na Vašu email adresu sa podacima za uplatu nakon što
                                        kliknete dugme "Platite",
                                        uplaćujete novac u Pošti na dati račun i slikate uplatnicu koju ćete poslati na
                                        istu email adresu sa koje ste dobili poruku.
                                        Nakon verifikacije Vaše uplate šaljemo Vam naručene proizvode!
                                    </p>
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input type="checkbox" class="form-check-input" id="save-info" required>
                                <label class="form-check-label" for="save-info">Prihvatam <a href="#">Uslove
                                        kupovine</a></label>
                            </div>

                            <hr class="my-4">

                            <button class="w-100 btn btn-lg bg-theme" type="submit" name="pay">Platite</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Footer -->
    <footer id="footer">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 text-lg-left text-center">
                    <div class="copyright">
                        &copy;2023 <strong>Nargila Shop 222</strong>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="footer-links text-lg-right text-center pt-2 pt-lg-0">
                        <a href="./index.php#hero" class="scrollto">Početna</a>
                        <a href="./index.php#about" class="scrollto">O nama</a>
                        <a href="./index.php#services" class="scrollto">Usluge</a>
                        <a href="./index.php#shop" class="scrollto">Shop</a>
                    </nav>
                </div>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <script src="./assets/js/scripts/aos/aos.js"></script>
    <script src="./assets/js/scripts/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/scripts/glightbox/js/glightbox.min.js"></script>
    <script src="./assets/js/scripts/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="./assets/js/main.js"></script>

</body>

</html>