<?php session_start(); 
if(!isset($_SESSION['cart'])){
	$_SESSION['cart'] = array();
}
function test_input($data) {
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

	<title>Nargila Shop 222</title>
	<meta content="" name="description">
	<meta content="" name="keywords">

	<!-- Favicons -->
	<link href="assets/img/favicon.png" rel="icon">
	<link href="assets/img/favicon.png" rel="apple-touch-icon">

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
	if(isset($_SESSION['admin222']) && $_SESSION['admin222']) {
		echo "<style> .admin { display: ''; } </style>";
	}
	else {
		echo "<style> .admin { display: none; } </style>";
	}

	$name = $email = $subject = $msg = '';
	// provera da li je forma podneta
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contact_btn'])) {
        // dodeljivanje vrednosti promenljivama
        $name = test_input($_POST['name']);
        $email = test_input($_POST['email']);
        $subject = test_input($_POST['subject']);
        $msg = test_input($_POST['message']);

        // provera da li su sva polja popunjena
        if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['subject']) && !empty($_POST['message'])) {
            // provera da li je ime sastavljeno samo od slova
            if (!preg_match('/^[a-zA-Z\s]{3,30}$/', $name)) {
                $errors['name'] = 'Ime sadrži samo slova i više od 3!';
            } else $errors['name'] = null;

            // provera da li je naslov sastavljen samo od slova
            if (!preg_match('/^[a-zA-Z]{3,30}$/', $subject)) {
                $errors['subject'] = 'Naslov sadrži samo slova i više od 3!';
            } else $errors['subject'] = null;

            // provera da li je email u ispravnom formatu
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Niste uneli ispravan email!';
            } else $errors['email'] = null;

            // provera da li poruka ima manje od 10 karaktera
            if (strlen($msg) < 10) {
                $errors['msg'] = 'Poruka mora imati više od 10 karaktera!';
            } else $errors['msg'] = null;

            // ako nema gresaka mozemo poslati email
            if ($errors['name'] == null && $errors['email'] == null && $errors['subject'] == null && $errors['msg'] == null) {
				$to = 'info@nargilashop222.com';
                $message =
                '<div style="max-width: 400px;">
                    <p> ' . $msg . ' </p>
                    <h4>' . $name . '</h4>
					<h3>' . $email . '</h3>
                </div>';
                $headers = "From: $email \r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html\r\n";
				if(mail($to, $subject, $message, $headers))
                	echo "<script> 
					Swal.fire({
						icon: 'success',
						title: 'Poruka uspešno poslata!',
						showConfirmButton: false,
						timer: 2000
					  }) </script>";
				$name = $email = $subject = $msg = '';
            }
        } else {
            $errors['empty'] = 'Popunite sva polja!';
        }
		echo "<script> window.location = './index.php#contact' </script>";
    }

	if(isset($_POST['add-to-cart'])) {
		//Da li je proizvod vec dodat
		$s=0;
		for($i=0; $i<100; $i++) {
			if(isset($_SESSION['cart'][$i])) {
				if($_SESSION['cart'][$i][0] == $_POST['id']) {
					$s=1;
				}
			}   
		}
		if($s==0){
			$b=array($_POST['id'], 1);
			array_push($_SESSION['cart'], $b);
			echo "<script>
			  Swal.fire({
				icon: 'success',
				title: 'Proizvod dodat u korpu!',
				showConfirmButton: false,
				timer: 2000
			  })</script>";
		}
		else{
			echo "<script>
			Swal.fire({
			  icon: 'error',
			  title: 'Već ste dodali proizvod u korpu!',
			  showConfirmButton: false,
			  timer: 2000
			})</script>";
		}
		echo "<script> window.location = './index.php#shop' </script>";
	}
	?>	
	<!-- Header -->
	<header id="header" class="fixed-top d-flex align-items-center">
		<div class="container d-flex align-items-center justify-content-between">
			<div class="logo">
				<h1><a href="index.php">222</a></h1>
			</div>
			<nav id="navbar" class="navbar">
				<ul>
					<li><a class="nav-link scrollto active" href="#hero">Početna</a></li>
					<li><a class="nav-link scrollto" href="#about">O nama</a></li>
					<li><a class="nav-link scrollto " href="#shop">Shop</a></li>
					<li><a class="nav-link scrollto" href="#services">Usluge</a></li>
					<li><a class="nav-link scrollto" href="#contact">Kontakt</a></li>
				</ul>
				<a href="./pages/add.php"><i class="fa-solid fa-square-plus fa-xl ms-sm-5 ms-2 me-sm-2 me-1 admin"></i></a>
				<a href="./pages/logout.php"><i class="fa-solid fa-right-from-bracket fa-xl admin"></i></a>
				<a href="./cart.php"><i class="fa-solid fa-cart-shopping <?php if(!empty($_SESSION['cart'])) echo "fa-bounce"?> fa-xl ms-sm-5 ms-1 me-lg-0 me-3"></i></a>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
	</header><!-- End Header -->
	
	<!-- Hero -->
	<section id="hero" class="d-flex align-items-center">

		<div class="container">
			<div class="row">
				<div class="col-lg-6 pt-5 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center pe-lg-4">
					<h1 data-aos="fade-up" data-aos-delay="200">Iskustvo kao nijedno drugo</h1>
					<h2 data-aos="fade-up" data-aos-delay="200">Otkrijte širok izbor nargila i pribora u Nargila Shopu 222 - savršeno mjesto za svakog ljubitelja nargile
					</h2>
					<div data-aos="fade-up" data-aos-delay="400">
						<a href="#shop" class="btn-get-started scrollto">Pogledaj proizvode</a>
					</div>
				</div>
				<div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="fade-left" data-aos-delay="200">
					<!-- <img src="./assets/img/hero.gif" class="img-fluid animated" alt=""> -->
				</div>
			</div>
		</div>

	</section><!-- End Hero -->

	<main id="main">

		<!-- About Us -->
		<section id="about" class="about">
			<div class="container">
				<div class="section-title" data-aos="fade-up">
					<h2>Nargila Shop 222</h2>
				</div>

				<div class="row">
					<div class="image col-xl-5 d-flex align-items-stretch justify-content-center justify-content-xl-start"
						data-aos="fade-right" data-aos-delay="150">
						<img src="./assets/img/hookahoncouch.png" alt="" class="img-fluid">
					</div>
					<div class="col-xl-1"></div>
					<div class="col-xl-6 d-flex align-items-stretch pt-4 pt-xl-0" data-aos="fade-left"
						data-aos-delay="200">
						<div class="content d-flex flex-column justify-content-center">
							<p class="">
							Dobrodošli u Nargila Shop 222, vašu destinaciju za vrhunsku, kvalitetnu i stilsku
							estetiku nargila i pribora. Naša posvećenost, izvrsnost i strast prema
							nargili odvode nas korak dalje u pronalaženju najboljih proizvoda na tržištu,
							kako bi našim kupcima osigurali nezaboravno iskustvo uživanja u nargili.
							</p>
						</div><!-- End .content-->
					</div>
				</div>
			</div>
		</section><!-- End About Us Section -->

		<!-- Shop Section -->
		<section id="shop" class="shop">
			<div class="container">

				<div class="section-title" data-aos="fade-up">
					<h2>Shop</h2>
					<p>Uživajte u povoljnim cenama i brzoj dostavi</p>
				</div>

				<div class="row" data-aos="fade-up" data-aos-delay="200">
					<div class="col-lg-12 d-flex justify-content-center">
						<ul id="shop-flters">
							<li data-filter="*" class="filter-active">Sve</li>
							<li data-filter=".filter-narg">Nargile</li>
							<li data-filter=".filter-ukusi">Ukusi</li>
							<li data-filter=".filter-ostalo">Ostalo</li>
						</ul>
					</div>
				</div>

				<div class="row shop-container" data-aos="fade-up" data-aos-delay="400">
					<?php 
					include './sql/connection.php';
					$sql = "SELECT id, name, category, price, description, image FROM proizvod";
					$result = $conn->query($sql);
	
					while($row = $result->fetch_assoc()) {
						echo "<script>alert(".$row['name'].")</script>";
						if($row['category']=='Nargila')
							$category = 'filter-narg';
						else if($row['category']=='Ukus')
							$category = 'filter-ukusi';
						else if($row['category']=='Ostalo')
							$category = 'filter-ostalo';

						echo '<div class="col-lg-4 col-md-6 shop-item '. $category .'">
							<div class="product shadow-lg rounded-2" data-aos="fade-up" data-aos-delay="100">
								<a href="shop-details.php?id='. $row['id'] .'">
									<div class="product-img">
										<img src="./assets/img/shop/'. $row['image'] .'" class="img-fluid" alt="'. $row['description'] .'">
										<div class="social">
											<i class="fa-solid fa-plus fa-xs me-2"></i>Više
										</div>
									</div>
									<div class="product-info mt-1 py-2">
									<span>'. $row['category'] .'</span>
									<h4>'. $row['name'] .'</h4>
									<h3 class="mt-3">'. ($row['price']-1) .' RSD</h3>
									</div>
								</a>
								<a href="./pages/delete.php?id='. $row['id'] .'" onclick="return confirm(\'Da li si siguran da hoćeš da izbriseš?\')" 
									class="btn btn-primary-outline text-danger mb-2 admin">Izbriši</a>
								<form action="" method="POST">
									<input name="id" type="text" class="d-none" value="'. $row['id'] .'">
									<button class="btn w-100 py-3 bg-theme" name="add-to-cart" type="submit">
										<i class="fa-solid fa-cart-plus fa-xl me-3"></i>Dodaj u korpu
									</button>
								</form>
							</div>
						</div>';
					}
					?>
				</div>
			</div>
		</section><!-- End Shop Section -->

		<!-- Services Section -->
		<section id="services" class="services">
			<div class="container">

				<div class="section-title" data-aos="fade-up" data-aos-delay="50">
					<h2>Usluge</h2>
					<p>U Kraljevu se možete družiti sa nama od 13h do 22h radnim danima, vikendom do 23h</p>
				</div>

				<div class="row">
					<div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
						<div class="icon-box" data-aos="fade-up" data-aos-delay="100">
							<div class="icon"><i class="fa-solid fa-store"></i></div>
							<h4 class="title">Shop</h4>
							<p class="description">Naručite ili kupite lično na našoj lokaciji
								sve što Vam treba za nargilu</p>
						</div>
					</div>

					<div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
						<div class="icon-box" data-aos="fade-up" data-aos-delay="150">
							<div class="icon"><i class="fa-solid fa-bong"></i></div>
							<h4 class="title">Degustacija</h4>
							<p class="description">Degustirajte nargilu u lokalu po ceni od 500 dinara</p>
						</div>
					</div>

					<div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
						<div class="icon-box" data-aos="fade-up" data-aos-delay="200">
							<div class="icon"><i class="fa-solid fa-hand-holding-hand"></i></div>
							<h4 class="title">Iznajmljivanje</h4>
							<p class="description">Možete iznajmiti nargilu na teritotiji Kraljeva po povoljnoj ceni</p>
						</div>
					</div>

					<div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
						<div class="icon-box" data-aos="fade-up" data-aos-delay="250">
							<div class="icon"><i class="fa-solid fa-joint"></i></div>
							<h4 class="title">Tompusi</h4>
							<p class="description">Isprobajte kvalitetne tompuse za nezaboravno iskustvo pušenja</p>
						</div>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col-md-6 d-flex align-items-stretch">
						<img class="img-fluid rounded-2" src="./assets/img/services/lokal.jpg" data-aos="fade-up"
							data-aos-delay="100">
					</div>
					<div class="col-md-6 d-flex align-items-stretch mt-4 mt-md-0">
						<img class="img-fluid rounded-2" src="./assets/img/services/ukusi.jpg" data-aos="fade-up"
							data-aos-delay="200">
					</div>
					<div class="col-md-6 d-flex align-items-stretch mt-4">
						<img class="img-fluid rounded-2" src="./assets/img/services/nargile.jpg" data-aos="fade-up"
							data-aos-delay="100">
					</div>
					<div class="col-md-6 d-flex align-items-stretch mt-4">
						<img class="img-fluid rounded-2" src="./assets/img/services/glava.jpg" data-aos="fade-up"
							data-aos-delay="200">
					</div>
				</div>
			</div>
		</section><!-- End Services Section -->

		<!-- Contact Section -->
		<section id="contact" class="contact">
			<div class="container">

				<div class="section-title" data-aos="fade-up">
					<h2>Kontakt</h2>
				</div>

				<div class="row">
					<div class="col-md-3 mt-4 mt-md-0" data-aos="fade-up" data-aos-delay="200">
						<div class="info">
							<div>
								<a href="https://goo.gl/maps/Y9xmuBJE7TV9A1AFA" target="_blank">
									<i class="fa-solid fa-location-dot"></i> Obilićeva 21<br>Kraljevo, 36000
								</a>
							</div>
							<div class="my-3">
								<a href="mailto:info@nargilashop222.com" target="_blank">
									<i class="fa-solid fa-envelope"></i> info@nargilashop222.com
								</a>
							</div>
							<div>
								<a href="tel:0628743808" target="_blank">
									<i class="fa-solid fa-phone"></i> +381 62 874 3808
								</a>
							</div>
						</div>
						<div class="social-links d-flex mt-3">
							<a href="https://www.tiktok.com/@nargilashop222" target="_blank" class="tiktok me-2">
								<i class="bi bi-tiktok"></i></a>
							<a href="https://www.instagram.com/nargilashop222_kraljevo/" target="_blank"
								class="instagram ms-2"><i class="bi bi-instagram"></i></a>
						</div>
					</div>

					<div class="col-md-9 mb-lg-0 mb-4 mt-4 mt-md-0" data-aos="fade-up" data-aos-delay="300">
						<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="php-email-form">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<input type="text" name="name" id="name" placeholder="Ime" value="<?php echo $name; ?>" 
											class="form-control rounded-2 <?php if (!empty($errors['name']) || !empty($errors['empty'])) { echo 'is-invalid'; }?>">
										<?php
                                            if (!empty($errors['name'])) 
                                                echo '<p class="text-danger mt-3">' . $errors['name'] . '</p>';
                                        ?>
									</div>
									<div class="form-group">
										<input type="text" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" 
											class="form-control rounded-2 <?php if (!empty($errors['email']) || !empty($errors['empty'])) { echo 'is-invalid'; }?>">
										<?php
                                            if (!empty($errors['email']))
                                                echo '<p class="text-danger mt-3">' . $errors['email'] . '</p>';
                                        ?>
									</div>
									<div class="form-group">
										<input type="text" name="subject" id="subject" placeholder="Naslov" value="<?php echo $subject; ?>"
											class="form-control rounded-2 <?php if (!empty($errors['subject']) || !empty($errors['empty'])) { echo 'is-invalid'; }?>">
										<?php
                                            if (!empty($errors['subject']))
                                                echo '<p class="text-danger mt-3">' . $errors['subject'] . '</p>';
                                        ?>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<textarea class="form-control rounded-2 <?php if (!empty($errors['msg']) || !empty($errors['empty'])) { echo 'is-invalid'; }?>" 
										name="message" rows="4" placeholder="Šta Vas zanima?"><?php echo $msg; ?></textarea>
										<?php
                                            if (!empty($errors['msg']))
                                                echo '<p class="text-danger mt-3">' . $errors['msg'] . '</p>';
                                    	?>
									</div>
									<button type="submit" class="btn" name="contact_btn">Pošalji</button>
								</div>
							</div>
							<?php
                                if (!empty($errors['empty']))
                                    echo '<p class="text-danger mt-3">' . $errors['empty'] . '</p>';
                            ?>
						</form>
					</div>
					<div class="col-12 mt-3" data-aos="fade-up" data-aos-delay="100">
						<div class="contact-about">
							<iframe
								src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5766.554798320886!2d20.68517931285413!3d43.72556390771592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x475701a5df07d5f3%3A0xcec2c81f9d0be8d1!2sNargila%20shop%20222%20Kraljevo!5e0!3m2!1ssr!2srs!4v1679511803578!5m2!1ssr!2srs"
								class="w-100 rounded-2" height="300" allowfullscreen="" loading="lazy"
								referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
					</div>
				</div>

			</div>
		</section><!-- End Contact Section -->

	</main>

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
						<a href="#" class="scrollto">Početna</a>
						<a href="#about" class="scrollto">O nama</a>
						<a href="#services" class="scrollto">Usluge</a>
						<a href="#shop" class="scrollto">Shop</a>
					</nav>
				</div>
			</div>
		</div>
	</footer><!-- End Footer -->
	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
			class="bi bi-arrow-up-short"></i></a>

	<!-- Scripts -->
	<script src="assets/js/scripts/aos/aos.js"></script>
	<script src="assets/js/scripts/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/scripts/glightbox/js/glightbox.min.js"></script>
	<script src="assets/js/scripts/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="assets/js/main.js"></script>
</body>

</html>