<?php 
include_once 'includes.php';

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <pre>
        <?php
$DB = new DBO();
//print_r($DB->VERTEBRATA());

// print_r($DB->distributions());
// print_r($DB->occurrences());

// print_r($DB->species_names());

 print_r($DB->occurences_for_lsid(
             $lsid = "urn:lsid:biodiversity.org.au:afd.taxon:2ee18b7b-bae4-492f-9466-44d7f7a82790"
            ,$pageNumber = 0
            ,$pageSize   = 10
            )
         );

// urn:lsid:biodiversity.org.au:afd.name:242202

        ?>
        </pre>
    </body>
</html>
