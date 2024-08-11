<?php
    ob_start();
    session_start();
    require_once 'config/connect.php';

    if (!isset($_SESSION['customer']) || empty($_SESSION['customer'])) {
        header('location: login.php');
        exit;
    }

    include 'inc/header.php'; 
    include 'inc/nav.php'; 

    if (!isset($_SESSION['customerid'])) {
        die("Error: Customer ID is not set in the session.");
    }

    $uid = $_SESSION['customerid'];
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    if (empty($cart)) {
        die("Error: Your cart is empty.");
    }

    // Calculate the total amount
    $total = 0;
    foreach ($cart as $key => $value) {
        $ordsql = "SELECT * FROM products WHERE id=$key";
        $ordres = mysqli_query($connection, $ordsql);
        $ordr = mysqli_fetch_assoc($ordres);
        $total += ($ordr['price'] * $value['quantity']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['agree']) && $_POST['agree'] == 'true') {
            $fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
            $lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
            $company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
            $address1 = filter_var($_POST['address1'], FILTER_SANITIZE_STRING);
            $address2 = filter_var($_POST['address2'], FILTER_SANITIZE_STRING);
            $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
            $state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
            $phone = filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT);
            $zip = filter_var($_POST['zipcode'], FILTER_SANITIZE_NUMBER_INT);

            $sql = "SELECT * FROM usersmeta WHERE uid=$uid";
            $res = mysqli_query($connection, $sql);
            $count = mysqli_num_rows($res);

            if ($count == 1) {
                // Update data in usersmeta table
                $usql = "UPDATE usersmeta SET firstname='$fname', lastname='$lname', address1='$address1', address2='$address2', city='$city', state='$state', zip='$zip', company='$company', mobile='$phone' WHERE uid=$uid";
                mysqli_query($connection, $usql) or die(mysqli_error($connection));
            } else {
                // Insert data in usersmeta table
                $isql = "INSERT INTO usersmeta (firstname, lastname, address1, address2, city, state, zip, company, mobile, uid) VALUES ('$fname', '$lname', '$address1', '$address2', '$city', '$state', '$zip', '$company', '$phone', '$uid')";
                mysqli_query($connection, $isql) or die(mysqli_error($connection));
            }

            // Prepare Razorpay order
            $razorpayOrder = [
                'amount' => $total * 100, // amount in paise
                'currency' => 'INR',
                'receipt' => 'rcptid_' . uniqid(),
                'payment_capture' => 1
            ];

            $razorpayOrderJson = json_encode($razorpayOrder);
            $ch = curl_init('https://api.razorpay.com/v1/orders');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode('rzp_test_VXg708tcWmMHMY:YOUR_RAZORPAY_SECRET')
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $razorpayOrderJson);
            $result = curl_exec($ch);

            if ($result === false) {
                die('cURL Error: ' . curl_error($ch));
            }

            curl_close($ch);
            $razorpayOrderResponse = json_decode($result, true);

            if (isset($razorpayOrderResponse['id'])) {
                $razorpayOrderId = $razorpayOrderResponse['id'];
            } else {
                die('Error: Unable to create Razorpay order');
            }
        }
    }

    $sql = "SELECT * FROM usersmeta WHERE uid=$uid";
    $res = mysqli_query($connection, $sql);
    $r = mysqli_fetch_assoc($res);
?>

<!-- SHOP CONTENT -->
<section id="content">
    <div class="content-blog">
        <div class="page_header text-center">
            <h2>Shop - Checkout</h2>
            <p>Get the best kit for smooth shave</p>
        </div>
        <form method="post">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="billing-details">
                            <h3 class="uppercase">Billing Details</h3>
                            <div class="space30"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>First Name </label>
                                    <input name="fname" class="form-control" type="text" value="<?php echo !empty($r['firstname']) ? $r['firstname'] : ''; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label>Last Name </label>
                                    <input name="lname" class="form-control" type="text" value="<?php echo !empty($r['lastname']) ? $r['lastname'] : ''; ?>">
                                </div>
                            </div>
                            <div class="clearfix space20"></div>
                            <label>Company Name</label>
                            <input name="company" class="form-control" type="text" value="<?php echo !empty($r['company']) ? $r['company'] : ''; ?>">
                            <div class="clearfix space20"></div>
                            <label>Address </label>
                            <input name="address1" class="form-control" type="text" value="<?php echo !empty($r['address1']) ? $r['address1'] : ''; ?>">
                            <div class="clearfix space20"></div>
                            <input name="address2" class="form-control" type="text" value="<?php echo !empty($r['address2']) ? $r['address2'] : ''; ?>">
                            <div class="clearfix space20"></div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>City </label>
                                    <input name="city" class="form-control" type="text" value="<?php echo !empty($r['city']) ? $r['city'] : ''; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label>State</label>
                                    <input name="state" class="form-control" type="text" value="<?php echo !empty($r['state']) ? $r['state'] : ''; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label>Postcode </label>
                                    <input name="zipcode" class="form-control" type="text" value="<?php echo !empty($r['zip']) ? $r['zip'] : ''; ?>">
                                </div>
                            </div>
                            <div class="clearfix space20"></div>
                            <label>Phone </label>
                            <input name="phone" class="form-control" type="text" value="<?php echo !empty($r['mobile']) ? $r['mobile'] : ''; ?>">
                        </div>
                    </div>
                </div>

                <div class="space30"></div>
                <h4 class="heading">Your order</h4>

                <table class="table table-bordered extra-padding">
                    <tbody>
                        <tr>
                            <th>Cart Subtotal</th>
                            <td><span class="amount">INR <?php echo $total; ?></span></td>
                        </tr>
                        <tr>
                            <th>Shipping and Handling</th>
                            <td>Free Shipping</td>
                        </tr>
                        <tr>
                            <th>Order Total</th>
                            <td><strong><span class="amount">INR <?php echo $total; ?></span></strong></td>
                        </tr>
                    </tbody>
                </table>

                <input type="submit" class="button btn-lg" value="Pay Now">
            </div>
        </form>
    </div>
</section>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.querySelector("input[type='submit']").addEventListener('click', function(e) {
        e.preventDefault();

        var razorpayOrderId = "<?php echo isset($razorpayOrderId) ? $razorpayOrderId : ''; ?>";
        var totalAmount = "<?php echo $total * 100; ?>"; // amount in paise

        if (!razorpayOrderId) {
            alert('Error: Razorpay Order ID is missing.');
            return;
        }

        var options = {
            "key": "rzp_test_VXg708tcWmMHMY", // Your Razorpay key ID
            "amount": totalAmount,
            "currency": "INR",
            "name": "Thozhan Stores",
            "description": "Purchase Description",
            "order_id": razorpayOrderId, // Ensure this is correctly set
            "handler": function (response) {
                alert("Payment Successful. Payment ID: " + response.razorpay_payment_id);
                // You can redirect or update the UI here
            },
            "prefill": {
                "name": "<?php echo $fname . ' ' . $lname; ?>",
                "email": "<?php echo $_SESSION['customer']; ?>",
                "contact": "<?php echo $phone; ?>"
            },
            "theme": {
                "color": "#F37254"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
    });
</script>

<?php include 'inc/footer.php'; ?>
