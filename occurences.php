<?php
include_once 'includes.php';

if (!isset($argv[1]) ||  $argv[1] == "--help" || $argv[1] == "-h")
{
    echo " {$argv[0]} [--delim=|] name=SpeciesName       .... occurence points for this species \n";
    echo " {$argv[0]} [--delim=|] name=lsid:(ALA LSID)   .... use the accurate LSID from the species list\n";

    echo " --delim='|'    ... column delimiter \n";
    echo " default delimiter '|' \n";
    echo " \n";
    echo " --page_count=n  .... stop after this number of pages (useful for testing)\n";
    echo " --page_size=100 .... each page of data is this many rows (tested to work up to 500 rows - becomes less responsive over this)\n";
    echo " \n";
    echo " \n";
    
    exit();
}

$name = util::CommandLineOptionValue($argv, 'name','');
if ($name == '') exit();
if ($name == '*') $name = '';

$delim = util::CommandLineOptionValue($argv, 'delim','|');

$page_count = util::CommandLineOptionValue($argv, 'page_count',-1);
$pageSize  = util::CommandLineOptionValue($argv, 'page_size',100);


$DB = new DBO();

$pageNumber = 0;

// field names to keep 
$f = array(); 
$f['basisOfRecord'] = '';
$f['raw_scientificName'] = '';
$f['decimalLatitude'] = '';
$f['decimalLongitude'] = '';
$f['geospatialKosher'] = '';
$f['year'] = '';
$f['month'] = '';



$chunk = $DB->occurrences_by_page($name,0,1); // make one read to get number of records
$totalRecords = $chunk['totalRecords'];

// calc n umber pages required 
$total_pages = $totalRecords / $pageSize;

echo "totalRecords = $totalRecords   total_pages = $total_pages\n";

$chunk = $DB->occurrences_by_page($name,$pageNumber,$pageSize);


echo join($delim,array_keys($f))."\n";  // header

while ($pageNumber < $totalRecords) // lop while the size of the hunk is as big as the page
{            
    
    foreach ($chunk['occurrences'] as $occurrence) 
    {
        foreach (array_keys($f) as $column_key) 
            echo array_util::Value($occurrence, $column_key, null, true).$delim;

        echo "\n";

    }
    
    if ($page_count >= 0)
    {
        if (($page_count -1 ) * $pageSize == $pageNumber) exit();
    }
    
    $pageNumber += $pageSize;
    $chunk = $DB->occurrences_by_page($name,$pageNumber,$pageSize);
    
}


unset($DB);


?>
