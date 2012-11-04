<?php
include_once 'includes.php';

$name = util::CommandLineOptionValue($argv, 'name','');
if ($name == '') exit();
if ($name == '*') $name = '';

$delim = util::CommandLineOptionValue($argv, 'delim','|');

$DB = new DBO();

$all = $DB->species_names_all($name,100);

//print_r($all);

//echo implode($delim, array_keys($all[0]))."\n";
//
//foreach ($all as $row) 
//{
//    echo implode($delim, array_values($row))."\n";    
//}

unset($DB);


?>
