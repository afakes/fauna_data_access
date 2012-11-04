<?php
include_once 'includes.php';

if ($argv[1] == "--help" || $argv[1] == "-h")
{
    echo " {$argv[0]} [--delim=|] [--rank=TaxanomicRank] name=SpeciesName    ... list of species like \n";
    echo " {$argv[0]} [--delim=|] [--rank=TaxanomicRank] name=*              ... list of all species \n";

    echo " default delimiter '|' \n";
    echo " \n";
    echo " page_count=n  .... stop after this number of pages (useful for testing)\n";
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

$name = util::CommandLineOptionValue($argv, 'name','');
if ($name == '') exit();
if ($name == '*') $name = '';

$delim = util::CommandLineOptionValue($argv, 'delim','|');

// used to select only this rank.
$rank = util::CommandLineOptionValue($argv, 'rank','');

$page_count = util::CommandLineOptionValue($argv, 'page_count',-1);

$DB = new DBO();

$pageSize = 100;

$pageNumber = 0;

$chunk = $DB->species_names_by_page($name,$pageNumber,$pageSize);

echo implode($delim, array_keys($chunk[0]))."\n"; 

while (count($chunk) == $pageSize) // lop while the size of the hunk is as big as the page
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
    
    
    if ($page_count >= 0)
    {
        if ($page_count * $pageSize == $pageNumber) exit();
    }
    
    $pageNumber += $pageSize;
    $chunk = $DB->species_names_by_page($name,$pageNumber,$pageSize);
    
}


unset($DB);


?>
