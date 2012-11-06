<?php
include_once 'includes.php';

/*
 * READ All Data from ALA  and save .JSON file.
 * 
 * yes the programming in here could be way more efficient but it would be much harder to read.
 * so if you are going to use properly - please look at the IF statements and other loops
 * 
 */

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


$name_as_folder = util::CommandLineOptionValue($argv, 'name_as_folder',false);


$output = util::CommandLineOptionValue($argv, 'output','');     // default is  -1  download all pages
if (!$name_as_folder)
    if (file_exists($output)) 
    {
        echo "ERROR: output file already exists [{$output}] \n";
        exit();
    }

$page_count = util::CommandLineOptionValue($argv, 'page_count',-1);       // default is  -1  download all pages
$page_size  = util::CommandLineOptionValue($argv, 'page_size',500);       // default is 100 rows per page

$decode     = util::CommandLineOptionValue($argv, 'decode',false);        // decode JSON into a PHP PRINT_R format


$chunk = occurrences_by_page($name,0,1); // make one read to get number of records
$totalRecords = $chunk['totalRecords'];


if ($page_count == -1) $page_count = ceil($totalRecords / $page_size);  // we didn't set  page count so set to highest possible

$pageIndex = 0;
$part_number = 0;

while ($part_number < $page_count )  // lop while the size of the hunk is as big as the page
{            
    echo "Get Part {$part_number}\n";
    
    $chunk = occurrences_by_page($name,$pageIndex,$page_size);
    write_row($name,$output,$decode,$chunk,$part_number,$name_as_folder);
        
    
    $pageIndex += $page_size;
    $part_number++;
    
}

exit();


// =======================================================================================
// ========= FUNCTIONS ===================================================================
// =======================================================================================


function write_row($name,$output,$decode,$chunk,$part_number,$name_as_folder)
{
    
    if ($name_as_folder)
    {
        
        if ($output == '')
            $folder_name = "{$name}";    
        else
        {
            
            if (!is_dir($output)) 
            {
                echo "make directory {$output}\n";
                mkdir($output);   // make sure we have some where to put the files
            }
            
            $folder_name = "{$output}/{$name}";
        }
            
        
        
        if (!is_dir($folder_name)) 
        {
            echo "make directory {$folder_name}\n";            
            mkdir($folder_name);   // make sure we have some where to put the files
        }
        
        $output = "{$output}/$name/part";
        
    }
    
    
    if ($output == '')  // if we don't want to save to a file then just echo to screen STDOUT
    {
        if ($decode)
            echo print_r(json_decode($chunk,true));
        else    
            echo $chunk;
    }
    else
    {
        // $output is not EMPTY so we use this a filename 
        
        if ($decode)
        {
            echo "writing decoded text to [{$output}]\n";
            file_put_contents($output, print_r(json_decode($chunk,true),true), FILE_APPEND);
        }
        else
        {
            $output .= "_".sprintf("%06d",$part_number).".json";
            echo "writing JSON data to [{$output}]\n";
            file_put_contents($output, $chunk, FILE_APPEND);
        }
        
    }
    
    
}


function occurrences_by_page($name = "",$pageIndex = 0,$page_size = 100) 
{
    
    $url = "http://biocache.ala.org.au/ws/webportal/occurrences?q={$name}&pageSize={$page_size}&start={$pageIndex}";
    
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
            echo "FAILED:: after 5 retries - could not get occurrences data from [{$url}]";
            exit();
        }
            
            
    }

    return  $f;

}



function usage($argv)
{
    
    if ( !(!isset($argv[1]) ||  $argv[1] == "--help" || $argv[1] == "-h")) return;    
    
    echo " \n";
    echo " php {$argv[0]} --name=SpeciesName     --decode=false --name_as_folder=true                ... create a folder in the current folder     with the same name as the value in '--name' and download JSON files to there\n";
    echo " php {$argv[0]} --name=SpeciesName     --decode=false --name_as_folder=true --output=data  ... create a folder inside the 'data' folder  with the same name as the value in '--name' and download JSON files to there\n";
    echo " \n";
    echo " php {$argv[0]} --name=SpeciesName     --decode=false --output=fred         ... download JSON format data and save into a files callewd fred.1.json, fred.2.json  etc\n";
    echo " php {$argv[0]} --name=lsid:(ALA LSID) --decode=false --output=fred \n";
    echo " php {$argv[0]} --lsid=(ALA LSID)      --decode=false --output=fred \n";
    echo " \n";
    echo " php {$argv[0]} --name=SpeciesName     --decode=true  --output=bert.txt     ... download data and save as text into a file called 'bert.txt'\n";
    echo " php {$argv[0]} --name=lsid:(ALA LSID) --decode=true  --output=bert.txt \n";
    echo " php {$argv[0]} --lsid=(ALA LSID)      --decode=true  --output=bert.txt \n";
    
    echo " \n";
    echo " --page_count=-1|integer          .... stop after this number of pages (useful for testing)\n";
    echo " --page_size=100|(integer < 500)  .... each page of data is this many rows (tested to work up to 500 rows - becomes less responsive over 500)\n";
    echo " \n";
    echo " --decode=false|true              .... decode JSON data into PHP print_r format - sometimes easier to handle / human readable\n";
    echo " \n";
    
    exit();    
    
    
}



?>
