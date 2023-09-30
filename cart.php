<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Nargila Shop 222 - Korpa</title>
	<meta content="" name="description">
	<meta content="" name="keywords">

	<!-- Favicons -->
	<link href="assets/img/favicon.png" rel="icon">
	<link href="assets/img/favicon.png" rel="apple-touch-icon">

	<!-- CSS -->
	<link href="assets/js/scripts/aos/aos.css" rel="stylesheet">
	<link href="assets/js/scripts/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/js/scripts/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="assets/js/scripts/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="assets/js/scripts/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
	<link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

	<!-- Header -->
	<header id="header" class="fixed-top d-flex align-items-center">
		<div class="container d-flex align-items-center justify-content-between">
			<div class="logo">
				<h1><a href="index.html">222</a></h1>
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

		<!-- ======= Breadcrumbs Section ======= -->
		<section class="breadcrumbs">
			<div class="container">

				<div class="d-flex justify-content-between align-items-center">
					<h2>Korpa</h2>
					<ol>
						<li><a href="./index.php">Shop</a></li>
						<li>Korpa</li>
					</ol>
				</div>

			</div>
		</section><!-- End Breadcrumbs Section -->
		<section class="cart">
			<div class="container">
				<?php
				if (empty($_SESSION['cart']))
					echo "<p class='display-3'>Vaša korpa je prazna!</p><a href='./index.php' class='display-5'>Nazad</a>";
				else {
					echo '<div class="row d-sm-flex d-none">
							<div class="col-1">
								<p>Proizvod</p>
							</div>
							<div class="col-6">
							</div>
							<div class="col-2">
								<p>Količina</p>
							</div>
							<div class="col-2">		
								<p class="">Cena</p>
							</div>
							<div class="col-1">		
								<p class="">Izbriši</p>
							</div>
						</div>';
					include './sql/connection.php';
					$total = 0;
					$minus = array();
					$plus = array();
					for ($i = 0; $i < 50; $i++) {
						if (isset($_SESSION['cart'][$i])) {
							$select = "SELECT * FROM proizvod WHERE id = " . $_SESSION['cart'][$i][0];
							$result = $conn->query($select);
							$row = $result->fetch_assoc();
							$minus [$i] = "";
							if($_SESSION['cart'][$i][1] == 1) $minus [$i] = "disabled";
							$plus [$i] = "";
							if($row['category'] == "Nargila" || $_SESSION['cart'][$i][1] == 5) $plus [$i] = "disabled";
							$pname = "qty" . $row['id'];
							if (isset($_POST['minus' . $pname])) {
								$plus [$i] = "";
								if ($_SESSION['cart'][$i][1] > 1) {
									$_SESSION['cart'][$i][1]--;
									if($_SESSION['cart'][$i][1] == 1) {
										$minus [$i] = "disabled";
									}
									else $minus [$i] = "";
								}
							} else if (isset($_POST['plus' . $pname])) {
								$minus [$i] = "";
								if ($_SESSION['cart'][$i][1] < 5 && $row['category'] != "Nargila") {
									$_SESSION['cart'][$i][1]++;
									if($_SESSION['cart'][$i][1] == 5) {
										$plus [$i] = "disabled";
									}
								}
							}
							echo '<div class="card mb-4">
							<div class="row g-0">
								<div class="col-md-1 col-6">
									<img src="./assets/img/shop/' . $row['image'] . '" class="img-fluid rounded-start" alt="' . $row['description'] . '">
								</div>
								<div class="col-md-6 col-6">
									<div class="card-body">
										<p class="card-title text-black display-6">' . $row['name'] . '</p>
										<p class="card-text"><small class="text-muted">' . $row['brand'] . '</small></p>
									</div>
								</div>
								<div class="col-md-2 col-5 d-flex align-items-center user-select-none">
									<div class="card-body">
										<div class="qty">
											<form action="" method="post">
												<button type="submit" name="minus' . $pname . '" class="border-0" '.$minus [$i].'>
													<i class="fa-solid fa-circle-minus fa-lg"></i>
												</button>
												<input type="text" class="count border-0" value="' . $_SESSION['cart'][$i][1] . '" disabled>
												<button type="submit" name="plus' . $pname . '" class="border-0" '.$plus [$i].'>
													<i class="fa-solid fa-circle-plus fa-lg"></i>
												</button>
											</form>
										</div>
									</div>
								</div>
								<div class="col-md-2 col-5 d-flex align-items-center">		
									<div class="card-body">
										<p class="mt-2 fw-bold price">' . (($_SESSION['cart'][$i][1] * $row['price']) - 1) . ' RSD</p>
									</div>
								</div>
								<div class="col-md-1 col-2 d-flex align-items-center">		
									<div class="card-body d-flex justify-content-sm-center justify-content-end">
										<a href="./pages/remove.php?id=' . $row['id'] . '"><i class="fa-solid fa-trash" style="color: #c41c1c;"></i></a>
									</div>
								</div>
							</div>
						</div>';
						$total+= ($_SESSION['cart'][$i][1] * $row['price']) - 1;
						}
					}
					echo '<div class="d-flex justify-content-end">
						<p class="display-6 total">Ukupno: '. $total .' RSD</p>
					</div>
					<form action="./payment.php" method="post" class="d-flex justify-content-end">
						<div class="col-lg-4 col-md-6 col-12">
						<button class="btn w-100 py-3 bg-theme" name="payment_btn" type="submit">
							<i class="fa-solid fa-credit-card fa-lg me-2"></i>Plaćanje
						</button>
						</div>
					</form>';
				}
				?>
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
	<script src="assets/js/scripts/aos/aos.js"></script>
	<script src="assets/js/scripts/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/scripts/glightbox/js/glightbox.min.js"></script>
	<script src="assets/js/scripts/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="assets/js/main.js"></script>

</body>

</html>