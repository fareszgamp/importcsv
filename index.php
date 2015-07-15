<?php  

require("libs/csvimport.php");

$filesarray = glob("csv/*.csv");

$a = new Csvimport();
$ab = $a->ImportCsvData($filesarray);

?> 