<head>
    <title>Ex6 - Project Sign Up Page by Christopher McArthur</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <script src="scripts.js"></script>
</head>
<body>
    <?php
		session_start();
    ?>
    
  <header> 
        <h1>eShop</h1>
        <h3 id="time">Day, month date year</h3>
    </header>
    
    <nav>
        <ul>
            <li><a href="index.php">Home </a></li>
            <li><a href="cart.php">Cart </a></li>
            <li><a href="login.php">
                <?php 
					if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) 
					{
						echo "Log Out";
					}
					else
					{
						echo "Login";
					}
				?>
                </a></li>
            <li><a href="signUp.php">Sign Up</a></li>
        </ul>
    </nav>
    
    
    
    <content>
        <h1>Your Cart</h1>
    </content>
    
    <footer>
                
                <h5>Information</h5>
                <ul class="list-unstyled">
                <li><a href="#">About Us</a></li>
                <li><a href="#">Delivery Information</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms &amp; Conditions</a></li>
                </ul>
                
                
                <h5>Customer Service</h5>
                <ul>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Returns</a></li>
                <li><a href="#">Site Map</a></li>
                </ul>
                
                <h5>Extras</h5>
                <ul>
                <li><a href="#">Brands</a></li>
                <li><a href="#">Gift Vouchers</a></li>
                <li><a href="#">Affiliates</a></li>
                <li><a href="#">Specials</a></li>
                </ul>
               
                <h5>My Account</h5>
                <ul>
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Order History</a></li>
                    <li><a href="#">Wish List</a></li>
                    <li><a href="#">Newsletter</a></li>
                </ul>
                
                <hr>
                <p>Powered By <a href="#">Chrisstopher McArthur</a><br> eShop © 2016</p>
                
</footer>