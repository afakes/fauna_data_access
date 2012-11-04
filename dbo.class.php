<?php
include_once '../utilities/includes.php';
/**
 * CLASS: 
 *        
 * 
 *   
 */
class DBO extends Object {
    //**put your code here
    
    
    public static $OCC_PAGE_SIZE = 1000;          # occurrence records per biocache request
    public static $SPECIES_PAGE_SIZE = 500;       # species per BIE request
    public static $BIE = 'http://bie.ala.org.au/';

    public static $AUSTRALIA_POLY = 'POLYGON((142.35449218749 -9.5513184389362,111.10937499999 -12.870040697133,114.00976562499 -37.137873247106,148.02343749999 -44.067356780858,155.9775390625 -26.838159010597,142.35449218749 -9.5513184389362))';
    
    public static $BIO_CACHE_URL =  'http://biocache.ala.org.au/';
    
    
    public function __construct() { 
        parent::__construct();
        
        
    }
    
    public function __destruct() {    
        parent::__destruct();
        
        
    }

    public function ReadWriteProperty() {
        if (func_num_args() == 0) return $this->getProperty();
        return $this->setProperty(func_get_arg(0));
        
    }

    public function VERTEBRATA() 
    {
        $f = file_get_contents("http://bie.ala.org.au/species/VERTEBRATA.json");

        $j = json_decode($f);
        
        return  $j;
        
    }
    
    public function distributions() 
    {
        $f = file_get_contents("http://spatial.ala.org.au/ws/distributions");

        $j = json_decode($f);
        
        return  $j;
        
    }
    

    public function occurrences($name = "macropus") 
    {
        $f = file_get_contents("http://biocache.ala.org.au/ws/webportal/occurrences?q={$name}");

        $j = json_decode($f);
        
        return  $j;
        
    }

    
    public function species_names($name = "",$pageNumber = 0,$pageSize = 100) 
    {
        
        $f = file_get_contents("http://biocache.ala.org.au/ws/webportal/species?q={$name}&pageSize={$pageSize}&start={$pageNumber}");

        $j = json_decode($f);
        
        return  $j;
        
    }

    
    public function occurences_for_lsid($lsid = "",$pageNumber = 0,$pageSize = 100) 
    {
        
        $f = file_get_contents("http://biocache.ala.org.au/ws/webportal/occurrences?q=lsid:{$lsid}&pageSize={$pageSize}&start={$pageNumber}");

        $j = json_decode($f);
        
        
        $full_result = array();
        foreach ($j->occurrences as $occurrence) 
        {          
            
            $result = array();    
            $result['basisOfRecord']    = $occurrence->basisOfRecord;
            $result['geospatialKosher'] = $occurrence->geospatialKosher;
            
            $result['decimalLatitude']  = $occurrence->decimalLatitude;
            $result['decimalLongitude'] = $occurrence->decimalLongitude;
            $result['year']             = $occurrence->year;
            $result['month']            = $occurrence->month;
            
            $full_result[] = $result;
            
        }
        
        
        return  $full_result;
        
    }
    
    
// 
    
    
//     
    

    
    
    // 
    
    
//url = BIOCACHE + 'ws/occurrences/search'
//    params = {
//        'q': q_param(species_lsid, changed_since),
//        'fl': ','.join(('id', 'latitude', 'longitude', 'sensitive_longitude',
//            'sensitive_latitude', 'assertions', 'coordinate_uncertainty',
//            'sensitive_coordinate_uncertainty', 'occurrence_date',
//            'basis_of_record', 'year', 'month')),
//        'facet': 'off',
//        'wkt': AUSTRALIA_POLY,
//        'pageSize': OCC_PAGE_SIZE
//    }
//
    
    
    
}

?>