<?php 
	ob_start();
	session_start();
	require_once 'config/connect.php';

	if (!isset($_SESSION['customer']) && empty($_SESSION['customer'])) {
		header('location: login.php');
		exit();
	}

	include 'inc/header.php'; 
	include 'inc/nav.php'; 

	$uid = $_SESSION['customerid'];
?>

<!-- SHOP CONTENT -->
<section id="content">
	<div class="content-blog content-account">
		<div class="container">
			<div class="row">
				<div class="page_header text-center">
					<h2>My Account</h2>
				</div>
				<div class="col-md-12">
					<h3>Recent Orders</h3>
					<br>
					<table class="cart-table account-table table table-bordered">
						<thead>
							<tr>
								<th>Product Name</th>
								<th>Quantity</th>
								<th>Price</th>
								<th></th>
								<th>Total Price</th>
							</tr>
						</thead>
						<tbody>
						<?php
							// Check if 'id' is set in the GET request
							if (isset($_GET['id']) && !empty($_GET['id'])) {
								$oid = $_GET['id'];
							} else {
								header('location: my-account.php');
								exit();
							}

							// Fetch order details
							$ordsql = "SELECT * FROM orders WHERE uid='$uid' AND id='$oid'";
							$ordres = mysqli_query($connection, $ordsql);

							if (!$ordres) {
								die("Query failed: " . mysqli_error($connection));
							}

							$ordr = mysqli_fetch_assoc($ordres);

							// Fetch order items
							$orditmsql = "SELECT * FROM orderitems o JOIN products p ON o.pid = p.id WHERE o.orderid = '$oid'";
							$orditmres = mysqli_query($connection, $orditmsql);

							if (!$orditmres) {
								die("Query failed: " . mysqli_error($connection));
							}

							while ($orditmr = mysqli_fetch_assoc($orditmres)) {
						?>
							<tr>
								<td>
									<a href="single.php?id=<?php echo $orditmr['pid']; ?>"><?php echo substr($orditmr['name'], 0, 25); ?></a>
								</td>
								<td><?php echo $orditmr['pquantity']; ?></td>
								<td>INR <?php echo $orditmr['productprice']; ?>/-</td>
								<td></td>
								<td>INR <?php echo $orditmr['productprice'] * $orditmr['pquantity']; ?>/-</td>
							</tr>
						<?php 
							}
						?>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td>Order Total</td>
								<td><?php echo $ordr['totalprice']; ?></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td>Order Status</td>
								<td><?php echo $ordr['orderstatus']; ?></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td>Order Placed On</td>
								<td><?php echo $ordr['timestamp']; ?></td>
							</tr>
						</tbody>
					</table>

					<br><br><br>

					<div class="ma-address">
						<h3>My Addresses</h3>
						<p>The following addresses will be used on the checkout page by default.</p>
						<div class="row">
							<div class="col-md-6">
								<h4>My Address <a href="edit-address.php">Edit</a></h4>
								<?php
									$csql = "SELECT u1.firstname, u1.lastname, u1.address1, u1.address2, u1.city, u1.state, u1.country, u1.company, u.email, u1.mobile, u1.zip 
											 FROM users u 
											 JOIN usersmeta u1 
											 ON u.id = u1.uid 
											 WHERE u.id = $uid";
									$cres = mysqli_query($connection, $csql);

									if ($cres && mysqli_num_rows($cres) == 1) {
										$cr = mysqli_fetch_assoc($cres);
										echo "<p>" . $cr['firstname'] . " " . $cr['lastname'] . "</p>";
										echo "<p>" . $cr['address1'] . "</p>";
										echo "<p>" . $cr['address2'] . "</p>";
										echo "<p>" . $cr['city'] . "</p>";
										echo "<p>" . $cr['state'] . "</p>";
										echo "<p>" . $cr['country'] . "</p>";
										echo "<p>" . $cr['company'] . "</p>";
										echo "<p>" . $cr['zip'] . "</p>";
										echo "<p>" . $cr['mobile'] . "</p>";
										echo "<p>" . $cr['email'] . "</p>";
									} else {
										echo "No address found.";
									}
								?>
							</div>
							<div class="col-md-6"></div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<?php include 'inc/footer.php'; ?>
