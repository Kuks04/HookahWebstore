<?php 
	session_start(); 
	if(!isset($_SESSION['cart'])){
		$_SESSION['cart'] = array();
	}
	$id=$_GET['id'];
	include './sql/connection.php';
	$select = "SELECT * FROM proizvod WHERE id = '" . $id . "'";
	$result = $conn->query($select);
    $value = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Nargila Shop 222 - <?php echo $value['name'];?></title>
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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
	<?php 
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
		if($s==0) {
			$b=array($_POST['id'], 1);
			array_push($_SESSION['cart'], $b);
			echo "<script>
			Swal.fire({
			  icon: 'success',
			  title: 'Proizvod dodat u korpu!',
			  showConfirmButton: false,
			  timer: 2000
			});	
			</script>";
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
		echo "<script> setTimeout(function() { window.location = './index.php#shop'; }, 2000);</script>";
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
					<li><a class="nav-link scrollto" href="./index.php#hero">Početna</a></li>
					<li><a class="nav-link scrollto" href="./index.php#about">O nama</a></li>
					<li><a class="nav-link scrollto " href="./index.php#shop">Shop</a></li>
					<li><a class="nav-link scrollto" href="./index.php#services">Usluge</a></li>
					<li><a class="nav-link scrollto" href="./index.php#contact">Kontakt</a></li>
				</ul>
				<a href="./cart.php"><i class="fa-solid fa-cart-shopping <?php if(!empty($_SESSION['cart'])) echo "fa-bounce"?> fa-xl ms-5 me-lg-0 me-3"></i></a>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
	</header><!-- End Header -->

	<main id="main">

		<!-- Breadcrumbs -->
		<section id="breadcrumbs" class="breadcrumbs">
			<div class="container">

				<div class="d-flex justify-content-between align-items-center">
					<h2>Detalji proizvoda</h2>
					<ol>
						<li><a href="./index.php#shop">Shop</a></li>
						<li>Detalji</li>
					</ol>
				</div>

			</div>
		</section><!-- End Breadcrumbs -->

		<!-- Shop Details -->
		<section id="shop-details" class="shop-details">
			<div class="container">

				<div class="row gy-4">

					<div class="col-lg-8">
						<div class="shop-details-img">
							<a href="./assets/img/shop/<?php echo $value['image']; ?>" data-gallery="shopGallery" class="shop-lightbox">
								<img src="./assets/img/shop/<?php echo $value['image']; ?>" alt="<?php echo $value['description'];?>">
							</a>
						</div>
					</div>

					<div class="col-lg-4">
						<div class="shop-info">
							<h3><?php echo $value['name']; ?></h3>
							<ul>
								<li><strong>Marka</strong>: <?php echo $value['brand']; ?></li>
								<li><strong>Kategorija</strong>: <?php echo $value['category']; ?></li>
								<li><strong>Cena</strong>: <?php echo $value['price']-1; ?> RSD</li>
							</ul>
						</div>
						<div class="shop-description">
							<h2>Opis</h2>
							<p>
							<?php echo $value['description']; ?>
							</p>
						</div>
						<form action="" method="POST">
							<input name="id" type="text" class="d-none" value="<?php echo $id;?>">
							<button class="btn w-100 bg-theme py-3" name="add-to-cart" type="submit">
								<i class="fa-solid fa-cart-plus fa-xl me-3"></i>Dodaj u korpu
							</button>
						</form>
					</div>

				</div>

			</div>
		</section><!-- End Shop Details -->

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
						<a href="./index.php#hero" class="scrollto">Početna</a>
						<a href="./index.php#about" class="scrollto">O nama</a>
						<a href="./index.php#services">Usluge</a>
						<a href="./index.php#shop">Shop</a>
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