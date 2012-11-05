<?php
include_once 'includes.php';

usage($argv);   // check to see if they have passed args and show usage if not

$input = util::CommandLineOptionValue($argv, 'input','');
if ($input == '')
{
    echo "ERROR:: input is empty\n";
    usage($argv);
}

if ($input == "-")
{
    $text = '';
    $in = fopen('php://stdin', 'r');
    while(!feof($in)){
        $text = $text . fgets($in, 4096);
    }    
    
    $input_file = array_util::Trim(explode("\n", $text));
    
    fclose($in);
}
else
{
    if (!file_exists($input))
    {
        echo "ERROR:: can't find input [{$input}]\n";
        usage($argv);
    }
    else
    {
       $input_file = file($input);   // file exists read it in
    }
    
}




$output = util::CommandLineOptionValue($argv, 'output','');
if ($output == '') 
{
    echo "ERROR:: output is empty\n";
    usage($argv);
}

if (!is_dir($output)) 
{
    echo "ERROR:: output folder does not exist  [{$output}]\n";
    usage($argv);
}
    


$delim      = util::CommandLineOptionValue($argv, 'delim','|');         // default to | pipe as delimiter as comma is used a lot in tgext
$page_count = util::CommandLineOptionValue($argv, 'page_count',-1);     // default is  -1  download all pages
$pageSize   = util::CommandLineOptionValue($argv, 'page_size',500);     // default is 100 rows per page



if (count($input_file) <= 1)
{
    echo "ERROR:: Not enough lines in [{$input}]\n";
    usage($argv);
}

echo "read ".count($input_file)." from {$input}]\n";


for ($index = 1; $index < count($input_file); $index++)   // assume first line is header
{
    
    $name = trim($input_file[$index]);
    if ($name == "") continue;
    
    $output_filename = "{$output}/{$name}.txt";
    
    if (file_exists($output_filename)) 
    {
        echo "Skipping [{$output_filename}]\n";
        continue;
    }
    
    echo "$name\n";
    
    $name_tag = "--name={$name}";
    if (util::contains($name, ":lsid:")) $name_tag = "--lsid={$name}";
    
    $cmd = "php occurences.php {$name_tag} --delim='{$delim}'  --page_count={$page_count} --pageSize={$pageSize} > {$output_filename}";
    echo "$cmd\n";
    
    exec($cmd);
    
}



exit();

function usage($argv)
{
    
    if ( !(!isset($argv[1]) ||  $argv[1] == "--help" || $argv[1] == "-h")) return;    
    
    echo " \n";
    echo " {$argv[0]} [--delim=|] --input=filename --output=filename   \n";

    echo " \n";
    echo " --input=filename   .... (assumes first line is header) file containing list of species names if line starts  can be an LSID  \n";
    echo " --input=-          .... read list of names from STDIN \n";
    echo " \n"; 
    echo " --output=folder    .... folder where files will be written - each line of input will be used a folder name in side this folder  \n";
    echo " \n"; 
    echo " --delim='|'        .... column delimiter default '|'  \n";
    echo " \n"; 
    echo " --page_count=n   .... stop after this number of pages (useful for testing)\n";
    echo " --page_size=100  .... each page of data is this many rows (tested to work up to 500 rows - becomes less responsive over this)\n";
    echo " \n";
    echo " \n";
    
    exit();    
    
    
}



?>
