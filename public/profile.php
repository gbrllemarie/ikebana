<?php
	include("api/database.php");
	session_start();
	date_default_timezone_set("Asia/Manila");
	setlocale(LC_MONETARY, "en_PH");

	if (!isset($_SESSION["userid"])) {
		// redirect to homepage if not logged in
		header("Location: /?login_error=You must be logged in to view this page.");
		die();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $_SESSION["username"] ?> | Ikebana</title>

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
		<div class="ui text container">
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

			<h1>Orders</h1>
			<?php
				// get all orders made by user
				$userId = $_SESSION["userid"];
				$orderQuery = "SELECT * FROM orders WHERE userid = $userId";

				$orderResult = pg_query($orderQuery);
				while($order = pg_fetch_array($orderResult)) { ?>
					<div class="ui fluid card">
						<div class="content">
							<div class="header">
								<?php echo $order["dateordered"]; ?>
							</div>
							<div class="meta">
								<div class="ui label">
									Order ID <?php echo $order["id"]; ?>
								</div>
							</div>
							<div class="description">
								<div class="ui header">Items Ordered</div>
								<table class="ui striped table">
									<thead>
										<tr>
											<th>Product Name</th>
											<th class="right aligned">Quantity Ordered</th>
											<th class="right aligned">Total Amount</th>
										</tr>
									</thead>
									<tbody>
										<?php
											// get all order details from this order
											$orderId = $order["id"];
											$detailsQuery = "SELECT * FROM orderdetails WHERE orderid = $orderId";

											$detailsResult = pg_query($detailsQuery);
											while($item = pg_fetch_array($detailsResult)) { ?>
											<tr>
												<td><?php echo $item["productname"]; ?></td>
												<td class="right aligned"><?php echo $item["quantityordered"]; ?></td>
												<td class="right aligned">Php <?php echo money_format("%.2n", $item["totalamount"]); ?></td>
											</tr>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<th></th>
											<th></th>
											<th class="right aligned">Php <?php echo money_format("%.2n", $order["totalamount"]); ?></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				<?php }
			?>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/g/jquery@3.1.1,semantic-ui@2.2.6,jquery.slick@1.6.0"></script>
	<script src="https://use.fontawesome.com/cce022efb7.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			//initialize modals
		});
	</script>
</body>
</html>