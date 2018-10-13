<head>
    <title>Exerciese 1: PHP Functions</title>
</head>
<body>
<!--
<?php
    /* Function to get Factorial of a Number */
    function factorial($num)
    {
        $fact = 1;
        for($i = 1; $i <= $num ;$i++)
            $fact = $fact * $i;
        return $fact;
    }
    
    function mostFrequent($str)
    {
        $str = str_replace(' ', '', $str);
        $instance = 0;
        $char = "";
        foreach (count_chars($str, 1) as $i => $val) 
        {
            if($val > $instance)
            {
                $instance = $val;
                $char = chr($i);
            }
           
        }
        echo "There were $instance instance(s) of \"" ,$char,"\" in the string.\n";
    }
    
    function uppercaseFirst($str)
    {
        return ucfirst($str);
    }
    
    function splitSort($str)
    {
        $words = explode(" ", $str);
        sort($words);
        $sorted = "";
        return implode($words);
    }
    function dayAfterTomorrow()
    {
        $date = new DateTime('+2day');
        return $date->format("d/m/y");
    }
    function findUnique($array)
    {
        $result = array_unique($array);
        
        foreach($result as $elem) {echo $elem . ", "; }
    }
    function formatHash($array)
    {
        asort($array);
		
		foreach($array as $key=>$value)
		{
			echo "<p>Name: $key, Price: $value</p>";
		}
    }
?>
-->

    
    <h1> factorial of 56 is: <?php  echo factorial(6);?></h1>
    <h1> upper casing the first word: <?php  echo uppercaseFirst("upper casing the first word");?></h1>
    <h1> The most frequent of "hello my name is sam": <?php  echo mostFrequent("hello my name is sam")?></h1>
    <h1> sorting of "the cat ran from the dog": <?php  echo splitSort("the cat ran from the dog");?></h1>
    <h1> day after tomorrow: <?php  echo  dayAfterTomorrow();?></h1>
    <h1> finding unique elemts 1,2,3,2,1,4: <?php  echo  findUnique(array("1","2","3","2","1","4"));?></h1>
    <h1> formatHash(): </h1> <br/><?php $array=array("john"=>1, "jane"=>2, "jay"=>3, "jack"=>1, "joe"=>1, "josh"=>2, "jill"=>4); formatHash($array);?>





</body>
