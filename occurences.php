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
    exit();
}


$info_only  = util::CommandLineOptionValue($argv, 'info_only',false);  // return info about the search BUT nosearch results
if ($info_only)
{
    
    $url = "http://biocache.ala.org.au/ws/webportal/occurrences?q={$name}&pageSize=1&start=0";
    $f = @file_get_contents($url);
    $j = @json_decode($f,true);
    
    print_r($j);  // just display information and quit
    exit();
}

$totalRecords = record_count($name);

$count_only = util::CommandLineOptionValue($argv, 'count_only',false);  // return info about the search BUT nosearch results
if ($count_only)
{
    echo "count={$totalRecords}\n";
    exit();
}


$output_folder = util::CommandLineOptionValue($argv, 'output_folder','');
if ($output_folder == '') 
{
    echo "ERROR: output_folder not specified \n";
    usage($argv);
    exit();
}


$page_count = util::CommandLineOptionValue($argv, 'page_count',-1);     // default is  -1  download all pages
$pageSize   = util::CommandLineOptionValue($argv, 'page_size',50000);   // default is 50,000 rows per page for GZ download this is good





$pageNumber = 0;

while ($pageNumber < $totalRecords) // loop while the size of the hunk is as big as the page
{            

    $chunk_result = occurrences_by_page($name,$output_folder,$pageNumber) ;
    if (is_null($chunk_result))
    {
        echo "FAILED:: download data for {$name} \n";
        exit();   
    }
    
    if ($page_count >= 0)
        if (($page_count -1 ) * $pageSize == $pageNumber) exit();
    
    $pageNumber += $pageSize;
    
}

exit();



// =======================================================================================
// ========= FUNCTIONS ===================================================================
// =======================================================================================


function occurrences_by_page($name,$output_folder,$pageNumber = 0,$pageSize = 50000,$fileds = "longitude,latitude,year,month,raw_taxon_name,names_and_lsid") 
{
    
    $url="http://biocache.ala.org.au/ws/webportal/occurrences.gz?&q={$name}&fl={$fileds}&pageSize={$pageSize}&start={$pageNumber}";

    $output_filename = "{$output_folder}/{$name}_".sprintf("%08d",$pageNumber).".gz";
    
    $output_filename = str_replace("//", "/", $output_filename);
    
    echo "$output_filename , $url\n";
    
    if (file_exists($output_filename)) return  $output_filename;
    
    
    return  file::wget($url, $output_filename,true);

}

function record_count($name)
{
    
    $url = "http://biocache.ala.org.au/ws/webportal/occurrences?q={$name}&pageSize=1&start=0";
    $f = @file_get_contents($url);
    $j = @json_decode($f,true);
    
    return  array_util::Value($j, 'totalRecords', -1);
    
}


function usage($argv)
{
    
    if ( !(!isset($argv[1]) ||  $argv[1] == "--help" || $argv[1] == "-h")) return;    
    
    echo " \n";
    echo " {$argv[0]} --name=SpeciesName      --output_folder=foldername     .... occurence points for this name and place gz files in this folder\n";
    echo " {$argv[0]} --name=lsid:(ALA LSID)  --output_folder=foldername     .... occurence points for this LSID and place gz files in this folder\n";

    echo " \n";
    echo " --page_count=-1          .... stop after this number of pages (useful for testing)\n";
    echo " --page_size=50000        .... each page of data is this many rows (tested to work up to 50000 rows - becomes less responsive over this)\n";
    echo " \n";
    echo " --info_only=true         .... display information about the results but NOT the results\n";
    echo " --count_only=true        .... display record count only - no data\n";
    echo " \n";
    
    
    exit();    
    
    
}



?>
