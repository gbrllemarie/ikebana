<?php
	include("api/database.php");
	session_start();
	setlocale(LC_MONETARY, "en_PH");

	if (!isset($_SESSION["userid"])) {
		// redirect to homepage if not logged in
		header("Location: /?login_error=You must be logged in to view this page.");
		die();
	} else if (!$_SESSION["userisadmin"]) {
		// redirect to homepage if not admin
		header("Location: /?login_error=You can't access this page.");
		die();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Manage Shop | Ikebana</title>

	<link href="https://fonts.googleapis.com/css?family=Megrim|Open+Sans:400,700" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/g/semantic-ui@2.2.6(semantic.min.css),jquery.slick@1.6.0(slick.css)">
	<link rel="stylesheet" href="css/app.css">

</head>
<body>
	<div id="manage">
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
				<h1>Manage Shop</h1>
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
			<div class="ui basic clearing segment">
				<div class="ui right floated blue button" onclick="$('#addproduct-modal').modal('toggle')">
					Add Product
				</div>
			</div>
			<table class="ui striped padded table">
				<thead>
					<tr>
						<th>Product Name</th>
						<th class="right aligned">Price Each</th>
						<th class="right aligned">In Stock</th>
						<th class="right aligned">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						// get all products
						$query = "SELECT * FROM products";
						$result = pg_query($query);
						while($row = pg_fetch_array($result)) { ?>
							<tr>
								<td>
									<?php echo $row["productname"]; ?>
								</td>
								<td class="right aligned">
									<?php echo money_format("%.2n", $row["priceeach"]); ?>
								</td>
								<td class="right aligned">
									<?php echo $row["instock"]; ?>
								</td>
								<td class="right aligned">
									<a class="ui label" onclick="
										$('#editproduct-modal input[name=productId]').val(<?php echo $row['id']; ?>);
										$('#editproduct-modal input[name=productName]').val('<?php echo $row['productname']; ?>');
										$('#editproduct-modal input[name=inStock]').val(<?php echo $row['instock']; ?>);
										$('#editproduct-modal input[name=priceEach]').val(<?php echo $row['priceeach']; ?>);
										$('#editproduct-modal').modal('toggle');
									">
										Edit
									</a>
									<a class="ui red label" onclick="$('#deleteproduct-modal input[name=productId]').val(<?php echo $row['id']; ?>); $('#deleteproduct-modal').modal('toggle');">
										Delete
									</a>
								</td>
							</tr>
						<?php }
					?>
				</tbody>
			</table>
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
			<button class="ui inverted green approve button" type="submit">Submit</button>
			<button class="ui inverted red cancel button">Cancel</button>
		</div>
	</form>
	<form method="POST" action="/api/addproduct.php" enctype="multipart/form-data" id="addproduct-modal" class="ui small basic modal form">
		<div class="header">Add Product</div>
		<div class="content">
			<div class="ui field">
				<label>Product Name</label>
				<input type="text" name="productName" placeholder="Product Name" required>
			</div>
			<div class="ui field">
				<label>Price Each</label>
				<input type="number" name="priceEach" placeholder="Price Each" value="0" required>
			</div>
			<div class="ui field">
				<label>In Stock</label>
				<input type="number" name="inStock" placeholder="In Stock" value="0" required>
			</div>
			<div class="ui field">
				<label>Product Image</label>
				<input type="file" accept="image/*" name="productImage" placeholder="Product Image">
			</div>
		</div>
		<div class="actions">
			<button type="button" class="ui inverted red cancel button">Cancel</button>
			<button type="submit" class="ui inverted green approve button">Add Product</button>
		</div>
	</form>

	<form method="POST" action="/api/editproduct.php" enctype="multipart/form-data" id="editproduct-modal" class="ui small basic modal form">
		<input type="hidden" name="productId">
		<div class="header">Edit Product</div>
		<div class="content">
			<div class="ui field">
				<label>Product Name</label>
				<input type="text" name="productName" placeholder="Product Name" required>
			</div>
			<div class="ui field">
				<label>Price Each</label>
				<input type="number" name="priceEach" placeholder="Price Each" required>
			</div>
			<div class="ui field">
				<label>In Stock</label>
				<input type="number" name="inStock" placeholder="In Stock" required>
			</div>
			<div class="ui field">
				<label>Product Image</label>
				<input type="file" accept="image/*" name="productImage" placeholder="Product Image">
			</div>
		</div>
		<div class="actions">
			<button type="button" class="ui inverted red cancel button">Cancel</button>
			<button type="submit" class="ui inverted green approve button">Modify Product</button>
		</div>
	</form>

	<form method="POST" action="/api/deleteproduct.php" id="deleteproduct-modal" class="ui small basic modal form">
		<input type="hidden" name="productId">
		<div class="header">Confirm Delete</div>
		<div class="content">
			Delete product?
		</div>
		<div class="actions">
			<button type="button" class="ui inverted red cancel button">Cancel</button>
			<button type="submit" class="ui red approve button">Delete Product</button>
		</div>
	</form>

	<script src="https://cdn.jsdelivr.net/g/jquery@3.1.1,semantic-ui@2.2.6,jquery.slick@1.6.0"></script>
	<script src="https://use.fontawesome.com/cce022efb7.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			//initialize modals
			$("#addproduct-modal").modal();
			$("#editproduct-modal").modal();
			$("#deleteproduct-modal").modal();
		});
	</script>
</body>
</html>