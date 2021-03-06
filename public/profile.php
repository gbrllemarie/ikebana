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
		<header class="ui secondary menu">
			<a href="/" class="title item">
				ikebana
			</a>
			<div class="right menu">
				<div class="item">
					<a class="ui secondary button" href="/shop.php">
						Visit Shop
					</a>
				</div>
				<?php if ($_SESSION["userisadmin"]) { ?>
					<div class="item">
						<a href="/manage.php" class="ui secondary button">
							Manage Shop
						</a>
					</div>
				<?php } ?>
				<div class="divider"></div>
				<div class="item">
					<a href="/profile.php" class="ui secondary button">
						<?php echo $_SESSION["username"]; ?>
					</a>
				</div>
				<div class="item">
					<a href="/api/logout.php" class="ui secondary button">Logout</a>
				</div>
			</div>
		</header>

		<div class="masthead">
			<div class="ui text container">
				<h1>
					<?php echo $_SESSION["username"]; ?>
					<a class="ui blue label" onclick="$('#editprofile-modal').modal('toggle')">
						Edit Profile
					</a>
				</h1>
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

	<!-- modals -->
	<form id="editprofile-modal" class="ui small basic modal form" method="POST" action="/api/editprofile.php">
		<div class="header">Edit Profile</div>
		<div class="content">
			<div class="ui field">
				<label>Username</label>
				<input type="text" name="username" value="<?php echo $_SESSION["username"]; ?>">
			</div>
			<div class="ui stackable equal width grid">
				<div class="column field">
					<label>Current Password</label>
					<input type="password" name="password" required>
				</div>
				<div class="column field">
					<label>New Password (leave blank to keep old password)</label>
					<input type="password" name="newPassword">
				</div>
			</div>
		</div>
		<div class="actions">
			<button class="ui inverted red cancel button" type="button">Cancel</button>
			<button class="ui inverted green approve button" type="submit">Update Profile</button>
		</div>
	</form>

	<script src="https://cdn.jsdelivr.net/g/jquery@3.1.1,semantic-ui@2.2.6,jquery.slick@1.6.0"></script>
	<script src="https://use.fontawesome.com/cce022efb7.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			//initialize modals
			$("#editprofile-modal").modal();
		});
	</script>
</body>
</html>