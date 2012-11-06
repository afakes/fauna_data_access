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

if (util::contains($name, ':lsid:')) $name = "lsid:{$name}";

$lsid = util::CommandLineOptionValue($argv, 'lsid','');
if ($lsid != '') $name = "lsid:$lsid"; 

if ($name == '') 
{
    echo "ERROR: name or lsid not specified \n";
    usage($argv);
    exit();
}

$csv = util::CommandLineOptionValue($argv, 'csv',false);

$table = get_info($name);


if ($csv)
    matrix::display($table, ",", null, null);
else
    matrix::display($table, " ", null, 30);

exit();


// =======================================================================================
// ========= FUNCTIONS ===================================================================
// =======================================================================================


function get_info($name)
{
    
    $info = json_decode(json_data_by_page($name,0,1),true); // make one read to get number of records
    
    
    $facetResults = array_util::Value($info, 'facetResults', null);
    if (is_null($facetResults)) return null;
    
    
    // breakout facetResults  into tables that are easy to read;
    // facetResults contains a lot of two column tables
    // below is basic structure
    //
    // so the  fieldName is the name of the first column in the table and count is the second column
    // 
    //
    
    
/*
     [43] => Array
                (
                    [fieldName] => occurrence_year
                    [fieldResult] => Array
                        (
                            [0] => Array
                                (
                                    [label] => 1850-01-01T00:00:00Z
                                    [count] => 4
                                )

                            [1] => Array
                                (
                                    [label] => 1860-01-01T00:00:00Z
                                    [count] => 47
                                )

                            [2] => Array
                                (
                                    [label] => 1870-01-01T00:00:00Z
                                    [count] => 2
                                )
* 
 */    
    

    $result = array();
    
    foreach ($facetResults as $raw_table) 
    {
        
        foreach ($raw_table['fieldResult'] as $fieldResulRow) 
        {
            $row = array();
            $row['table'] = $raw_table['fieldName'];
            $row['value'] = $fieldResulRow['label'];
            $row['count'] = $fieldResulRow['count'];
            $result[] = $row;
        }
        
    }
    
    return $result;
    
}




function json_data_by_page($name = "",$pageIndex = 0,$page_size = 100) 
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
    
    echo " php {$argv[0]} --name=SpeciesName     ... get the number of records that would be returned for this name\n";
    echo " php {$argv[0]} --name=lsid:(ALA LSID) ...  '   '     '    '    '      '    '    '     '     '    '  LSID\n";
    echo " php {$argv[0]} --lsid=(ALA LSID)      ...  '   '     '    '    '      '    '    '     '     '    '  LSID \n";
    echo " \n";
    echo "     --csv=true  .... result will be COMMA seperated  ";
    echo " \n";
    
    exit();    
    
    
}



?>
