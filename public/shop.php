<?php
	include("api/database.php");
	session_start();
	setlocale(LC_MONETARY, "en_PH");
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Shop | Ikebana</title>

	<link href="https://fonts.googleapis.com/css?family=Megrim|Open+Sans:400,700" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/g/semantic-ui@2.2.6(semantic.min.css),jquery.slick@1.6.0(slick.css)">
	<link rel="stylesheet" href="css/app.css">

</head>
<body>
	<div id="shop">
		<div class="ui large secondary menu">
			<div class="item">Ikebana</div>
			<div class="right menu">
				<div class="item">login</div>
				<div class="item">register</div>
			</div>
		</div>
		<div class="ui stackable grid container">
			<div class="twelve wide column">
				<?php if (isset($_GET["success_message"])) { ?>
					<div class="ui success message">
						<?php echo $_GET["success_message"]; ?>
					</div>
				<?php } ?>
				<?php if (isset($_GET["error_message"])) { ?>
					<div class="ui error message">
						<?php echo $_GET["error_message"]; ?>
					</div>
				<?php } ?>
				<div class="ui two stackable cards">
					<?php
						// get all products
						$query = "SELECT * FROM products";
						$result = pg_query($query);
						while($row = pg_fetch_array($result)) {
							$id = $row["id"];
							$imgUrl = "images/products/default.png";
							if (file_exists("images/products/$id.png")) {
								$imgUrl = "images/products/$id.png";
							} else if (file_exists("images/products/$id.jpg")) {
								$imgUrl = "images/products/$id.jpg";
							}
						?>
						<div class="product card" style="background-image: url('<?php echo $imgUrl; ?>');">
							<div class="content">
								<div class="ui right floated label">
									Php <?php echo money_format("%.2n", $row["priceeach"]); ?>
								</div>
								<div class="header">
									<?php echo $row["productname"]; ?>
								</div>
								<div class="meta">
									<?php echo $row["instock"]; ?> left in stock
								</div>
							</div>
							<div class="extra content">
								<form method="POST" action="/api/addtocart.php" class="ui right floated right action input">
									<input type="hidden" value="<?php echo $row["id"]; ?>" name="id">
									<input type="hidden" value="<?php echo $row["productname"]; ?>" name="name">
									<input type="hidden" value="<?php echo $row["priceeach"]; ?>" name="priceEach">
									<input type="number" value="1" name="quantity">
									<button type="submit" class="ui inverted button">
										Add to Cart
									</button>
								</form>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class=" four wide column">
				<?php if (!isset($_SESSION["userid"])) { ?>
					<div class="ui secondary segment">
						Please login to access your cart.
					</div>
				<?php } else if (empty($_SESSION["usercart"])) { ?>
					<div class="ui secondary segment">
						Your cart is empty.
					</div>
				<?php } else { ?>
					<div class="ui segments">
						<div class="ui segment">
							Flower Basket
						</div>
						<div class="ui divided items segment">
							<?php $total = 0; foreach ($_SESSION["usercart"] as $id => $details) { ?>
								<div class="item">
									<div class="content">
										<div class="header">
											<?php echo $details["name"]; ?>
										</div>
										<div class="meta">
											<span class="quantity">
												<?php echo $details["quantity"]; ?> in cart
											</span>
										</div>
										<div class="extra">
											<form method="POST" action="/api/removefromcart.php" class="ui right floated left labeled tiny button">
												<input type="hidden" name="id" value="<?php echo $id; ?>">
												<div class="ui basic red right pointing label">
													Php <?php
														$itemTotal = $details["priceEach"] * $details["quantity"];
														$total += $itemTotal;
														echo money_format("%.2n", $itemTotal);
													?>
												</div>
												<button type="submit" class="ui red tiny button">
													Remove
												</button>
											</form>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="ui secondary segment">
							Total: Php <?php echo money_format("%.2n", $total); ?>
						</div>
					</div>
					<div class="ui large green fluid labeled icon button" onclick="$('#commitorder-modal').modal('toggle')">
						<i class="cart icon"></i>
						Place Order
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<!-- modals -->
	<form method="POST" action="/api/commitorder.php" id="commitorder-modal" class="ui basic modal form">
		<div class="ui fluid cancel button">Cancel</div>
		<button type="submit" class="ui fluid green approve button">Place Order</button>
	</form>

	<script src="https://cdn.jsdelivr.net/g/jquery@3.1.1,semantic-ui@2.2.6,jquery.slick@1.6.0"></script>
	<script src="https://use.fontawesome.com/cce022efb7.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			//initialize modals
			$("#commitorder-modal").modal();
		});
	</script>
</body>
</html>