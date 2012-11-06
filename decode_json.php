<?php
include_once 'includes.php';

/*
 * decode a  JSON format file into a PHP print_r 
 * 
 * yes the programming in here could be way more efficient but it would be much harder to read.
 * so if you are going to use properly - please look at the IF statements and other loops
 * 
 */

usage($argv);   // check to see if they have passed args and show usage if not


$input = util::CommandLineOptionValue($argv, 'input','');     // default is  -1  download all pages
if (!file_exists($input)) 
{
    echo "ERROR: input file does not exist [{$input}] \n";
    exit();
}


$output = util::CommandLineOptionValue($argv, 'output','');     // default is  -1  download all pages
if (file_exists($output)) 
{
    echo "ERROR: output file already exists [{$output}] \n";
    exit();
}


if ($output != '') echo "read: $input\n";
$file = file_get_contents($input);

if ($output != '') echo "decode\n";
$json = json_decode($file,true);


if ($output == '')
    print_r($json);
else
{
    echo "write $output\n";
    file_put_contents($output, print_r($json,true));
}
    


exit();

// =======================================================================================
// ========= FUNCTIONS ===================================================================
// =======================================================================================



function usage($argv)
{
    
    if ( !(!isset($argv[1]) ||  $argv[1] == "--help" || $argv[1] == "-h")) return;    
    
    echo " \n";
    echo " php {$argv[0]} --input=some.json --output=fred.txt    .... read some.json and save as  fred.txt \n";
    echo " \n";
    
    exit();    
    
    
}



?>
