
<head>
    <title>Ex6 - Project Sign Up Page by Christopher McArthur</title>
    <link rel="stylesheet" type="text/css" href="main.css">
    <script src="scripts.js"></script>
</head>
<body>
    <?php
		session_start();
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
		{
			$_SESSION['loggedin'] = false;
			session_unset();
			session_destroy();
			header("Location: index.php", true, 301);
		}
		else 
		{
			if ($_SERVER["REQUEST_METHOD"] == "GET") 
			{
				$error = "";
				
				if (empty($_GET["email"]))
				{
					$error = "Email Required!";
				}
				else if (empty($_GET["pass"]))
				{
					$error = "Password Required!";
				}
				else
				{
					$myfile = fopen("members.txt", "r") or die("Unable to open file!");
					
					while(!feof($myfile)) 
					{
						$line = fgets($myfile);
						$tokens = preg_split('/\s+/', $line);
						
							
						if(isset($tokens[0]) && isset($tokens[1]) && $tokens[0] == $_GET["email"] && $tokens[1] == $_GET["pass"])
						{
							$_SESSION['loggedin'] = true;
							break;
						}
					}
					fclose($myfile);
				}
			}
		}
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
        <h1>Login to your Account</h1>
        
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" onSubmit="checkRegister()">
			<?php echo $error . "<br>"?>
			<input type="text" id="email" name="email" placeholder="E-mail"><br>
			<input type="password" id="pass" name="pass" placeholder="Password"><br>
			<input id="submit" type="submit" value="Login">
		</form>

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
    <script type="text/javascript">document.getElementById("time").innerHTML = getCurrentDate();</script>