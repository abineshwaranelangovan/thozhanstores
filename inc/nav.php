<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <link rel="stylesheet" href="path/to/font-awesome.css">
    <link rel="stylesheet" href="path/to/your-stylesheet.css">
    <style>
       .menu-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background-color: #333;
}

.menu-wrap .logo {
    flex: 1;
}

.menu-wrap .logo img {
    height: 50px; /* Adjust the size as needed */
}

.sf-menu {
    flex: 2;
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
}

.sf-menu li {
    margin: 0 15px;
}

.sf-menu a {
    color: #FF1493; /* Adjust to your core color */
    text-decoration: none;
    padding: 10px;
}

.sf-menu a:hover {
    background-color: #32CD32; /* Adjust to your core color */
}

.header-xtra {
    flex: 1;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

.header-xtra .icon {
    margin-left: 20px;
    color: white;
    cursor: pointer;
}

.search-block {
    display: none; /* Initially hidden */
    position: absolute;
    top: 60px; /* Adjust based on your navbar height */
    right: 20px;
    background: white;
    padding: 10px;
    border: 1px solid #ccc;
}

.search-block.active {
    display: block;
}
.title{
    color: white;
}
    </style>
</head>
<body>

<div class="menu-wrap">
    <div class="logo">
        <!-- <h2 class="title">Thozhan Stores</h2> -->
        <a href="index.php"><img src="img/thozha.png" class="img-responsive" alt=""/></a>
    </div>
    <ul class="sf-menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Shop</a>
            <ul>
                <?php
                    $catsql = "SELECT * FROM category";
                    $catres = mysqli_query($connection, $catsql);
                    while($catr = mysqli_fetch_assoc($catres)){
                ?>
                <li><a href="index.php?id=<?php echo $catr['id']; ?>"><?php echo $catr['name']; ?></a></li>
                <?php } ?>
            </ul>
        </li>
        <li><a href="#">My Account</a>
            <ul>
                <li><a href="my-account.php">My Orders</a></li>
                <li><a href="edit-address.php">Update Address</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <div class="header-xtra">
        <div class="icon">
            <i class="fa fa-user"></i>
        </div>
        <div class="icon search-icon">
            <i class="fa fa-search"></i>
        </div>
        <div class="search-block">
            <div class="ssc-inner">
                <form>
                    <input type="text" placeholder="Type Search text here...">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to toggle the search bar
    document.querySelector('.search-icon').addEventListener('click', function() {
        document.querySelector('.search-block').classList.toggle('active');
    });
</script>

</body>
</html>
