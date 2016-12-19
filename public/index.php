<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<title>Ikebana</title>

	<link href="https://fonts.googleapis.com/css?family=Megrim|Open+Sans:400,700" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/g/semantic-ui@2.2.6(semantic.min.css),jquery.slick@1.6.0(slick.css)">
	<link rel="stylesheet" href="css/app.css">

</head>
<body>
	<div id="homepage">
		<header class="ui secondary menu">
			<div class="right menu">
				<?php if (isset($_SESSION["userid"])) { ?>
					<div class="item">
						<a href="/profile.php" class="ui black label">
							Welcome, <strong><?php echo $_SESSION["username"]; ?></strong>.
						</a>
					</div>
					<?php if ($_SESSION["userisadmin"]) { ?>
						<div class="item">
							<a class="ui secondary button" href="/manage.php">
								Manage Shop
							</a>
						</div>
					<?php } ?>
					<div class="item">
						<a href="/api/logout.php" class="ui secondary button">Logout</a>
					</div>
				<?php } else { ?>
					<?php if (isset($_GET["logout_success"])) { ?>
						<div class="item">
							<div class="ui right pointing green label">
								<?php echo $_GET["logout_success"]; ?>
							</div>
						</div>
					<?php } ?>
					<div class="item">
						<div class="ui secondary button" onClick="$('#login-modal').modal('toggle');">
							Login
						</div>
					</div>
					<?php if (isset($_GET["login_error"])) { ?>
						<div class="item">
							<div class="ui left pointing red label">
								<?php echo $_GET["login_error"]; ?>
							</div>
						</div>
					<?php } ?>
					<div class="item">
						<div class="ui secondary button" onClick="$('#register-modal').modal('toggle');">
							Register
						</div>
					</div>
					<?php if (isset($_GET["register_error"])) { ?>
						<div class="item">
							<div class="ui left pointing red label">
								<?php echo $_GET["register_error"]; ?>
							</div>
						</div>
					<?php } ?>
					<?php if (isset($_GET["register_success"])) { ?>
						<div class="item">
							<div class="ui left pointing green label">
								<?php echo $_GET["register_success"]; ?>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
				<div class="divider"></div>
				<div class="item">
					<a class="ui primary button" href="/shop.php">
						Visit Shop
					</a>
				</div>
			</div>
		</header>
		<div id="homepage-carousel">
			<div class="slide slide1"></div>
			<div class="slide slide2"></div>
			<div class="slide slide3"></div>
		</div>
		<div id="title">
			Ikebana
		</div>
		<div id="subtitle">
			Flower Shop
		</div>
	</div>

	<!-- modals -->
	<form id="login-modal" class="ui small basic modal form" method="POST" action="/api/login.php">
		<div class="header">Login</div>
		<div class="content">
			<div class="ui stackable equal width grid">
				<div class="column field">
					<label>Username</label>
					<input type="text" name="username">
				</div>
				<div class="column field">
					<label>Password</label>
					<input type="password" name="password">
				</div>
			</div>
		</div>
		<div class="actions">
			<button class="ui inverted red cancel button" type="button">Cancel</button>
			<button class="ui inverted green approve button" type="submit">Submit</button>
		</div>
	</form>
	<form id="register-modal" class="ui small basic modal form" method="POST" action="/api/register.php">
		<div class="header">Register</div>
		<div class="content">
			<div class="ui stackable equal width grid">
				<div class="column field">
					<label>Username</label>
					<input type="text" name="username">
				</div>
				<div class="column field">
					<label>Password</label>
					<input type="password" name="password">
				</div>
			</div>
		</div>
		<div class="actions">
			<button class="ui inverted red cancel button" type="button">Cancel</button>
			<button class="ui inverted green approve button" type="submit">Submit</button>
		</div>
	</form>

	<script src="https://cdn.jsdelivr.net/g/jquery@3.1.1,semantic-ui@2.2.6,jquery.slick@1.6.0"></script>
	<script src="https://use.fontawesome.com/cce022efb7.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			// homepage carousel setup
			$("#homepage-carousel").slick({
				autoplay: true,
				autoplaySpeed: 3000,
				arrows: true,
				prevArrow: "<div class='slick-prev'><i class='fa fa-angle-double-left'></i></div>",
				nextArrow: "<div class='slick-next'><i class='fa fa-angle-double-right'></i></div>",
				cssEase: "cubic-bezier(0.770, 0.000, 0.175, 1.000)",
				infinite: true,
				pauseOnFocus: false,
				pauseOnHover: false,
				centerMode: true,
				centerPadding: "0",
				speed: 750
			});

			//initialize modals
			$("#login-modal").modal();
			$("#register-modal").modal();
		});
	</script>

</body>
</html>