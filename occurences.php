<?php
include_once 'includes.php';

usage($argv);   // check to see if they have passed args and show usage if not


$name = util::CommandLineOptionValue($argv, 'name','');
if ($name == '*') $name = '';

$lsid = util::CommandLineOptionValue($argv, 'lsid','');
if ($lsid != '') $name = "lsid:$lsid"; 

if ($name == '') 
{
    echo "ERROR: name or lsid not specified \n";
    usage($argv);
}



$delim      = util::CommandLineOptionValue($argv, 'delim','|');         // default to | pipe as delimiter as comma is used a lot in tgext
$page_count = util::CommandLineOptionValue($argv, 'page_count',-1);     // default is  -1  download all pages
$pageSize   = util::CommandLineOptionValue($argv, 'page_size',500);     // default is 100 rows per page

$info_only   = util::CommandLineOptionValue($argv, 'info_only',false);  // return info about the search BUT nosearch results

$HumanObservation    = util::CommandLineOptionValue($argv, 'HumanObservation',false);  // return info about the search BUT nosearch results



$chunk = occurrences_by_page($name,0,1); // make one read to get number of records
$totalRecords = $chunk['totalRecords'];


if ($info_only)
{
    print_r($chunk);  // just display information and quit
    exit();
}

$pageNumber = 0;
$chunk = occurrences_by_page($name,$pageNumber,$pageSize);

echo "scientific_name{$delim}lat{$delim}lng{$delim}occurrence_date\n";  // header

while ($pageNumber < $totalRecords) // lop while the size of the hunk is as big as the page
{            
    
    foreach ($chunk['occurrences'] as $occurrence) 
    {
        
        if (array_util::Value($occurrence, 'geospatialKosher') != "true")
            continue; // check to see if even ALA belives that the point is valid
        
        
        if ($HumanObservation)  // if they have asked to check only for HumanObservation's
            if (array_util::Value($occurrence, 'basisOfRecord') != "HumanObservation")
                continue; // check to see if even ALA belives that the point is valid

        echo "".array_util::Value($occurrence, 'basisOfRecord')."{$delim}{$occurrence['scientificName']}{$delim}{$occurrence['decimalLatitude']}{$delim}{$occurrence['decimalLongitude']}{$delim}" .array_util::Value($occurrence, 'year'). "-".array_util::Value($occurrence, 'month')."-01\n";
        
        
    }
    
    
    if ($page_count >= 0)
        if (($page_count -1 ) * $pageSize == $pageNumber) exit();
    
    $pageNumber += $pageSize;
    $chunk = occurrences_by_page($name,$pageNumber,$pageSize);
    
}

exit();



// =======================================================================================
// ========= FUNCTIONS ===================================================================
// =======================================================================================


function occurrences_by_page($name = "",$pageNumber = 0,$pageSize = 100) 
{
    
    $url = "http://biocache.ala.org.au/ws/webportal/occurrences?q={$name}&pageSize={$pageSize}&start={$pageNumber}";
    
    $f = @file_get_contents($url);
    
    if ($f === FALSE) // tried once and failed
    {
        $error_count = 0;
        while ($error_count < 5 && $f === FALSE)      // lets try up to 5 times to get this segment
        {
            $f = @file_get_contents($url);
        }
        
        if ($f === FALSE) 
        {
            echo "FAILED:: could not get occurrences data from [{$url}]";
            exit();
        }
            
            
    }
    
    
    $j = json_decode($f,true);

    return  $j;

}



function usage($argv)
{
    
    if ( !(!isset($argv[1]) ||  $argv[1] == "--help" || $argv[1] == "-h")) return;    
    
    echo " \n";
    echo " {$argv[0]} [--delim=|] --name=SpeciesName       .... occurence points for this species \n";
    echo " {$argv[0]} [--delim=|] --name=lsid:(ALA LSID)   .... use the accurate LSID from the species list\n";

    echo " --delim='|'              .... column delimiter default '|'  \n";
    echo " \n";
    echo " --page_count=-1          .... stop after this number of pages (useful for testing)\n";
    echo " --page_size=100          .... each page of data is this many rows (tested to work up to 500 rows - becomes less responsive over this)\n";
    echo " \n";
    echo " --info_only=true         .... disp;ay information about the results but NOT the results\n";
    echo " --HumanObservation=false .... disp;ay information about the results but NOT the results\n";
    echo " \n";
    
    
    
    exit();    
    
    
}



?>
