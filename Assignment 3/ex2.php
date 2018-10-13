<html>
<head>
    <title>PHP work! Exercise 2</title>
    
    </head>
    <body>
        <html>
<head>
	<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}

</style>
</head>
<body>
	<table>
		<tr>
	    	<th>Name</th>
	    	<th>
	    		<!--<?php
	    			if(isset($_GET["name"]))
	    			{
	    				echo $_GET["name"];
	    			}
	    			else
	    			{
	    				echo "n/a";
	    			}
	    		?>-->
	    	</th> 
	  	</tr>
	  	<tr>
	    	<th>Age</th>
	    	<th>
	    		<?php
	    			if(isset($_GET["age"]))
	    			{
	    				echo $_GET["age"];
	    			}
	    			else
	    			{
	    				echo "n/a";
	    			}
	    		?>
	    	</th> 
	  	</tr>
	  	<tr>
	    	<th>Phone Number</th>
	    	<th>
	    		<?php
	    			if(isset($_GET["number"]))
	    			{
	    				echo $_GET["number"];
	    			}
	    			else
	    			{
	    				echo "n/a";
	    			}
	    		?>
	    	</th> 
	  	</tr>
  	</table>
        
        
        
        
        
        
        
        <?php
if (isset($_GET['link'])) {
    echo $_GET['link'];
}else{
    // Fallback behaviour goes here
}?>
        <!--
        <?php
        $str = "<h1>Hello world!</h1>";
        echo $str;
        echo("<br>What a nice day!");
        ?>
        -->
        
        
        
        
    <?php echo "hello world!";?>
    <!--

    <?php
        $name = $_GET['name'];
        $age = $_GET['age'];
        $num = $_GET['number'];

        echo "$name who is  $age has the number $num";
    ?>-->
    
    </body>



</html>