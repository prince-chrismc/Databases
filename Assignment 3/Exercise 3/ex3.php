<?php
		if(isset($_COOKIE["Visits"]))
		{
            echo "<p>Hello, this is your " . $_COOKIE["Visits"] . " time visiting this website!</p>";
			
			if($_COOKIE["Visits"] > 1)
			{
				if(isset($_COOKIE["lastVisit"]))
				{
					echo "last visited on " . (date("d-m-y",$_COOKIE["lastVisit"])) . "at" . (date(" h:i e",$_COOKIE["lastVisit"])) . " time";
				}
			}
			setcookie("Visits", $_COOKIE["Visits"]+1, time() + (86400 * 30), "/");
			setcookie("lastVisit", time(), time() + (86400 * 365), "/");
		}
		else
		{
			echo "<p>Welcome! You are new here</p>";
			setcookie("Visits", 1, time() + (86400 * 365), "/"); // 86400 = 1 day
		}
	?>
