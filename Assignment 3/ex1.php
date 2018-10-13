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
        $strArray = count_chars($str,1);
        foreach ($strArray as $key=>$value)
        {
            echo "The character <b>'".chr($key)"'</b> was found $value time(s)<br>";
        }
    }
    
    function uppercaseFirst($str)
    {
        return ucfirst($str);
    }
    
    function splitSort($str)
    {
       $words = explode(" ", $str);
        sort($array);
        $sorted = "";
        return implode($array);
    }
    function dayAfterTomorrow()
    {
        return strtotime("+2 days");
    }
    function findUnique($array)
    {
        return array_unique($array);
    }
    function formatHash($array)
    {
        return;
    }
?>
-->

    
    <h1> factorial of 56 is:</h1> <!--<?php  echo "hello:" factorial(56);?>-->
    <h1> upper casing the first word: <?php  echo uppercaseFirst("upper casing the first word");?></h1>
    <h1> The most frequent of "hello my name is sam": <?php  echo mostFrequent("hello my name is sam")?></h1>
    <h1> sorting of "the cat ran from the dog": <?php  echo splitSort("the cat ran from the dog");?></h1>
    <h1> factorial of 56 is: <?php  echo  . factorial(56)?></h1>
    <h1> factorial of 56 is: <?php  echo  . factorial(56)?></h1>
    <h1> factorial of 56 is: <?php  echo  . factorial(56)?></h1>




</body>
