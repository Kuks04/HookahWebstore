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

    <title>Nargila shop 222 - Add</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/favicon.png" rel="apple-touch-icon">

    <!-- CSS -->
    <link href="../assets/js/scripts/aos/aos.css" rel="stylesheet">
    <link href="../assets/js/scripts/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/js/scripts/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/js/scripts/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/js/scripts/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../assets/js/scripts/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <?php
    if (!isset($_SESSION['admin222']) && !$_SESSION['admin222']) {
        echo "<script> window.location = '../index.php' </script>";
    } 

    $name = $brand = $category = $price = $desc = '';
    // provera da li je forma podneta
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_btn']) ) {
        // dodeljivanje vrednosti promenljivama
        $name = test_input($_POST['name']);
        $brand = test_input($_POST['brand']);
        $select = test_input($_POST['category']);
        $price = test_input($_POST['price']);
        $desc = test_input($_POST['desc']);

        if ($select == "1")
            $category = "Nargila";
        else if ($select == "2")
            $category = "Ukus";
        else if ($select == "3")
            $category = "Ostalo";
            
        // proveravamo da li je fajl uspesno prenesen na server
        if (is_uploaded_file($_FILES['upload']['tmp_name'])) {
            // proveravamo da li je fajl manji od 5MB
            if ($_FILES['upload']['size'] < 5242880) {
                // proveravamo da li je fajl jedan od dozvoljenih tipova
                $allowedTypes = ['image/jpeg', 'image/png', 'image/svg', 'image/jpg'];
                if (in_array($_FILES['upload']['type'], $allowedTypes)) {
                    $uploadDir = "../assets/img/shop/";

                    // dodajemo vreme da se ne bi desavalo da postoje slike sa istim imenima
                    $fileName = time() . '_' . $_FILES['upload']['name'];
                    $uploadFile = $uploadDir . basename($fileName);
                    // ubacujemo fajl u nas folder
                    if (!move_uploaded_file($_FILES['upload']['tmp_name'], $uploadFile)) {
                        $errors['uploaderr'] = 'Greška tokom otpremanja, pokušajte ponovo.';
                    }
                } else {
                    $errors['uploaderr'] = 'Slika nije dozvoljenog formata! Dozvoljeni - (JPEG, JPG, PNG, SVG).';
                }
            } else {
                $errors['uploaderr'] = 'Slika je veća od 5MB!';
            }
        } else {
            $errors['uploaderr'] = 'Izaberi sliku!';
        }

        // provera da li su sva polja popunjena
        if (
           !empty($_POST['name']) && !empty($_POST['brand']) && !empty($_POST['category'])
           && !empty($_POST['price']) && !empty($_POST['desc']) && !empty($fileName)
        ) {
            include '../sql/connection.php';
            $insert = "INSERT INTO proizvod(name, brand, category, price, description, image)
                VALUES('$name', '$brand', '$category', '$price', '$desc', '$fileName')";
            if (mysqli_query($conn, $insert))
                echo "<script> alert('Uspesno dodat proizvod') </script>";
            $name = $brand = $category = $price = $desc = $fileName = '';
        }

        echo "<script> window.location = '../index.php' </script>";
    }
    ?>
    <main id="main">
        
        <div class="container contact pt-5">
            <a class="display-4" href="../index.php">Nazad</a>
            <div class="mt-5 section-title" data-aos="fade-up">
                <h2>Dodavanje</h2>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="php-email-form"
                data-aos="fade-up" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="name" id="name" placeholder="Ime" value="<?php echo $name; ?>"
                                class="form-control rounded-2" minlength="3" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="brand" id="brand" placeholder="Marka" value="<?php echo $brand; ?>"
                                class="form-control rounded-2" minlength="3" required>
                        </div>

                        <div class="form-group">
                            <select name="category" id="category" aria-label=".form-select-lg example"
                                class="form-select rounded-2" required>
                                <option value="">Kategorija</option>
                                <option value="1">Nargila</option>
                                <option value="2">Ukus</option>
                                <option value="3">Ostalo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="number" name="price" id="price" placeholder="Cena"
                                value="<?php echo $price; ?>" class="form-control rounded-2" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <textarea class="form-control rounded-2" name="desc" rows="4" placeholder="Opis"
                                minlength="10" required><?php echo $desc; ?></textarea>
                        </div>
                        <button class="btn btn-primary rounded-5" onclick="document.getElementById('getFile').click();"
                            type="button">Dodaj sliku</button>
                        <input type='file' id="getFile" name="upload" accept=".png, .jpg, .jpeg, .svg"
                            style="display:none;" required>
                        <?php if (!empty($errors['uploaderr']))
                            echo '<p class="text-danger mt-3">' . $errors['uploaderr'] . '</p>'; ?>
                    </div>
                    <hr class="my-4 bg-dark opacity-100">
                    <button type="submit" class="btn w-50 m-lg-auto ms-lg-0 ms-2" name="add_btn">Dodaj Proizvod</button>
                </div>
            </form>
        </div>

    </main>

    <!-- Scripts -->
    <script src="../assets/js/scripts/aos/aos.js"></script>
    <script src="../assets/js/scripts/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts/glightbox/js/glightbox.min.js"></script>
    <script src="../assets/js/scripts/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="../assets/js/scripts/swiper/swiper-bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>