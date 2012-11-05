<?php
include_once 'includes.php';

usage($argv);   // check to see if they have pass args and show usage if not
    

$name = util::CommandLineOptionValue($argv, 'name','');
if ($name == '') exit();
if ($name == '*') $name = '';

$rank       = util::CommandLineOptionValue($argv, 'rank','');  // used to select only this rank.

$delim      = util::CommandLineOptionValue($argv, 'delim','|');      // default to | pipe as delimiter as comma is used a lot in tgext
$page_count = util::CommandLineOptionValue($argv, 'page_count',-1);  // default is  -1  download all pages
$pageSize   = util::CommandLineOptionValue($argv, 'page_size',100);  // default is 100 rows per page



$rowIndex = 0;

$chunk = species_names_by_page($name,$rowIndex,$pageSize);

echo implode($delim, array_keys($chunk[0]))."\n"; 

while (count($chunk) == $rowIndex) // loop while the number rows downloaded is the same as the page size
{            
    
    write_rows($chunk,$delim ,$rank);
    
    if ($page_count >= 0)
        if (($page_count -1 ) * $pageSize == $rowIndex) 
            exit();
    
    
    $rowIndex += $pageSize;
    $chunk = species_names_by_page($name,$rowIndex,$pageSize);
    
}
write_rows($chunk,$delim ,$rank); // write any bits left over



exit();

function write_rows($chunk,$delim ,$rank)
{
    
    foreach ($chunk as $row) 
    {

        if ($rank == '')
        {
            echo implode($delim, array_values($row))."\n";        
            continue;
        }

        if (trim($row['rank']) == $rank)
        {
            echo implode($delim, array_values($row))."\n";        
        }

    }
    
    
    
    
}


function species_names_by_page($name = "",$rowIndex = 0,$pageSize = 100) 
{

    
    $f = file_get_contents("http://biocache.ala.org.au/ws/webportal/species?q={$name}&pageSize={$pageSize}&start={$rowIndex}");
    
    $j = json_decode($f,true);
    
    return $j;
}


function usage($argv)
{
    
    if ( !(!isset($argv[1]) ||  $argv[1] == "--help" || $argv[1] == "-h")) return;

    echo " \n";
    
    echo " {$argv[0]} [--delim=|] [--rank=TaxanomicRank] --name=SpeciesName    ... list of species like \n";
    echo " {$argv[0]} [--delim=|] [--rank=TaxanomicRank] --name=*              ... list of all species \n";

    echo " default delimiter '|' \n";
    echo " \n";
    echo " --page_count=n  .... stop after this number of pages (useful for testing)\n";
    echo " --page_size=100 .... each page of data is this many rows\n";
    echo " \n";
    echo " --rank=
 value list
 ---------------
 class
 family
 genus
 infraorder
 kingdom
 order
 phylum
 rank
 species
 suborder
 subspecies
 superfamily
 superorder
 variety
\n";
    
    echo " \n";
    echo "A useful command line to list out the 'rank(s)'\n";
    echo "php get_species_names.php --name='*' --page_count=3 | cut -d'|' -f7 | sort | uniq \n";
    echo " \n";
    
    
    exit();    
}


?>
